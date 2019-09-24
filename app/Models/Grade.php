<?php

declare (strict_types=1);

namespace App\Models;

use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;

/**
 */
class Grade extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'grades';
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
    protected $fillable = ['name'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];


    /**
     * 年级子分类
     *
     * @return \Hyperf\Database\Model\Relations\HasMany
     */
    public function gradeLevels()
    {
        return $this->hasMany(GradeLevel::class);
    }

    /**
     * 名称查询
     *
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeSearchName($query, $name)
    {
        return $query->where(compact('name'));
    }

    /**
     * 名称唯一查询
     *
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeValidateName($query, $id)
    {
        return $query->where('id', '<>', $id);
    }

    /**
     * 搜索
     *
     * @param $query
     * @param array $arg
     * @return mixed
     */
    public function scopeSearchSelect($query, array $arg = [])
    {
        if (empty($arg)) {
            $arg = ['id', 'name', 'grade_level_count'];
        }
        return $query->select($arg);
    }
}