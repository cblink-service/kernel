<?php

namespace Cblink\Service\Kennel\Exceptions;

use RuntimeException;

/**
 * Class HttpRequestException
 * @package Cblink\Service\Kennel\Exceptions
 */
class HttpRequestException extends RuntimeException
{
    protected $code = 400;
}
