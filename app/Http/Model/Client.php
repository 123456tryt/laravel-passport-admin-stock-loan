<?php

namespace App\Http\Model;

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
 * @mixin \Eloquent
 */
class Client extends Base
{
    protected $table = "u_customer";

    public $timestamps = false;
    protected $guarded = ['id', 'created_time', 'updated_time'];

}
