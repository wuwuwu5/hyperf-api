<?php

function reload()
{
    echo "\033[32m reload..... \033[0m" . PHP_EOL;
    $pid = intval(file_get_contents(dirname(__DIR__) . '/runtime/hyperf.pid'));
    \Swoole\Process::kill($pid, SIGUSR1);
    echo "\033[32m reload success \033[0m" . PHP_EOL;

}

if (!function_exists('redisId')) {
    function redisId($key)
    {
        return config('rediskey.' . $key);
    }
}


if (!function_exists('redis')) {
    function redis()
    {
        $container = \Hyperf\Utils\ApplicationContext::getContainer();
        return $container->get(\Redis::class);
    }
}


if (!function_exists('container')) {
    function container()
    {
        return \Hyperf\Utils\ApplicationContext::getContainer();
    }
}


/**
 *
 */
if (!function_exists('request')) {
    function request()
    {
        return make(\Hyperf\HttpMessage\Server\Request::class);
    }
}

/**
 *
 */
if (!function_exists('response')) {
    function response()
    {
        return container()->get(\Hyperf\HttpServer\Contract\ResponseInterface::class);
    }
}