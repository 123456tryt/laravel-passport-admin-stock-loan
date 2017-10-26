<?php

namespace App\Http\Controllers\Load;

trait ShowTrait
{
    use ShowBaseTrait;

    public function index($data = [])
    {
        $agent_id = \Auth::user()->agent_id;
        if (!$agent_id) return;
        $params = $data ?: request()->all();
        $keyWord = config('select.' . static::$model_name . '.keyWord');
        if ($keyWord == 'agent_id') {
            $params['where'][] = ['agent_id', $agent_id];
        }
        $rs = static::_run_orm($params);
        return self::jsonReturn($rs);
    }

    public function show($id = '', $data = [])
    {
        $agent_id = \Auth::user()->agent_id;
        if (!$agent_id) return;
        $params = $data ?: request()->all();
        if ($id) $params['where'][] = ['id', $id];
        $keyWord = config('select.' . static::$model_name . '.keyWord');
        if ($keyWord == 'agent_id') {
            $params['where'][] = ['agent_id', $agent_id];
        }
        $rs = static::_run_orm($params, $id);
        return self::jsonReturn($rs);
    }
}