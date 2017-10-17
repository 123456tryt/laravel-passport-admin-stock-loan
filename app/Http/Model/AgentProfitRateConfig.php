<?php

namespace App\Http\Model;


/**
 * App\Http\Model\AgentProfitRateConfig
 *
 * @property int $id 主键【id】
 * @property int|null $agent_id 代理商id
 * @property int|null $type 提成类型 :0（天配）、1（月配）、2（手续费）、3（一级推广佣金）、4（二级推广佣金）、5（三级推广佣金）
 * @property int|null $percentage 提成比例%
 * @property int|null $agent_product_id 代理商产品id(废弃)
 * @property string|null $remark 备注(废弃)
 * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
 * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereAgentProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class AgentProfitRateConfig extends Base
{
    protected $table = "a_agent_percentage_setting";
    const TypeDay = 0;
    const TypeMonth = 1;
    const TypeCommissionOne = 2;
    const TypeCommissionTwo = 3;
    const TypeCommissionThree = 4;
    protected $guarded = ['id', 'create_time', 'updated_time'];


}
