<?php

namespace App\Http\Model;


/**
 * App\Http\Model\AgentCashFlow
 *
 * @property int $id 主键【id】
 * @property int|null $agent_id 代理商id【agent_id】
 * @property string|null $agent_level 代理商等级【agent_level】
 * @property string|null $agent_name 代理商名称【agent_name】
 * @property float|null $cash_amount 提现金额【cash_amount】
 * @property float|null $fee 手续费【fee】
 * @property float|null $in_amount 到账金额【in_amount】
 * @property string|null $apply_time 申请时间【apply_time】
 * @property string|null $audit_time 审批时间【audit_time】
 * @property string|null $payment_time 打款时间【payment_time】
 * @property string|null $payment_arrive_time 到账时间【payment_arrive_time】
 * @property int|null $cash_status 提现状态【cash_status】
 * @property string|null $remark 备注【remark】
 * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
 * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereAgentLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereAgentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereApplyTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereAuditTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereCashAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereCashStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereInAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow wherePaymentArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow wherePaymentTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class AgentCashFlow extends Base
{
    protected $table = "a_agent_cash_flow";


}
