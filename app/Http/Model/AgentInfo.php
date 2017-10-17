<?php

namespace App\Http\Model;


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
 * @property string|null $service_time 服务时间
 * @property string|null $service_phone 代理商客服电话
 * @property string|null $service_email 客服邮箱地址
 * @property string|null $service_qq 客服QQ
 * @property string|null $qq_group QQ群
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
 * @property string|null $remark 备注信息
 * @property string|null $sms_account 短信账号
 * @property string|null $sms_pwd 短信密码
 * @property int|null $sms_captcha_template_id 短信验证码模板id
 * @property int|null $sms_sign_id 短信签名id
 * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
 * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereAppid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereCopyright($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereCustQr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereMobileDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo wherePlatformName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo wherePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo wherePublicNumberName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo wherePublicQr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereQqGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereSeoKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereServiceEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereServicePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereServiceQq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereServiceTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereSmsAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereSmsCaptchaTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereSmsPwd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereSmsSignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereUpdatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereWebDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\AgentInfo whereWebsiteRecordNo($value)
 * @mixin \Eloquent
 */
class AgentInfo extends Base
{
    protected $table = "a_agent_extra_info";
    protected $guarded = ['id', 'create_time', 'updated_time'];


}
