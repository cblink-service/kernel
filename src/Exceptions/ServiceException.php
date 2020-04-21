<?php

namespace Cblink\Service\Kennel\Exceptions;

use InvalidArgumentException;

/**
 * Class ServiceException
 * @package Cblink\Service\Kennel\Exceptions
 */
class ServiceException extends InvalidArgumentException
{
    protected $code = 500;
}
