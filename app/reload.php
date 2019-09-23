<?php

echo "\033[32m reload..... \033[0m" . PHP_EOL;
$pid = intval(file_get_contents(dirname(__DIR__) . '/runtime/hyperf.pid'));

\Swoole\Process::kill($pid, SIGTERM);

exec("/usr/bin/env php " . dirname(__DIR__) . '/bin/hyperf.php start');

echo "\033[32m reload success \033[0m" . PHP_EOL;


