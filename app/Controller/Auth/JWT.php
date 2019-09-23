<?php

namespace App\Controller\Auth;

use Hyperf\HttpServer\Contract\RequestInterface;

class JWT
{
    /**
     * @var array $header
     */
    private $header;

    /**
     * @var array $payload
     */
    private $payload;

    /**
     * @var string $sign
     */
    private $sign;

    /**
     * @var string
     */
    private $token;

    /**
     * @var Object $user
     */
    private $user;


    /**
     * 设置头部信息
     *
     * @param array $header
     * @return $this
     */
    public function setHeader(array $header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * 设置负载
     *
     * @param array $payload
     * @return $this
     */
    public function setPayload(array $payload)
    {
        $this->payload = base64_encode(json_encode($payload));

        return $this;
    }

    /**
     *
     * @param JWTSubject $user
     * @return string
     */
    public function fromUser(JWTSubject $user)
    {
        $this->makeHeader()->makePayload($user);

        return $this->token();
    }


    public function check($auth)
    {
        $request = make(RequestInterface::class);

        // 获取签名
        $authorization = $request->getHeader('authorization');

        if (empty($authorization)) {
            return false;
        }

        $token = str_replace('Bearer ', '', $authorization);

        if (empty($token)) {
            return false;
        }

        try {

            // 解密
            $payload = \Firebase\JWT\JWT::decode($token[0], config('jwt.secret_key'), ['HS256']);

            // 加密后的用户model::class
            if (empty($payload->prv)) {
                return false;
            }

            // 验证是否相等
            if (sha1($auth) != $payload->prv) {
                return false;
            }

            $user = $this->user($auth, $payload->sub);

            if (empty($user)) {
                return false;
            }

            $this->token = $token;

            return true;
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            return false;
        }
    }

    /**
     * 用户信息
     *
     * @param $auth
     * @param $sub
     * @return mixed
     */
    public function user($auth, $sub)
    {
        $user = make($auth)->find($sub);

        if (empty($user)) {
            return false;
        }
        $this->user = $user;
        return $user;
    }

    /**
     * 生成token
     *
     * @return string
     */
    private function token()
    {
        return \Firebase\JWT\JWT::encode($this->payload, config('jwt.secret_key'), 'HS256', NUll, $this->header);
    }


    /**
     * 头部
     *
     * @return $this
     */
    private function makeHeader()
    {
        $this->header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];

        return $this;
    }


    /**
     * 负载
     *
     * @param JWTSubject $user
     * @return $this
     */
    private function makePayload(JWTSubject $user)
    {
        $request = make(RequestInterface::class);

        $this->payload = [
            "iss" => $request->url(), // 签发人
            "iat" => time(), // 签发时间
            "exp" => intval(config('jwt.ttl') * 60 + time()), // 过期时间
            "nbf" => time(), // 生效时间
            "jti" => time() . $user->getJWTIdentifier(), // 编号
            "sub" => $user->getJWTIdentifier(), // 主体
            "prv" => sha1(get_class($user)) // 额外信息model编码后
        ];

        return $this;
    }
}