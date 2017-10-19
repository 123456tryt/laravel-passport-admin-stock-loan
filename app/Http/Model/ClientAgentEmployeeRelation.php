<?php

namespace App\Http\Model;


/**
 * App\Http\Model\ClientAgentEmployeeRelation
 *
 * @property int $id 主键【id】
 * @property int|null $cust_id 客户id【cust_id】
 * @property int|null $direct_cust_id 直属客户id【direct_cust_id】
 * @property int|null $direct_agent_id 直属代理商id【direct_agent_id】
 * @property int|null $direct_emp_id 需要修成对应的s_sys_user表
 * @property int|null $belong_to_agent 员工所属代理商id【belong_to_agent】
 * @property int|null $agent1 1级代理商id【agent1】
 * @property int|null $agent2 2级代理商id【agent2】
 * @property int|null $agent3 3级代理商id【agent3】
 * @property int|null $agent4 4级代理商id【agent4】
 * @property int|null $agent5 5级代理商id 数字越大越近
 * @property int|null $cust1 1级客户id数字越小越近
 * @property int|null $cust2 2级客户id【cust2】
 * @property int|null $cust3 3级客户id【cust3】
 * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
 * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereAgent1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereAgent2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereAgent3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereAgent4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereAgent5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereBelongToAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereCust1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereCust2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereCust3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereCustId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereDirectAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereDirectCustId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereDirectEmpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientAgentEmployeeRelation whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class ClientAgentEmployeeRelation extends Base
{
    protected $table = "u_member_agent_relation";
    protected $guarded = ['id', 'create_time', 'updated_time'];


}
