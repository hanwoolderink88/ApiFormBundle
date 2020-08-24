<?php

namespace Hanwoolderink\ApiForm\ApiForm;

class ApiFormItem
{
    private const ALLOWED_TYPES = [
        ApiFormItemFactory::TYPE_STRING,
        ApiFormItemFactory::TYPE_INT,
        ApiFormItemFactory::TYPE_FLOAT,
        ApiFormItemFactory::TYPE_ARRAY,
        ApiFormItemFactory::TYPE_PASSWORD,
        ApiFormItemFactory::TYPE_DATE,
        ApiFormItemFactory::TYPE_DATETIME,
    ];

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $type = 'string';

    /**
     * @var bool
     */
    protected bool $required = false;

    /**
     * @var bool
     */
    protected bool $canChange = true;

    /**
     * @var bool
     */
    protected bool $unique = false;

    /**
     * @var string|null
     */
    protected ?string $regex = null;

    /**
     * @var string
     */
    protected string $regexErrorMessage = 'Regex does not comply';

    /**
     * @var int|null
     */
    protected ?int $minLength = null;

    /**
     * @var int|null
     */
    protected ?int $maxLength = null;

    /**
     * ApiFormItem constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ApiFormItem
     */
    public function setName(string $name): ApiFormItem
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return ApiFormItem
     * @throws ApiFormMisconfiguredException
     */
    public function setType(string $type): ApiFormItem
    {
        if (in_array($type, self::ALLOWED_TYPES, true) === false) {
            $class = self::class;
            throw new ApiFormMisconfiguredException("{$type} is not allowed as {$class}::type in {$this->name}");
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     * @return ApiFormItem
     */
    public function setRequired(bool $required): ApiFormItem
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCanChange(): bool
    {
        return $this->canChange;
    }

    /**
     * @param bool $canChange
     * @return ApiFormItem
     */
    public function setCanChange(bool $canChange): ApiFormItem
    {
        $this->canChange = $canChange;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->unique;
    }

    /**
     * @param bool $unique
     * @return ApiFormItem
     */
    public function setUnique(bool $unique): ApiFormItem
    {
        $this->unique = $unique;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegex(): ?string
    {
        return $this->regex;
    }

    /**
     * @param string|null $regex
     * @return ApiFormItem
     */
    public function setRegex(?string $regex): ApiFormItem
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegexErrorMessage(): string
    {
        return $this->regexErrorMessage;
    }

    /**
     * @param string $regexErrorMessage
     * @return ApiFormItem
     */
    public function setRegexErrorMessage(string $regexErrorMessage): ApiFormItem
    {
        $this->regexErrorMessage = $regexErrorMessage;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinLength(): ?int
    {
        return $this->minLength;
    }

    /**
     * @param int|null $minLength
     * @return ApiFormItem
     */
    public function setMinLength(?int $minLength): ApiFormItem
    {
        $this->minLength = $minLength;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    /**
     * @param int|null $maxLength
     * @return ApiFormItem
     */
    public function setMaxLength(?int $maxLength): ApiFormItem
    {
        $this->maxLength = $maxLength;

        return $this;
    }
}
