<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;
use App\Http\Model\SystemParams;

/**
 * Class SystemController 系统代理商
 * @package App\Http\Controllers\Api
 */
class SystemParamsController extends Controller
{
    //use \App\Http\Controllers\Load\ShowTrait, \App\Http\Controllers\Load\UpdateTrait;

    public static $model_name = 'SystemParams';

    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function index()
    {
        $agent_id = \Auth::user()->agent_id;
        $rs['list'] = \DB::select(
            'select any_value(id) as id,`key`,any_value(value)as value,any_value(remark)as remark,
            any_value(agent_id)as agent_id,any_value(param_type)as param_type
            from(select * from s_system_params where agent_id in (0,?) group by id order by agent_id desc)a group by `key`'
            , [$agent_id]);
        return self::jsonReturn($rs);
    }

    public function update($data = [])
    {
        $agent_id = \Auth::user()->agent_id;
        $params = $data ?: request()->all();
        $rs = SystemParams::select('key', 'value')->where('agent_id', 0)->get();
        foreach ($rs as $v) {
            $arr[$v['key']] = $v['value'];
        }
        foreach ($params as $v) {
            if ($agent_id == 0) {
                SystemParams::where([['id', $v['id']], ['agent_id', 0]])->limit(1)->update($v);
            } elseif ($v['agent_id'] == $agent_id) {
                SystemParams::where([['id', $v['id']], ['agent_id', $agent_id]])->limit(1)->update($v);
            } elseif ($agent_id != 0 && $v['agent_id'] == 0 && $v['value'] != $arr[$v['key']]) {
                unset($v['id']);
                $v['agent_id'] = $agent_id;
                $result = SystemParams::where([['key', $v['key']], ['agent_id', $agent_id]])->first();
                if (!$result) SystemParams::create($v);
                elseif ($result && $result['value'] != $v['value']) SystemParams::where([['key', $v['key']], ['agent_id', $agent_id]])->update($v);
                else continue;
            } else {
                continue;
            }
        }
        return self::jsonReturn([], 1, '更新成功！');
    }
}