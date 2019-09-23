<?php

declare(strict_types=1);

namespace App\Controller\Api\Student;

use App\Controller\Auth\JWT;
use App\Event\LastLoginTimeEvent;
use App\Models\Student;
use EasyWeChat\Factory;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Annotation\PostMapping;
use App\Annotation\Login;
use Hyperf\Utils\Context;
use App\Controller\Controller as BaseController;
use Hyperf\HttpServer\Annotation\Controller;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @Controller(prefix="/api/student")
 */
class LoginController extends BaseController
{

    /**
     * @Inject
     * @var JWT
     */
    protected $jwt;

    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    /**
     * 登录
     *
     * @PostMapping(path="login")
     * @Login(auth="App\Models\Student")
     */
    public function login(RequestInterface $request, ResponseInterface $response)
    {
        $user = Context::get('auth_user');

        if (empty($user)) {
            $user = Student::create([
                'openid' => Context::get('openid'),
                'unionid' => Context::get('unionid'),
                'avatar' => $request->input('userInfo.avatarUrl'),
                'nickname' => $request->input('userInfo.nickName'),
                'gender' => $request->input('userInfo.gender'),
            ]);
        }

        // 更新最后登录时间
        $this->eventDispatcher->dispatch(new LastLoginTimeEvent($user));

        $token = $this->jwt->fromUser($user);

        // 返回数据
        return $this->response(compact('token'));
    }
}
