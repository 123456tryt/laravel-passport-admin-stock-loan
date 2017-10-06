<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Http\Model\CustBankCard;

class UserDataRepository extends BaseRepository
{
    public function model()
    {
        return "App\\User";
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

        return $user->update(["withdraw_pw" => $withDrawPassword]);
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

        return $user->update(["withdraw_pw" => $withDrawPassword]);
    }
}