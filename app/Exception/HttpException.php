<?php

namespace App\Exception;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Server\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpException
 * @package App\Exception
 */
class HttpException extends ServerException
{
}