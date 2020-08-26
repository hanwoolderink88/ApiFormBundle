<?php

namespace Hanwoolderink88\ApiForm\Src;

class ApiFormItem
{
    public const TYPE_STRING = 'string';
    public const TYPE_INT = 'int';
    public const TYPE_FLOAT = 'float';
    public const TYPE_ARRAY = 'array';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_DATE = 'date';
    public const TYPE_DATETIME = 'datetime';

    private const ALLOWED_TYPES = [
        self::TYPE_STRING,
        self::TYPE_INT,
        self::TYPE_FLOAT,
        self::TYPE_ARRAY,
        self::TYPE_PASSWORD,
        self::TYPE_DATE,
        self::TYPE_DATETIME,
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
    protected bool $changeable = true;

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
    public function isChangeable(): bool
    {
        return $this->changeable;
    }

    /**
     * @param bool $changeable
     * @return ApiFormItem
     */
    public function setChangeable(bool $changeable): self
    {
        $this->changeable = $changeable;

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
     * @throws ApiFormMisconfiguredException
     */
    public function setRegex(?string $regex): ApiFormItem
    {
        if (@preg_match($regex, '') === false) {
            throw new ApiFormMisconfiguredException("Not a regex for {$this->name}");
        }

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
