<?php

namespace App\Http\Model;

/**
 * App\Http\Model\ClientFLow
 *
 * @property int $id 主键【id】
 * @property int|null $cust_id 客户id【cust_id】
 * @property int|null $operator_id 0:系统程序,关联到后台登陆用户s_system-user.id
 * @property int|null $flow_type 0：充值\r\n1：提现\r\n2：充值退回\r\n3：配资支出（基本配资和追配）\r\n4：利息支出\r\n5：保证金支出（配资保证金、追配保证金、补充保证金）\r\n6：利润提取\r\n7：配资撤回（结算）\r\n8：推广收益 9:后台调整金额10:后台调调整充值
 * @property string|null $description 记账说明【description】
 * @property float|null $amount_of_account 记账金额【amount_of_account】
 * @property float|null $account_left 账户余额（记账）【account_left】
 * @property string|null $remark 备注
 * @property \Carbon\Carbon|null $occur_time 发生时间【occur_time】
 * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
 * @property int|null $flow_id 这个字段有什么用处?
 * @property int|null $emp_agent_id 员工所属代理商id【emp_agent_id】
 * @property int|null $emp_id 员工id【emp_id】
 * @property int|null $agent1 1级代理商id【agent1】
 * @property int|null $agent2 2级代理商id【agent2】
 * @property int|null $agent3 3级代理商id【agent3】
 * @property int|null $agent4 4级代理商id【agent4】
 * @property int|null $agent5 5级代理商id【agent5】
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereAccountLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereAgent1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereAgent2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereAgent3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereAgent4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereAgent5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereAmountOfAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereCustId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereEmpAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereEmpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereFlowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereFlowType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereOccurTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereOperatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFLow whereRemark($value)
 * @mixin \Eloquent
 */
class ClientFLow extends Base
{
    protected $table = "u_cust_account_flow";
    protected $guarded = ['id'];
    const AdminIncreaseMoney = 10;//后台调整金额
    const AdminDecreaseMoney = 9;//后台调整金额
    const CREATED_AT = "created_time";
    const UPDATED_AT = "occur_time";
}
