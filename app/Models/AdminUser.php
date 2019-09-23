<?php

declare (strict_types=1);

namespace App\Models;

use App\Controller\Auth\JWTSubject;
use Hyperf\DbConnection\Model\Model;

/**
 */
class AdminUser extends Model implements JWTSubject
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_users';
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
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * 用户查询
     *
     * @param $query
     * @param $username
     * @return mixed
     */
    public function scopeSearchUser($query, $username)
    {
        return $query->where('username', $username)->orWhere('phone', $username);
    }

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