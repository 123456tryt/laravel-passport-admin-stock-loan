<?php

namespace App\Http\Model;


/**
 * App\Http\Model\ClientWithdraw
 *
 * @property int $id 主键【id】
 * @property int|null $cust_id 客户id
 * @property float|null $cash_amount 提现金额
 * @property float|null $fee 手续费
 * @property float|null $in_amount 到账金额
 * @property string|null $payment_time 打款时间
 * @property string|null $payment_arrive_time 到账时间
 * @property int|null $cash_status 提现状态:0（待审核），1（待打款），2（审核失败），3（已打款），4（撤销成功）
 * @property int|null $bankcard_id 银行卡ID
 * @property string|null $bank_card 银行卡号
 * @property string|null $remark 后台备注
 * @property \Carbon\Carbon|null $updated_time 审核时间
 * @property \Carbon\Carbon|null $created_time 申请时间
 * @property string|null $cust_remark 废弃
 * @property string|null $apply_time 废弃
 * @property string|null $audit_time 废弃
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereApplyTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereAuditTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereBankCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereBankcardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereCashAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereCashStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereCustId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereCustRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereInAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw wherePaymentArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw wherePaymentTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientWithdraw whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class ClientWithdraw extends Base
{
    protected $table = "u_cust_cash_flow";
    protected $guarded = ['id'];


    /**
     * 代理机构 拥有的登录用户
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function users()
    {
        return $this->hasMany('App\User', 'agent_id', 'id');
    }


}
