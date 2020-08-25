<?php

namespace Hanwoolderink\ApiForm\ApiForm;

use DateTime;

class ApiFormItemValidator
{
    /**
     * @var ApiFormItem
     */
    private ApiFormItem $item;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private array $errors = [];

    /**
     * @var bool
     */
    private bool $isNew;

    public function __construct(ApiFormItem $item, $value, bool $isNew)
    {
        $this->item = $item;
        $this->value = $value;
        $this->isNew = $isNew;
    }

    public function isValid(): bool
    {
        $valueIsEmpty = $this->value === null || $this->value === '';
        // required check (POST)
        if ($this->isNew && $this->item->isRequired() && $valueIsEmpty) {
            $this->errors[] = "Required field {$this->item->getName()} is missing";

            return false; // move to next item, we don't want double errors per field
        }

        // can change check (PUT|PATCH)
        if (!$this->isNew && !$this->item->isChangeable() && !$valueIsEmpty) {
            $this->errors[] = "Field {$this->item->getName()} cannot be updated";

            return false; // move to next item, we don't want double errors per field
        }

        // min/max length, type check and regex check
        if ($this->value !== null) {
            switch ($this->item->getType()) {
                case 'string':
                case 'password':
                    // type check
                    if (!$valueIsEmpty && is_string($this->value) === false) {
                        $this->errors[] = "{$this->item->getName()} is not of type string";
                        break;
                    }
                    // min length
                    if ($this->item->getMinLength() !== null && strlen($this->value) < $this->item->getMinLength()) {
                        $this->errors[] = "{$this->item->getName()} minimum length is {$this->item->getMinLength()}";
                    }
                    // max length
                    if ($this->item->getMaxLength() !== null && strlen($this->value) > $this->item->getMaxLength()) {
                        $this->errors[] = "{$this->item->getName()} maximum length is {$this->item->getMaxLength()}";
                    }
                    // regex check
                    if ($this->item->getRegex() !== null && preg_match($this->item->getRegex(), $this->value) === 0) {
                        $this->errors[] = $this->item->getRegexErrorMessage();
                    }
                    break;
                case 'int':
                    // type check
                    if (!$valueIsEmpty && is_int($this->value) === false) {
                        $this->errors[] = "{$this->item->getName()} is not of type int";
                    }
                    break;
                case 'float':
                    // type check
                    if (!$valueIsEmpty && is_float($this->value) === false) {
                        $this->errors[] = "{$this->item->getName()} is not of type float";
                    }
                    break;
                case 'array':
                    // type check
                    if (!$valueIsEmpty && is_array($this->value) === false) {
                        $this->errors[] = "{$this->item->getName()} is not of type array";
                        break;
                    }
                    // min length
                    if ($this->item->getMinLength() !== null && count($this->value) < $this->item->getMinLength()) {
                        $this->errors[] = "{$this->item->getName()} minimum length is {$this->item->getMinLength()}";
                    }
                    // max length
                    if ($this->item->getMaxLength() !== null && count($this->value) > $this->item->getMaxLength()) {
                        $this->errors[] = "{$this->item->getName()} maximum length is {$this->item->getMaxLength()}";
                    }
                    break;
                case 'date':
                    // type check
                    if ($this->validateDate($this->value) === false) {
                        $this->errors[] = "{$this->item->getName()} should be in format yyyy-mm-dd and filled";
                    }
                    break;
                case 'datetime':
                    // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/toJSON
                    $parts = explode('T', $this->value, 2);
                    $dateIsInvalid = isset($parts[0]) && $this->validateDate($parts[0], 'Y-m-d') === false;
                    $timeIsInvalid = isset($parts[1]) && $this->validateTime($parts[1]) === false;
                    if ($dateIsInvalid || $timeIsInvalid) {
                        $this->errors[] = "{$this->item->getName()} should be in format 2020-01-01T23:50 and filled";
                    }
                    break;
            }
        }

        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param $date
     * @param string $format
     * @return bool
     */
    private function validateDate($date, $format = 'Y-m-d'): bool
    {
        $d = DateTime::createFromFormat($format, $date);

        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits
        // so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    /**
     *
     * @param string $time
     * @return bool
     */
    private function validateTime(string $time): bool
    {
        $fullRegex = '/^(?:2[0-3]|[01][0-9]):[0-5][0-9].[0-9][0-9][0-9][Z]$/';
        $regex = '/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/';

        $smallTime = preg_match($regex, $time, $m1);
        $bigTime = preg_match($fullRegex, $time, $m2);

        return $smallTime || $bigTime;
    }
}
