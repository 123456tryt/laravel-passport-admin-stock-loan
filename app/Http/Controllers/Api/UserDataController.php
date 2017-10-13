<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\SmsRepository;
use Illuminate\Http\Request;
use App\Repositories\UserDataRepository;

/**
 * 用户资料
 * Class UserDataController
 * @package App\Http\Controllers\Api
 */
class UserDataController extends Controller
{
    private $userData = null;
    private $sms = null;

    public function __construct(UserDataRepository $userData, SmsRepository $sms)
    {
        $this->middleware("auth:api");

        $this->userData = $userData;
        $this->sms = $sms;
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo(Request $request)
    {
        $ret = $this->userData->getUserInfo($request->user());
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "查询错误");
    }

    /**
     * 银行卡列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankCards(Request $request)
    {
        $ret = $this->userData->bankCards($request->user());
        return $ret !== flase ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "查询错误");
    }

    /**
     * 获取银行卡详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBankCard(Request $request)
    {
        $ret = $this->userData->GetBankCard($request->user(), $request->get("id"));
        return $ret ? parent::jsonReturn($ret, parent::CODE_SUCCESS, "success") :
            parent::jsonReturn([], parent::CODE_FAIL, "查询错误");
    }

    /**
     * 保存银行卡
     * @param Request $requests
     * TODO:绑定卡数量限制,字段限制,实名认证
     */
    public function storeBankCard(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "bank_card" => "required|between:16,19",
            "bank_name" => "required|between:1,30",
            "bank_reg_cellphone" => ["required", "regex:/^1[0-9]{10}$/"],
        ], [
            "bank_card.required" => "银行卡号不能为空",
            "bank_name.required" => "银行名不能为空",
            "bank_reg_cellphone.required" => "银行预留手机号不能为空",
            "bank_card.between" => "请填写正确的银行卡号",
            "bank_name.between" => "请填写正确的银行名称",
            "bank_reg_cellphone.regex" => "请填写正确的银行预留手机号"
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $ret = $this->userData->storeBankCard($request->user(), $request->all());
        return $ret ? parent::jsonReturn([], parent::CODE_SUCCESS, "添加成功") :
            parent::jsonReturn([], parent::CODE_FAIL, "添加失败");
    }

    /**
     * 修改绑定银行卡
     * @param Request $request
     */
    public function updateBankCard(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "bank_card" => "required|between:16,19",
            "bank_name" => "required|between:1,30",
            "bank_reg_cellphone" => ["required", "regex:/^1[0-9]{10}$/"],
        ], [
            "bank_card.required" => "银行卡号不能为空",
            "bank_name.required" => "银行名不能为空",
            "bank_reg_cellphone.required" => "银行预留手机号不能为空",
            "bank_card.between" => "请填写正确的银行卡号",
            "bank_name.between" => "请填写正确的银行名称",
            "bank_reg_cellphone.regex" => "请填写正确的银行预留手机号"
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $ret = $this->userData->updateBankCard($request->user(), $request->get("id"), $request->all());
        return $ret ? parent::jsonReturn([], parent::CODE_SUCCESS, "修改成功") :
            parent::jsonReturn([], parent::CODE_FAIL, "修改失败");
    }

    /**
     * 删除银行卡
     * @param Request $request
     */
    public function deleteBankCard(Request $request)
    {
        $ret = $this->userData->deleteBankCard($request->user(), $request->id);
        return $ret ? parent::jsonReturn([], parent::CODE_SUCCESS, "删除成功") :
            parent::jsonReturn([], parent::CODE_FAIL, "删除失败");
    }

    /**
     * 修改昵称
     * @param Request $request
     * TODO:昵称过滤
     */
    public function updateNickname(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "nick_name" => "required|between:1,20",
        ], [
            "nick_name.required" => "新昵称不能为空",
            "nick_name.between" => "新昵称格式应该为1-20字符",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $ret = $this->userData->updateNickname($request->user(), $request->get("nick_name"));
        return $ret ? parent::jsonReturn([], parent::CODE_SUCCESS, "修改成功") :
            parent::jsonReturn([], parent::CODE_FAIL, "修改失败");
    }

    /**
     * 实名认证
     * @param Request $request
     * @return mixed
     */
    public function storeCetification(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "real_name" => "required|between:1,20",
            "id_card" => "required|between:15,18"
        ], [
            "real_name.required" => "真实姓名不能为空",
            "id_card.required" => "身份证不能为空",
            "real_name.between" => "请填写正确的真实姓名",
            "id_card.between" => "请填写正确格式的身份证号码"
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $ret = $this->userData->storeCetification($request->user(), $request->get("real_name"), $request->get("id_card"));
        return $ret ? parent::jsonReturn([], parent::CODE_SUCCESS, "提交成功") :
            parent::jsonReturn([], parent::CODE_FAIL, "提交失败");
    }

    /**
     * 设置提款密码
     * @param Request $request
     * @return mixed
     * TODO 支付密码加密
     */
    public function storeWithdrawPassword(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "withdraw_pw" => "required|between:6,20"
        ], [
            "withdraw_pw.required" => "密码不能为空",
            "withdraw_pw.between" => "密码长度应为6-20位"
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $ret = $this->userData->storeWithdrawPassword($request->user(), $request->get("withdraw_pw"));
        return $ret ? parent::jsonReturn([], parent::CODE_SUCCESS, "设置成功") :
            parent::jsonReturn([], parent::CODE_FAIL, "设置失败");
    }

    /**
     * 修改提款密码
     * @param Request $request
     * @return mixed
     */
    public function updateWithdrawPassword(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "old_withdraw_pw" => "required|between:6,20",
            "withdraw_pw" => "required|between:6,20",
        ], [
            "old_withdraw_pw.required" => "旧密码不能为空",
            "withdraw_pw.required" => "新密码不能为空",
            "old_withdraw_pw.between" => "旧密码长度应为6-20位",
            "withdraw_pw.between" => "新密码长度应为6-20位",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        $ret = $this->userData->updateWithdrawPassword($request->user(), $request->get("old_withdraw_pw"),
            $request->get("withdraw_pw"));
        return $ret ? parent::jsonReturn([], parent::CODE_SUCCESS, "修改成功") :
            parent::jsonReturn([], parent::CODE_FAIL, "修改失败");
    }

    public function sendSms(Request $request)
    {
        $user = $request->user();
        $agenInfo = getAgent();
        $ret = $this->sms->sendVerify($user->cellphone, $agenInfo, "手机绑定、登录密码、找回提款密码");
        return $ret ? parent::jsonReturn([], parent::CODE_SUCCESS, "发送成功") :
            parent::jsonReturn([], parent::CODE_FAIL, $this->sms->getErrorMsg() ?: "发送错误");
    }

    public function updatePhone(Request $request)
    {
        $user = $request->user();
        $validator = \Validator::make($request->all(), [
            "cellphone" => ["required", "regex:/^1[0-9]{10}$/", "unique:u_customer,cellphone"],
            "oldPhoneCode" => "required",
        ], [
            "cellphone.required" => "手机号码不能为空",
            "cellphone.regex" => "请填写正确的手机号码",
            "cellphone.unique" => "新手机号码已经被注册",
            "oldPhoneCode.required" => "原手机验证码不能为空",
        ]);

        if ($validator->fails()) {
            return parent::jsonReturn([], parent::CODE_FAIL, $validator->errors()->first());
        }

        if ($user->cellphone == $request->get("cellphone")) {
            return parent::jsonReturn([], parent::CODE_FAIL, "新手机号不能和原手机号一样");
        }

        if (!$this->sms->checkVerify($user->cellphone, $request->get("oldPhoneCode"))) {
            return parent::jsonReturn([], parent::CODE_FAIL, "原手机验证码错误");
        }

        if (!$this->sms->checkVerify($user->cellphone, $request->get("oldPhoneCode"))) {
            return parent::jsonReturn([], parent::CODE_FAIL, "手机验证码错误");
        }

//        $this->userData->updatePhone($user, $request->only([""]));

    }

    public function updatePassword()
    {

    }

    public function getBackWithdrawPassword()
    {

    }

    /**
     * 手机绑定
     */

    /**
     * 登录密码
     */

    /**
     * 忘记提款密码
     */
}