<?php

namespace App\Http\Controllers\Load;

trait DestroyTrait
{
    public function destroy($id = '')
    {
        if (!$id) return;
        $agent_id = \Auth::user()->agent_id;
        if (!$agent_id) return;
        $model_path = 'App\Http\Model\\' . static::$model_name;
        $Model = new $model_path;
        $params['id'] = $id;
        $keyWord = config('select.' . static::$model_name . '.keyWord');
        if ($keyWord == 'agent_id') {
            $params['where']['agent_id'] = $agent_id;
        }
        $rs = $params ? $Model->where($params)->limit(1)->delete() : false;
        if ($rs) return self::jsonReturn([], 1, '删除成功！');
        return self::jsonReturn([], 0, '删除失败！');
    }
}