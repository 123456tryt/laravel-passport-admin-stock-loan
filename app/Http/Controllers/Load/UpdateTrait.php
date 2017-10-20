<?php

namespace App\Http\Controllers\Load;

trait UpdateTrait
{
    public function update($id = '', $data = [])
    {
        if (!$id) return;
        $params = $data ?: request()->all();
        $model_path = 'App\Http\Model\\' . static::$model_name;
        $Model = new $model_path;
        $rs = $params ? $Model->find($id)->update($params) : false;
        if ($rs) return self::jsonReturn([], 1, '更新成功！');
        return self::jsonReturn([], 0, '更新失败！');
    }

    public function updateSelf($data = [])
    {
        $agent_id = \Auth::user()->agent_id;
        if (!$agent_id) return;
        $params = $data ?: request()->all();
        $model_path = 'App\Http\Model\\' . static::$model_name;
        $Model = new $model_path;
        $rs = $params ? $Model->where('agent_id', $agent_id)->limit(1)->update($params) : false;
        if ($rs) return self::jsonReturn([], 1, '更新成功！');
        return self::jsonReturn([], 0, '更新失败！');
    }
}