<?php

declare(strict_types=1);

namespace App\Controller\Api\Admin;

use App\Controller\Auth\JWT;
use App\Models\AdminUser;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\Di\Annotation\Inject;
use App\Controller\Controller as BaseController;

/**
 * Class LoginController
 * @package App\Controller\Admin
 * @Controller(prefix="/api/admin")
 */
class LoginController extends BaseController
{
    /**
     * @Inject
     * @var JWT
     */
    protected $jwt;

    /**
     * @PostMapping(path="login")
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \App\Exception\HttpException | ResponseInterface
     */
    public function login(RequestInterface $request, ResponseInterface $response)
    {
        $adminUser = AdminUser::searchUser($request->input('username'))->first();

        if (empty($adminUser)) {
            return $this->error("用户名或密码错误", 404);
        }

        if (!password_verify($request->input('password'), $adminUser->password)) {
            return $this->error("用户名或密码错误", 403);
        }

        $token = $this->jwt->fromUser($adminUser);

        return $this->response(compact('token'));
    }
}
