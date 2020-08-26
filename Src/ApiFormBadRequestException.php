<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace Hanwoolderink88\ApiForm\Src;

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
