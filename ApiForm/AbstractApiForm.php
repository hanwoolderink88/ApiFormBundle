<?php

namespace Hanwoolderink\ApiForm\ApiForm;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Hanwoolderink\ApiForm\DependencyInjection\RequestService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

abstract class AbstractApiForm
{
    /**
     * @var array|ApiFormItem[]
     */
    protected array $form = [];

    /**
     * @var array
     */
    private array $errors = [];

    /**
     * @var mixed
     */
    private $entity;

    /**
     * @var RequestService |null
     */
    private ?RequestService $request;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    /**
     * @var bool
     */
    private bool $isNew = false;

    /**
     * AbstractForm constructor.
     * @param RequestService $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        RequestService $request,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->request = $request;
        $this->em = $em;
        $this->encoder = $encoder;

        $this->config(new ApiFormItemFactory());
    }

    /**
     * Config goes in this method, this will be called in the abstract constructor to set the defined values
     *
     * $this->form[] = [];
     * @param ApiFormItemFactory $factory
     */
    abstract protected function config(ApiFormItemFactory $factory): void;

    /**
     * @param $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        $this->isNew = $this->entity->getId() === null;
    }

    /**
     * Check if all fields are filled correct and all required fields are there
     *
     * @return bool
     * @throws Exception
     */
    public function isValid(): bool
    {
        // reset errors
        $this->errors = [];

        foreach ($this->form as $item) {
            $value = $this->request->getBody($item->getName());
            $itemValidator = new ApiFormItemValidator($item, $value, $this->isNew);
            if ($itemValidator->isValid() === false) {
                $this->errors = array_merge($this->errors, $itemValidator->getErrors());
            }
        }

        // no errors reported means it's valid
        return empty($this->errors);
    }

    /**
     * Check if the form can be posted, basically an unique check for CREATE and exist check for UPDATE
     *
     * @return bool
     * @throws NonUniqueResultException
     */
    public function canPost(): bool
    {
        $this->isUnique();

        return empty($this->errors);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function postForm()
    {
        foreach ($this->form as $item) {
            $name = $item->getName();
            $method = 'set' . ucfirst($name);
            $value = $this->request->getBody($name);

            if ($name !== null && $value !== null && method_exists($this->entity, $method)) {
                if ($item->getType() === ApiFormItemFactory::TYPE_PASSWORD) {
                    $value = $this->encoder->encodePassword($this->entity, $value);
                }

                if ($item->getType() === ApiFormItemFactory::TYPE_DATE ||
                    $item->getType() === ApiFormItemFactory::TYPE_DATETIME
                ) {
                    // strip the milliseconds and timezone
                    $value = explode('.', $value, 1)[0];
                    $value = new DateTime($value);
                }

                call_user_func([$this->entity, $method], $value);
            }
        }

        return $this->entity;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     * @throws NonUniqueResultException
     */
    private function isUnique(): bool
    {
        $uniqueFields = [];
        foreach ($this->form as $item) {
            if ($item->isUnique() === true) {
                $name = $item->getName();
                $value = $this->request->getBody($name);
                $uniqueFields[$name] = $value;
            }
        }

        // no unique fields means we do not have to check
        if (empty($uniqueFields)) {
            return true;
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('t');
        $qb->from(get_class($this->entity), 't');
        if (!$this->isNew) {
            $qb->where("t.id <> :id")->setParameter("id", $this->entity->getId());
        }

        $i = 0;
        $andWhereQ = '';
        foreach ($uniqueFields as $column => $value) {
            $i++;
            $andWhereQ .= empty($andWhereQ) === false ? ' OR ' : '';
            $andWhereQ .= "t.{$column} = :$column ";
            $qb->setParameter($column, $value);
        }
        $qb->andWhere($andWhereQ);

        $qb->setMaxResults(1);
        $q = $qb->getQuery();
        $result = $q->getOneOrNullResult();

        if ($result !== null) {
            $fieldNames = implode(', ', array_keys($uniqueFields));
            $this->errors[] = "field(s) {$fieldNames} are not unique";
        }

        // no errors means all good...
        return empty($this->errors);
    }
}
