<?php

namespace App\Http\Controllers\Load;

trait ShowTrait
{
    use ShowBaseTrait;

    public function index($data = [])
    {
        $params = $data ?: request()->all();
        $rs = static::_run_orm($params);
        return self::jsonReturn($rs);
    }

    public function show($id = '', $data = [])
    {
        $params = $data ?: request()->all();
        if (!$id) return;
        $params['where'][] = ['id', $id];
        $rs = static::_run_orm($params, $id);
        return self::jsonReturn($rs);
    }

    public function showSelf($data = [])
    {
        $params = $data ?: request()->all();
        $agent_id = \Auth::user()->agent_id;
        if (!$agent_id) return;
        $params['where'][] = ['agent_id', $agent_id];
        $rs = static::_run_orm($params, '');
        return self::jsonReturn($rs);
    }

}