<?php

namespace App\Http\Model;


/**
 * App\Http\Model\AgentPeakCash
 *
 * @property int $id 主键【id】
 * @property int|null $agent_id 代理商id【agent_id】
 * @property int|null $parent_agent_id 上级代理商id【parent_agent_id】这个字段是多余的
 * @property string|null $agent_name 代理商名称【agent_name】
 * @property string|null $agent_level 级别【agent_level】
 * @property float|null $max_amount 峰值金额【max_amount】
 * @property float|null $lend_capital_value 借款金额【lend_capital_value】
 * @property int|null $is_settled 是否已经定值【is_settled】
 * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
 * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentPeakCash whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentPeakCash whereAgentLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentPeakCash whereAgentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentPeakCash whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentPeakCash whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentPeakCash whereIsSettled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentPeakCash whereLendCapitalValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentPeakCash whereMaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentPeakCash whereParentAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentPeakCash whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class AgentPeakCash extends Base
{
    protected $table = 'a_agent_performance';


}
