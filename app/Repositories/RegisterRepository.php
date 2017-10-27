<?php

namespace App\Repositories;

use App\Http\Model\Agent;
use App\Http\Model\AgentEmpPercentageSetting;
use App\Http\Model\MemberAgentRelation;
use App\Http\Model\MemberFeeRate;
use App\Http\Model\RecCode;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Http\Model\AgentPercentageSetting;
use App\Http\Controllers\Api\WechatController;

class RegisterRepository extends Base
{
    const CUSTOMER_TYPE_CODE = 0;   //客户
    const USER_TYPE_CODE = 1;   //员工
    const AGENT_TYPE_CODE = 2;  //代理
    const PERCENTAGE_TIANPEI_TYPE_CODE = 0;   //天配
    const PERCENTAGE_YUEPEI_TYPE_CODE = 1;   //月配
    const PERCENTAGE_FEE_TYPE_CODE = 2;   //手续费
    const PERCENTAGE_LEVEL1_TYPE_CODE = 3;   //一级返佣
    const PERCENTAGE_LEVEL2_TYPE_CODE = 4;   //二级返佣

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
        $recCode = $data["recCode"] ?? "";
        $relationData = $this->setRelation($custId, $recCode);
        $this->setFeeRate($relationData);

        $this->setRecCode($user, $recCode, $relationData["direct_agent_id"]);

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
            "password" => encryptPassword($data["password"]),
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
                    //假设一个用户只有一个上级用户时，这个用户是该用户的一级客户
                    $custList = [$codeRecord->user_id, $relationRecord->cust1];
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

        $defaultAgent = getDefaultAgent();
        array_unshift($agentList, $defaultAgent ? $defaultAgent->id : 0);
        $directAgent = (int)end($agentList);
        $directCust = $custList[0] ?? 0;
        $data = [
            "cust_id" => $custId,
            "direct_cust_id" => $directCust,
            "direct_agent_id" => $directAgent,
            "agent1" => $agentList[0],
            "agent2" => $agentList[1] ?? 0,
            "agent3" => $agentList[2] ?? 0,
            "agent4" => $agentList[3] ?? 0,
            "agent5" => $agentList[4] ?? 0,
            "direct_emp_id" => $emp,
            "belong_to_agent" => $directAgent && $emp ? $directAgent : 0,
            "cust1" => $custList[0] ?? 0,
            "cust2" => $custList[1] ?? 0,
        ];
        MemberAgentRelation::create($data);

        return $data;
    }

    /**
     * @param $relation
     */
    private function setFeeRate($relation)
    {
        $relation = array_merge($relation, ["emp_id" => $relation["direct_emp_id"], "agent1_rate" => 100]);
        $feeRate0 = $feeRate1 = $feeRate2 = $relation;
        $feeRate0["type"] = 0;
        $feeRate1["type"] = 1;
        $feeRate2["type"] = 2;
        //获取多个代理商分成设置记录
        //todo 验证代理是否设置天配、月配、手续费
        $agentPercentageSettings = AgentPercentageSetting::where(function ($query) use ($relation) {
            for ($i = 1; $i < 5; $i++) {
                $field = "agent" . $i;
                if ($relation[$field] < 1) break;

                $query->orWhere(["agent_id" => $field, "type" => self::PERCENTAGE_TIANPEI_TYPE_CODE])->
                orWhere(["agent_id" => $field, "type" => self::PERCENTAGE_YUEPEI_TYPE_CODE])->
                orWhere(["agent_id" => $field, "type" => self::PERCENTAGE_FEE_TYPE_CODE]);
            }
        })->get();
        foreach ($agentPercentageSettings as $v) {
            $type = $v->type;
            $t = &${"feeRate" . $type};

            for ($i = 1; $i < 5; $i++) {
                if ($relation["agent{$i}"] == $v["agent_id"]) $level = $i;
            }

            if (isset($relation["agent" . ($i + 1)]) && $relation["agent" . ($i + 1)]) {
                $t = array_merge($t, [
                    "agent" . ($level + 1) . "_rate" => $v["percentage"],
                ]);
            }

        }

        //如果客户有推荐客户
        if ($relation["cust1"]) {
            $custPercentageSettings = AgentPercentageSetting::where("agent_id", $relation["direct_agent_id"])
                ->where(function ($query) {
                    $query->where("type", self::PERCENTAGE_LEVEL1_TYPE_CODE)
                        ->orWhere("type", self::PERCENTAGE_LEVEL2_TYPE_CODE);
                })->get();

            foreach ($custPercentageSettings as $v) {
                $type = $v->type;
                if ($type == self::PERCENTAGE_LEVEL1_TYPE_CODE && $relation["cust1"]) {
                    $feeRate0["cust1_rate"] = $feeRate1["cust1_rate"] = $feeRate2["cust1_rate"] = $v["percentage"];
                } else if ($type == self::PERCENTAGE_LEVEL2_TYPE_CODE && $relation["cust1"]) {
                    $feeRate0["cust2_rate"] = $feeRate1["cust2_rate"] = $feeRate2["cust2_rate"] = $v["percentage"];
                }
            }
        }

        //如果用户有推荐员工
        if ($relation["direct_emp_id"]) {
            $agentEmpPercentageSettings = AgentEmpPercentageSetting::where("employee_id", $relation["direct_emp_id"])->
            where(function ($query) {
                $query->where("type", self::PERCENTAGE_TIANPEI_TYPE_CODE)->orWhere("type",
                    self::PERCENTAGE_YUEPEI_TYPE_CODE)->orWhere("type", self::PERCENTAGE_FEE_TYPE_CODE);
            })->get();

            foreach ($agentEmpPercentageSettings as $v) {
                $type = $v->type;
                $t = &${"feeRate" . $type};
                $t = array_merge($t, [
                    "emp_rate" => $v["percentage"],
                ]);
            }
        }

        MemberFeeRate::create($feeRate0);
        MemberFeeRate::create($feeRate1);
        MemberFeeRate::create($feeRate2);
    }

    public function getBackPassword($phone, $password)
    {
        $user = User::where("cellphone", $phone)->first();
        if (!$user) return false;

        return $user->update(["password" => encryptPassword($password)]);
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

        $qrCode = self::makeQrcode($code) ?: "";

        if ($ret) {
            $user->update([
                "cust_rec_code" => $code,
                "rec_code" => $recCode,
                "bar_code" => $qrCode,     //TODO:根据直属代理商公众号生成关注二维码
                "pc_adv_url" => FONT_END_URL . "#/register?code={$code}",
                "phone_adv_url" => FONT_END_URL . "#/register?code={$code}",
            ]);
        }
    }

    private function makeQrCode($code)
    {
        $wechat = new WechatController();
        $img = $wechat->makeQrCode($code);
        if (!$img) return false;

        $object = time() . rand(1, 99999) . $code . ".jpg";
        $ret = ossUpload($object, $img, "qrCode");
        return $ret;
    }

}