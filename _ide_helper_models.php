<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Http\Model {
    /**
     * App\Http\Model\Agent
     *
     * @property int $id 主键【id】
     * @property int|null $parent_id 上级代理商id
     * @property int|null $agent_level 代理商等级
     * @property string|null $name 代理商名称
     * @property string|null $owner_name 联系人姓名
     * @property string|null $owner_phone 联系人电话
     * @property int|null $is_independent 是否独立代理商(独立代理商有网站)1 可以配置extra_info
     * @property int|null $is_forbid_cash 是否禁止提现
     * @property int|null $is_locked 是否锁定
     * @property int|null $is_lock_agent_cust 是否锁定代理商客户
     * @property int|null $percentage_day 天配%
     * @property int|null $percentage_month 月配%
     * @property int|null $percentage_season 季配%
     * @property int|null $percentage_commission 佣金%
     * @property string|null $remark 备注
     * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
     * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereAgentLevel($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereCreatedTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereIsForbidCash($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereIsIndependent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereIsLockAgentCust($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereIsLocked($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereOwnerName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereOwnerPhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereParentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent wherePercentageCommission($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent wherePercentageDay($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent wherePercentageMonth($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent wherePercentageSeason($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereRemark($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Agent whereUpdatedTime($value)
     */
    class Agent extends \Eloquent
    {
    }
}

namespace App\Http\Model {
    /**
     * App\Http\Model\AgentCashFlow
     *
     * @property int $id 主键【id】
     * @property int|null $agent_id 代理商id【agent_id】
     * @property string|null $agent_level 代理商等级【agent_level】
     * @property string|null $agent_name 代理商名称【agent_name】
     * @property float|null $cash_amount 提现金额【cash_amount】
     * @property float|null $fee 手续费【fee】
     * @property float|null $in_amount 到账金额【in_amount】
     * @property string|null $apply_time 申请时间【apply_time】
     * @property string|null $audit_time 审批时间【audit_time】
     * @property string|null $payment_time 打款时间【payment_time】
     * @property string|null $payment_arrive_time 到账时间【payment_arrive_time】
     * @property int|null $cash_status 提现状态【cash_status】
     * @property string|null $remark 备注【remark】
     * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
     * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereAgentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereAgentLevel($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereAgentName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereApplyTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereAuditTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereCashAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereCashStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereCreatedTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereFee($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereInAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow wherePaymentArriveTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow wherePaymentTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereRemark($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentCashFlow whereUpdatedTime($value)
     */
    class AgentCashFlow extends \Eloquent
    {
    }
}

namespace App\Http\Model {
    /**
     * App\Http\Model\AgentInfo
     *
     * @property int $id 主键非自增,关联到a_agent.id一对一关系
     * @property string|null $platform_name 代理平台名称
     * @property string|null $web_domain 代理商网页端域名
     * @property string|null $mobile_domain 代理商手机端域名
     * @property string|null $province 省份
     * @property string|null $city 城市
     * @property string|null $address 详细地址联系地址
     * @property string|null $liaison 联系人
     * @property string|null $serivce_time 服务时间
     * @property string|null $service_phone 代理商客服电话
     * @property string|null $service_email 客服邮箱地址
     * @property string|null $service_qq 客服QQ
     * @property string|null $qq_group QQ群
     * @property string|null $bank_name 银行名称
     * @property string|null $bank_card_no 银行卡号
     * @property string|null $owner_name 户主姓名
     * @property string|null $owner_phone 户主电话
     * @property string|null $website_record_no 网站备案号
     * @property string|null $copyright 版权信息
     * @property string|null $seo_title 首页标题(SEO)
     * @property string|null $seo_description 页面描述(SEO)
     * @property string|null $seo_keyword 页面关健词(SEO)
     * @property string|null $cust_qr 客服微信二维码
     * @property string|null $appid 微信公告号appid
     * @property string|null $public_key 公共号秘钥
     * @property string|null $public_qr 公共微信号二维码
     * @property string|null $public_number_name 微信公共号名称
     * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
     * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereAddress($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereAppid($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereBankCardNo($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereBankName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereCity($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereCopyright($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereCreatedTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereCustQr($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereLiaison($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereMobileDomain($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereOwnerName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereOwnerPhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo wherePlatformName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereProvince($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo wherePublicKey($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo wherePublicNumberName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo wherePublicQr($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereQqGroup($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereSeoDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereSeoKeyword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereSeoTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereSerivceTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereServiceEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereServicePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereServiceQq($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereUpdatedTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereWebDomain($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereWebsiteRecordNo($value)
     */
    class AgentInfo extends \Eloquent
    {
    }
}

namespace App\Http\Model {
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
     */
    class AgentPeakCash extends \Eloquent
    {
    }
}

namespace App\Http\Model {
    /**
     * App\Http\Model\AgentProfitRateConfig
     *
     * @property int $id 主键【id】
     * @property int|null $agent_id 代理商id
     * @property int|null $employee_id 员工为0代表分成比例只对代理有效,u_system_user
     * @property int|null $agent_product_id 代理商产品id
     * @property int|null $percentage_type 提成类型【percentage_type】
     * @property float|null $percentage_rate 提成比例【percentage_rate】
     * @property string|null $remark 备注
     * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
     * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereAgentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereAgentProductId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereCreatedTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereEmployeeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig wherePercentageRate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig wherePercentageType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereRemark($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentProfitRateConfig whereUpdatedTime($value)
     */
    class AgentProfitRateConfig extends \Eloquent
    {
    }
}

namespace App\Http\Model {
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
     */
    class AgentSmsTemplate extends \Eloquent
    {
    }
}

namespace App\Http\Model {
    /**
     * App\Http\Model\Base
     *
     * @mixin \Eloquent
     */
    class Base extends \Eloquent
    {
    }
}

namespace App\Http\Model {
    /**
     * App\Http\Model\Client
     *
     * @property int $id 主键【id】
     * @property string|null $nick_name 昵称【nick_name】
     * @property string|null $cellphone 手机号【cellphone】
     * @property string $password
     * @property string|null $withdraw_pw 提款密码【withdraw_pw】
     * @property string|null $real_name 真实姓名【real_name】
     * @property string|null $id_card 身份证号【id_card】
     * @property string|null $openid openid（微信openid）【openid】
     * @property string|null $cust_rec_code 客户推荐码【cust_rec_code】
     * @property string|null $rec_code 填写的推荐码【rec_code】
     * @property string|null $bar_code 二维码地址【bar_code】
     * @property string|null $pc_adv_url pc推广链接地址【pc_adv_url】
     * @property string|null $phone_adv_url phone推广链接地址【phone_adv_url】
     * @property float|null $cust_capital_amount 账户余额【cust_capital_amount】
     * @property int|null $reg_source 注册来源【reg_source】
     * @property string|null $reg_ip 注册ip地址【reg_ip】
     * @property string|null $ip_location ip所属区域【ip_location】
     * @property int|null $is_login_forbidden 是否禁用登录【is_login_forbidden】
     * @property int|null $is_cash_forbidden 是否禁用账户提现【is_cash_forbidden】
     * @property int|null $is_charge_forbidden 是否禁止充值【is_charge_forbidden】
     * @property int|null $is_stock_finance_forbidden 是否禁止配资【finance_forbidden】
     * @property string|null $created_time 创建时间【created_time】
     * @property string|null $updated_time 更新时间【updated_time】
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereBarCode($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereCellphone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereCreatedTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereCustCapitalAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereCustRecCode($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereIdCard($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereIpLocation($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereIsCashForbidden($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereIsChargeForbidden($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereIsLoginForbidden($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereIsStockFinanceForbidden($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereNickName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereOpenid($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client wherePcAdvUrl($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client wherePhoneAdvUrl($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereRealName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereRecCode($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereRegIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereRegSource($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereUpdatedTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\Client whereWithdrawPw($value)
     */
    class Client extends \Eloquent
    {
    }
}

namespace App\Http\Model {
    /**
     * App\Http\Model\SysConfig
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
     */
    class SysConfig extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\User
     *
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
     * @mixin \Eloquent
     * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
     * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
     * @property int $id 主键 员工ID【id】
     * @property int|null $agent_id 所属代理商ID
     * @property string|null $email 员工邮箱
     * @property string|null $phone 员工手机【emp_cellphone】
     * @property string|null $name 员工姓名【emp_name】
     * @property string|null $password 登录密码【passwod】
     * @property int|null $role_id 关联到角色表s_system_role
     * @property int|null $is_lock 是否锁定【is_lock】
     * @property \Carbon\Carbon|null $created_at
     * @property \Carbon\Carbon|null $updated_at
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAgentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIsLock($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRoleId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
     * @property-read \App\Http\Model\Agent|null $agent
     */
    class User extends \Eloquent
    {
    }
}

