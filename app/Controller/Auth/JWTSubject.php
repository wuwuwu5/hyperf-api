<?php

namespace App\Controller\Auth;


interface JWTSubject
{
    /**
     * 查询主键
     *
     * @return mixed
     */
    public function getJWTIdentifier();
}