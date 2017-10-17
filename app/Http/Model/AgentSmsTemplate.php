<?php

namespace App\Http\Model;


/**
 * App\Http\Model\AgentSmsTemplate
 *
 * @property int $id 主键【id】
 * @property int $agent_id 代理商ID
 * @property string|null $third_party_name 三方短信平台名称
 * @property int|null $msg_template_id 短信模板id
 * @property string|null $msg_template 短信模板
 * @property string|null $msg_account 短信账号
 * @property string|null $msg_password 短信密码
 * @property string|null $msg_sign 短信签名
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentSmsTemplate whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentSmsTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentSmsTemplate whereMsgAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentSmsTemplate whereMsgPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentSmsTemplate whereMsgSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentSmsTemplate whereMsgTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentSmsTemplate whereMsgTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentSmsTemplate whereThirdPartyName($value)
 * @mixin \Eloquent
 */
class AgentSmsTemplate extends Base
{
    protected $table = "a_agent_msg_config";


}
