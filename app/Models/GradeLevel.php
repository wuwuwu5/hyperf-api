<?php

declare (strict_types=1);

namespace App\Models;

use Hyperf\DbConnection\Model\Model;

/**
 */
class GradeLevel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'grade_levels';
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
     * 年级
     *
     * @return \Hyperf\Database\Model\Relations\BelongsTo
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}