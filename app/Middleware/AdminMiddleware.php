<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Controller\Auth\JWT;
use App\Models\AdminUser;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

class AdminMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;


    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;


    /**
     * @Inject
     * @var JWT
     */
    protected $jwt;


    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->jwt->check(AdminUser::class)) {
            return $this->response->json(
                [
                    'code' => 401,
                    'data' => [],
                    'message' => '无权限'
                ]
            );
        }
        return $handler->handle($request);
    }
}