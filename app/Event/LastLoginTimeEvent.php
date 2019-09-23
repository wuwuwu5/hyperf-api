<?php

namespace App\Event;


class LastLoginTimeEvent
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * 获取用户
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
}