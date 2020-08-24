<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace Hanwoolderink\ApiForm\ApiForm;

use Exception;

class ApiFormBadRequestException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Bad request';

    /**
     * @var int
     */
    protected $code = 400;
}
