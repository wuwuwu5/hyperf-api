<?php

declare(strict_types=1);

namespace App\Aspect;

use App\Annotation\Login;
use App\Log;
use EasyWeChat\Factory;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Redis\Redis;
use Hyperf\Utils\Context;

/**
 * @Aspect
 */
class LoginAspect extends AbstractAspect
{
    /**
     * @Inject
     * @var ResponseInterface
     */
    public $response;

    /**
     * @Inject
     * @var RequestInterface
     */
    public $request;

    /**
     * @Inject
     * @var Log
     */
    protected $logger;

    /**
     * 切入的类
     *
     * @var array
     */
    public $classes = [
        'App\Controller\Student\LoginController::login',
    ];

    /**
     * 切入的注解类
     *
     * @var array
     */
    public $annotations = [
        Login::class
    ];


    /**
     * @param ProceedingJoinPoint $proceedingJoinPoint
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        // 日志
        $logger = $this->logger->get('default');

        /** @var Login $auth */
        $auth = $proceedingJoinPoint->getAnnotationMetadata()->method[Login::class];

        if (empty($auth)) {
            $logger->info($proceedingJoinPoint->className . 'Login注解缺失');
            return $this->response->json(['message' => '登录失败', 'code' => '403']);
        }

        $config = config('wechat.' . \App\Models\Student::class);

        if (empty($config)) {
            $logger->info($proceedingJoinPoint->className . '小程序配置缺失');
            return $this->response->json(['message' => '登录失败', 'code' => '403']);
        }

        // 实例化小程序
        $app = Factory::miniProgram($config);

        // 获取微信CODE
        $code = $this->request->input('code');

        // 获取用户微信信息
        $session = $app->auth->session((string)$code);

        if (empty($session) || isset($session['errcode'])) {
            $logger->info($proceedingJoinPoint->className . '小程序SESSION获取失败');
            return $this->response->json(['message' => '登录失败', 'code' => '403']);
        }

        $unionid = data_get($session, 'unionid');

         //实例化Redis
        $redis = redis();

         //获取用户信息
        $user = $redis->get($auth->auth . ':' . $unionid);

        if (empty($user)) {
             //数据库查询
            $user = make($auth->auth)->where(compact('unionid'))->first();

            if (!empty($user)) {
                $redis->set($auth->auth . ':' . $unionid, serialize($user));
            }
        } else {
            $user = unserialize($user);
        }

        Context::set('auth_user', $user);
        Context::set('unionid', $unionid);
        Context::set('openid', data_get($session, 'openid'));

        return $proceedingJoinPoint->process();
    }
}
