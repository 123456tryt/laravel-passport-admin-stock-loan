<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\Agent;
use App\Http\Model\AgentInfo;
use App\Http\Model\AgentProfitRateConfig;
use App\Http\Model\SystemConfigConfig;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class SystemController 系统代理商
 * @package App\Http\Controllers\Api
 */
class SystemController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }


    public function agentConfigs()
    {
        $agent_id = Auth::user()->agent_id;
        if (SystemConfigConfig::whereAgentId($agent_id)->count() > 0) {
            $configs = SystemConfigConfig::whereAgentId($agent_id)->get();
        } else {
            $configs = SystemConfigConfig::whereAgentId(1)->get();
            foreach ($configs as &$conf) {
                $conf->agent_id = $agent_id;
                $conf->save();
            }
        }
        $config = $configs->pluck('value', 'key');
        return self::jsonReturn($config);
    }

    public function agentConfigsUpdate(Request $request)
    {
        $all = $request->all();
        $agent_id = Auth::user()->agent_id;

        foreach ($all as $key => $value) {
            SystemConfigConfig::where(compact('agent_id', 'key'))->update(compact('value'));
        }
        return self::jsonReturn();

    }

}