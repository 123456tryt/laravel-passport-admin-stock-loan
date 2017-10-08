<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Model\Base
 *
 * @mixin \Eloquent
 */
class Base extends Model
{
    /**
     * 生成对应redis的key
     * @return string
     */
    public function GenerateCacheKey()
    {
        return $this->table . '.' . $this->id;
    }

    /**
     * 通过主键获取 redis数据
     * @param int $primaryId
     */
    public function getCachedModelBy(int $primaryId)
    {

        //TODO::
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // ... code here
        });

        self::created(function ($model) {
            // ... code here
            //TODO::创建redis
        });

        self::updating(function ($model) {
            // ... code here
        });

        self::updated(function ($model) {
            // ... code here
            //TODO::更新redis

        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
            //TODO::销毁redis

        });
    }

    const CREATED_AT = "created_time";
    const UPDATED_AT = "updated_time";
}