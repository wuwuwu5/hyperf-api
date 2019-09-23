<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;


use App\Exception\HttpException;
use Hyperf\HttpServer\Contract\ResponseInterface;

abstract class Controller
{
    /**
     * 返回错误
     *
     * @param string $message
     * @param int $statusCode
     * @return HttpException
     */
    public function error(string $message, int $statusCode): HttpException
    {
        throw new HttpException($message, $statusCode);
    }


    /**
     * 返回数据
     *
     * @param array|null $data
     * @param int $code
     * @param string|null $message
     * @return ResponseInterface
     */
    public function response(?array $data, int $code = 200, string $message = null)
    {
        return response()->json(compact('data', 'code', 'message'));
    }
}
