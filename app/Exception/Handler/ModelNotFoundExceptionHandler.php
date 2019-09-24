<?php

namespace App\Exception\Handler;


use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ModelNotFoundExceptionHandler extends ExceptionHandler
{

    /**
     * Handle the exception, and return the specified result.
     */
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if (wantJson()) {
            $data = json_encode([
                'code' => 404,
                'message' => '数据不存在',
            ], JSON_UNESCAPED_UNICODE);

            return $response
                ->withStatus($throwable->getCode())
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withBody(new SwooleStream($data));
        }
    }

    /**
     * Determine if the current exception handler should handle the exception,.
     *
     * @return bool
     *              If return true, then this exception handler will handle the exception,
     *              If return false, then delegate to next handler
     */
    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}