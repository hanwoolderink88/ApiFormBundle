<?php

namespace Hanwoolderink\ApiForm\ApiForm;

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
