<?php

namespace App\Annotation;

use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Login extends AbstractAnnotation
{
    /**
     * @var string
     */
    public $auth;
}