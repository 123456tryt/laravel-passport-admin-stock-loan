<?php

namespace App\Http\Model;


/**
 * App\Http\Model\Agent
 *
 * @property int $id 主键【id】
 * @property int|null $agent_number 代理商编号
 * @property int|null $parent_id 上级代理商id
 * @property int|null $agent_level 代理商等级
 * @property int|null $capital_id 资金池编号
 * @property string|null $agent_name 代理商名称
 * @property string|null $owner_name 负责人人姓名
 * @property string|null $phone 负责人电话
 * @property string|null $bank_name 开户银行
 * @property string|null $bank_account 提现卡号
 * @property int|null $is_independent 是否独立代理商(独立代理商有网站)1 可以配置extra_info
 * @property int|null $is_forbid_cash 是否禁止提现
 * @property int|null $is_locked 是否锁定
 * @property int|null $is_lock_agent_cust 是否锁定代理商客户
 * @property string|null $remark 备注
 * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
 * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereAgentLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereAgentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereAgentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereCapitalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereIsForbidCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereIsIndependent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereIsLockAgentCust($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereIsLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereOwnerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereUpdatedTime($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Model\Employee[] $employees
 */
class Agent extends Base
{
    protected $table = "a_agent";
    protected $guarded = ['id', 'create_time', 'updated_time'];


    /**
     * 代理机构 拥有的登录用户
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function users()
    {
        return $this->hasMany('App\User', 'agent_id', 'id');
    }

    public function employees()
    {

        return $this->hasMany('\App\Http\Model\Employee', 'agent_id', 'id');
    }
}
