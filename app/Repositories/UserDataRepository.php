<?php

namespace App\Repositories;

use App\Http\Model\CustBankCard;

class UserDataRepository extends Base
{
    public function getUserInfo($user)
    {
        $userInfo = [
            "cust_id" => $user->id,
            "nick_name" => $user->nick_name,
            "has_set_withdraw_password" => $user->withdraw_pw ? 1 : 0,
            "real_name" => $user->real_name,
            "cust_rec_code" => $user->cust_rec_code,
            "bar_code" => $user->bar_code,
            "pc_adv_url" => $user->pc_adv_url,
            "phone_adv_url" => $user->phone_adv_url,
            "cust_capital_amount" => $user->cust_capital_amount,
            "is_cash_forbidden" => $user->is_cash_forbidden,
            "is_charge_forbidden" => $user->is_charge_forbidden,
            "is_stock_finance_forbidden" => $user->is_stock_finance_forbidden,
        ];

        //TODO 根据需求新增

        return $userInfo;
    }

    /**
     * 银行卡信息列表
     * @param $user
     * @return mixed
     */
    public function bankCards($user)
    {
        $ret = CustBankCard::where("cust_id", $user->id)
            ->get();
        return $ret ? $ret->toArray() : false;
    }

    /**
     * 获取银行卡详情
     * @param $user
     * @param $id
     * @return mixed
     */
    public function getBankCard($user, $id)
    {
        $ret = CustBankCard::where("cust_id", $user->id)->where("id", $id)->first();
        return $ret ? $ret->toArray() : false;
    }

    /**
     * 创建银行卡
     * @param $user
     * @param $data
     * @return mixed
     */
    public function storeBankCard($user, $data)
    {
        $data["cust_id"] = $user->id;
        return CustBankCard::create($data);
    }

    /**
     * 更新银行卡信息
     * @param $user
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateBankCard($user, $id, $data)
    {
        $cardRecord = CustBankCard::find($id);
        if (!$cardRecord || $cardRecord->cust_id != $user->id) {
            return false;
        }

        return $cardRecord->update($data);
    }

    /**
     * 删除银行卡
     * @param $user
     * @param $id
     * @return bool
     */
    public function deleteBankCard($user, $id)
    {
        $cardRecord = CustBankCard::find($id);
        if (!$cardRecord || $cardRecord->cust_id != $user->id) {
            return false;
        }

        return $cardRecord->delete();
    }

    /**
     * 修改昵称
     * @param $user
     * @param $nickname
     * @return mixed
     */
    public function updateNickname($user, $nickname)
    {
        return $user->update(["nick_name" => $nickname]);
    }

    /**
     * 实名认证
     * @param $user
     * @param $realName
     * @param $idCard
     * @return bool
     */
    public function storeCetification($user, $realName, $idCard)
    {
        if ($user->real_name || $user->id_card) {
            return false;
        }

        return $user->update(["real_name" => $realName, "id_card" => $idCard]);
    }

    /**
     * 设置提款密码
     * @param $user
     * @param $withDrawPassword
     * @return bool
     */
    public function storeWithdrawPassword($user, $withDrawPassword)
    {
        if ($user->withdraw_pw) {
            return false;
        }

        return $user->update(["withdraw_pw" => encryptPassword($withDrawPassword)]);
    }

    /**
     * 更新提款密码
     * @param $user
     * @param $oldWithdrawPassword
     * @param $withDrawPassword
     * @return bool
     */
    public function updateWithdrawPassword($user, $oldWithdrawPassword, $withDrawPassword)
    {
        if ($user->withdraw_pw != $oldWithdrawPassword) {
            return false;
        }

        return $user->update(["withdraw_pw" => encryptPassword($withDrawPassword)]);
    }

    /**
     * 更新手机
     * @param $user
     * @param $newPhone
     * @return mixed
     */
    public function updatePhone($user, $newPhone)
    {
        return $user->update(["cellphone" => $newPhone]);
    }

    /**
     * 更新密码
     * @param $user
     * @param $password
     * @return mixed
     */
    public function updatePassword($user, $password)
    {
        return $user->update(["password" => encryptPassword($password)]);
    }

    /**
     * 找回提款密码
     * @param $user
     * @param $password
     * @return mixed
     */
    public function getBackWithdrawPassword($user, $password)
    {
        return $user->update(["withdraw_pw" => encryptPassword($password)]);
    }
}