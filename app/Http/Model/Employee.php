<?php

namespace App\Http\Model;


/**
 * App\Http\Model\Employee
 *
 * @property int $id
 * @property int|null $agent_id 代理商ID
 * @property string|null $employee_name 员工姓名
 * @property string|null $phone 员工电话
 * @property int|null $is_forbid 是否禁止
 * @property string|null $remark 备注
 * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
 * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Model\EmployeeProfitRateConfig[] $percentages
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Employee whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Employee whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Employee whereEmployeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Employee whereIsForbid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Employee wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Employee whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Employee whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class Employee extends Base
{
    protected $table = "a_agent_emp";
    protected $guarded = ['id', 'create_time', 'updated_time'];


    public function user()
    {
        return $this->hasOne('App\User', 'employee_id');
    }

    public function percentages()
    {
        return $this->hasMany('App\Http\Model\EmployeeProfitRateConfig', 'employee_id');
    }
}
