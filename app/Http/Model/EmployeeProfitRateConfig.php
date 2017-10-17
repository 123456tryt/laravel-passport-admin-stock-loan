<?php

namespace App\Http\Model;


/**
 * App\Http\Model\EmployeeProfitRateConfig
 *
 * @property int $id 主键【id】
 * @property int|null $employee_id 员工id
 * @property int $agent_id 代理商id
 * @property int|null $type 提成类型 :0（天配）、1（月配）、2（手续费）
 * @property int|null $percentage 提成比例%
 * @property int|null $agent_product_id 代理商产品id(废弃)
 * @property string|null $remark 备注(废弃)
 * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
 * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\EmployeeProfitRateConfig whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\EmployeeProfitRateConfig whereAgentProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\EmployeeProfitRateConfig whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\EmployeeProfitRateConfig whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\EmployeeProfitRateConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\EmployeeProfitRateConfig wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\EmployeeProfitRateConfig whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\EmployeeProfitRateConfig whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\EmployeeProfitRateConfig whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class EmployeeProfitRateConfig extends Base
{
    protected $table = "a_agent_emp_percentage_setting";
    const TypeDay = 0;
    const TypeMonth = 1;
    const TypeCommissionOne = 2;
    const TypeCommissionTwo = 3;
    const TypeCommissionThree = 4;
    protected $guarded = ['id', 'create_time', 'updated_time'];


}
