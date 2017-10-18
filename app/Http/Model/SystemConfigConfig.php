<?php

namespace App\Http\Model;

/**
 * App\Http\Model\SystemConfigConfig
 *
 * @property int $id 主键【id】
 * @property int|null $agent_id 代理商ID (agent_id + key)唯一 agent_id=1 代表默认配置
 * @property string|null $key 参数KEY (agent_id + key)唯一
 * @property string|null $value 参数VALUE
 * @property string|null $remark 参数说明
 * @property int|null $param_type 参数类型【多余字段】
 * @property string|null $updated_time
 * @property string|null $created_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\SysConfig whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\SysConfig whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\SysConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\SysConfig whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\SysConfig whereParamType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\SysConfig whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\SysConfig whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\SysConfig whereValue($value)
 * @mixin \Eloquent
 */
class SystemConfigConfig extends Base
{
    protected $table = "s_system_params";

    public $timestamps = false;
    protected $guards = ['id'];

}
