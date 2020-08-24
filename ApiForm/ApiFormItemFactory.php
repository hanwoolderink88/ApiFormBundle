<?php

namespace Hanwoolderink\ApiForm\ApiForm;

class ApiFormItemFactory
{
    public const TYPE_STRING = 'string';
    public const TYPE_INT = 'int';
    public const TYPE_FLOAT = 'float';
    public const TYPE_ARRAY = 'array';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_DATE = 'date';
    public const TYPE_DATETIME = 'datetime';

    /**
     * @param string $name
     * @return ApiFormItem
     */
    public function createItem(string $name)
    {
        return new ApiFormItem($name);
    }
}
