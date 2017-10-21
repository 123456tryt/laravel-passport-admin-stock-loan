<?php

namespace App\Http\Controllers\Load;

trait UpdateTrait
{
    public function update($id = '', $data = [])
    {
        $agent_id = \Auth::user()->agent_id;
        if (!$agent_id) return;
        $params = $data ?: request()->all();
        $model_path = 'App\Http\Model\\' . static::$model_name;
        $Model = new $model_path;
        if ($id) $where[] = ['id', $id];
        $where[] = ['agent_id', $agent_id];
        $rs = $params ? $Model->where($where)->limit(1)->update($params) : false;
        if ($rs) return self::jsonReturn([], 1, '更新成功！');
        return self::jsonReturn([], 0, '更新失败！');
    }
}