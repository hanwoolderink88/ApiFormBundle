<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace Hanwoolderink88\ApiForm\Src;

use Exception;

class ApiFormMisconfiguredException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'ApiFormItem misconfigured';

    /**
     * @var int
     */
    protected $code = 500;
}
