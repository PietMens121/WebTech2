<?php
namespace App\Exceptions\Container;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Undocumented class
 */
class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{

}