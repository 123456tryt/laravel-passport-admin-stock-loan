<?php

namespace App\Http\Model;


/**
 * App\Http\Model\ClientFeeRate
 *
 * @property int $id 主键【id】
 * @property int|null $cust_id 客户id【cust_id】
 * @property int|null $type 提成类型 :0（天配）、1（月配）、2（手续费）
 * @property int|null $agent1 1级代理商id【agent1】
 * @property float|null $agent1_rate 1级代理商分成比例
 * @property int|null $agent2 2级代理商id【agent2】
 * @property float|null $agent2_rate 2级代理商分成比例
 * @property int|null $agent3 3级代理商id【agent3】
 * @property float|null $agent3_rate 3级代理商分成比例
 * @property int|null $agent4 4级代理商id【agent4】
 * @property float|null $agent4_rate 4级代理商分成比例
 * @property int|null $agent5 5级代理商id【agent5】
 * @property float|null $agent5_rate 5级代理商分成比例
 * @property int|null $emp_id a_agent_emp.id
 * @property float|null $emp_rate 员工分成比例
 * @property int|null $cust1 1级客户id【cust1】
 * @property float|null $cust1_rate 1级客户分成比例
 * @property int|null $cust2 2级客户id【cust2】
 * @property float|null $cust2_rate 2级客户分成比例
 * @property int|null $cust3 3级客户id【cust3】
 * @property float|null $cust3_rate 3级客户分成比例
 * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
 * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
 * @property int|null $direct_agent_id 直属代理商id【direct_agent_id】
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereAgent1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereAgent1Rate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereAgent2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereAgent2Rate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereAgent3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereAgent3Rate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereAgent4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereAgent4Rate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereAgent5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereAgent5Rate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereCust1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereCust1Rate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereCust2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereCust2Rate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereCust3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereCust3Rate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereCustId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereDirectAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereEmpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereEmpRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientFeeRate whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class ClientFeeRate extends Base
{
    protected $table = "u_member_fee_rate";
    protected $guarded = ['id', 'create_time', 'updated_time'];


}
