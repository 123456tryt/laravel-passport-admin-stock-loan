<?php

namespace App\Repositories;

use App\Http\Model\Agent;
use App\Http\Model\MemberAgentRelation;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Http\Model\RecCode;
use Illuminate\Support\Facades\DB;

class RegisterRepository extends BaseRepository
{
    const CUSTOMER_TYPE_CODE = 0;   //客户
    const USER_TYPE_CODE = 1;   //员工
    const AGENT_TYPE_CODE = 2;  //代理

    public function model()
    {
        return "App\\User";
    }

    /**
     * 注册
     * @param $data
     * @return bool
     */
    public function register($data)
    {
        $user = $this->make($data);
        if (!$user) return false;

        $custId = $user->id;
        $recCode = $data["recCode"];
        $directAgent = $this->setRelation($custId, $recCode);

        $this->setRecCode($user, $recCode, $directAgent);

        return true;
    }

    /**
     * 写入用户信息
     * @param $data
     * @return mixed
     */
    private function make($data)
    {
        $info = [
            CUSTOMER_USERNAME_FIELD => $data["cellphone"],
            "password" => bcrypt($data["password"]),
            "nick_name" => $data["nick_name"],
            "reg_source" => $data["reg_source"],
            "reg_ip" => $data["reg_ip"],
            "ip_location" => $data["ip_location"],
        ];

        return $this->create($info);
    }

    /**
     * 建立用户关系 TODO:用户利息分成表处理
     * @param $custId
     * @param $recCode
     * @return int
     */
    private function setRelation($custId, $recCode)
    {
        $codeRecord = RecCode::where("rec_code", $recCode)->first();

        $agentList = [];
        $custList = [];
        $directCust = 0;
        $directAgent = 0;
        $emp = 0;

        if ($codeRecord) {
            $userType = $codeRecord->user_type;
            if ($userType == self::CUSTOMER_TYPE_CODE) {
                $relationRecord = MemberAgentRelation::where("cust_id", $codeRecord->user_id)->first();
                if ($relationRecord) {
                    for ($i = 2; $i < 6; $i++) {
                        $t = "agent" . $i;
                        if ($relationRecord->{$t}) {
                            $agentList[] = $relationRecord->{$t};
                        }
                    }
                    //假设一个用户只有一个上级用户时，这个用户是该用户的三级客户
                    $custList = [$relationRecord->cust2, $relationRecord->cust3, $codeRecord->user_id];
                    $emp = $relationRecord->direct_emp_id;
                }
            } else if ($userType == self::USER_TYPE_CODE || $userType == self::AGENT_TYPE_CODE) {
                if ($userType == self::USER_TYPE_CODE) {
                    $emp = $codeRecord->user_id;
                    $empInfo = DB::table("s_system_user")->where("id", $emp)->first();
                    $agentId = $empInfo ? $empInfo->agent_id : 0;
                } else {
                    $agentId = $codeRecord->user_id;
                }

                $agentLevel = 5;
                while ($agentLevel > 2) {
                    $agent = Agent::where("id", $agentId)->where("agent_level", "!=", 1)->first();
                    if ($agent) {
                        array_unshift($agentList, $agent->id);
                        $agentLevel = $agent->agent_level;
                        $agentId = $agent->parent_id;
                    } else {
                        $agentLevel = 1;
                    }
                }
            }
        }

        array_unshift($agentList, $this->getDefaultAgent());
        $directAgent = (int)end($agentList);
        $directCust = (int)end($custList);
        MemberAgentRelation::create([
            "cust_id" => $custId,
            "direct_cust_id" => $directCust,
            "direct_agent_id" => $directAgent,
            "agent1" => $agentList[0],
            "agent2" => $agentList[1] ?? 0,
            "agent3" => $agentList[2] ?? 0,
            "agent4" => $agentList[3] ?? 0,
            "agent5" => $agentList[4] ?? 0,
            "direct_emp_id" => $emp,
            "belong_to_agent" => $directAgent,
            "cust1" => $custList[0] ?? 0,
            "cust2" => $custList[1] ?? 0,
            "cust3" => $custList[2] ?? 0,
        ]);

        return $directAgent;
    }

    /**
     * 设置用户邀请码相关
     * @param $user
     * @param $recCode
     * @param $directAgent
     */
    private function setRecCode($user, $recCode, $directAgent)
    {
        $code = createRecCode();
        $ret = RecCode::create([
            "user_type" => self::CUSTOMER_TYPE_CODE,
            "user_id" => $user->id,
            "rec_code" => $code,
        ]);

        if ($ret) {
            $user->update([
                "cust_rec_code" => $code,
                "rec_code" => $recCode,
                "bar_code" => "",     //TODO:根据直属代理商公众号生成关注二维码
                "pc_adv_url" => "",
                "phone_adv_url" => "",
            ]);
        }
    }

    /**
     * 获取默认的代理id
     * @return mixed
     */
    private function getDefaultAgent()
    {
        $defaultAgent = Agent::where("agent_level", 1)->first();
        return $defaultAgent->id;
    }
}