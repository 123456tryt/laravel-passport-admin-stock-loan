<?php

namespace App\Http\Controllers\Load;

trait UpdateTrait
{
    public function update($id = '', $data = [])
    {
        $agent_id = \Auth::user()->agent_id;
        if (!$agent_id) return;
        $keyWord = config('select.' . static::$model_name . '.keyWord');
        if ($keyWord == 'agent_id') {
            $where[] = ['agent_id', $agent_id];
        }
        if ($id) $where[] = ['id', $id];
        $model_path = 'App\Http\Model\\' . static::$model_name;
        $Model = $model_path::where($where)->first();
        if (!$Model) return self::jsonReturn([], 0, '更新对象不存在！');
        $params = $data ?: request()->all();
        foreach ($params as $k => $v) {
            $Model->$k = $v;
        }
        $rs = $Model->save();
        if ($rs) return self::jsonReturn([], 1, '更新成功！');
        return self::jsonReturn([], 0, '更新失败！');
    }
}