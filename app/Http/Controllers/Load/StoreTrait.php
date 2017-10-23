<?php

namespace App\Http\Controllers\Load;

trait StoreTrait
{
    public function store($data = [])
    {
        $agent_id = \Auth::user()->agent_id;
        if (!$agent_id) return;
        $params = $data ?: request()->all();
        $model_path = 'App\Http\Model\\' . static::$model_name;
        $Model = new $model_path;
        $keyWord = config('select.' . static::$model_name . '.keyWord');
        if ($keyWord == 'agent_id') {
            $params['where']['agent_id'] = $agent_id;
        }
        $rs = $params ? $Model->create($params) : false;
        if ($rs) return self::jsonReturn([], 1, '新增成功！');
        return self::jsonReturn([], 0, '新增失败！');
    }
}