<?php

declare (strict_types=1);

namespace App\Models;

use App\Controller\Auth\JWTSubject;
use Hyperf\DbConnection\Model\Model;

/**
 */
class Student extends Model implements JWTSubject
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'students';
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'default';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nickname', 'real_name', 'avatar', 'gender', 'openid', 'unionid'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * 查询主键
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->{$this->primaryKey};
    }
}