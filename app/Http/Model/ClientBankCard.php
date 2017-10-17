<?php

namespace App\Http\Model;

/**
 * App\Http\Model\ClientBankCard
 *
 * @property int $id 主键【id】
 * @property int|null $cust_id 客户id【cust_id】
 * @property int|null $bind_status 绑定状态【bind_status】
 * @property string|null $bank_card 银行卡号【bank_card】
 * @property string|null $open_bank 开户支行【open_bank】
 * @property string|null $open_district 开户行所在区域【open_district】
 * @property string|null $open_province 开户行所在省份【open_ province】
 * @property string|null $bank_name 银行名称【bank_name】
 * @property int|null $card_type 银行卡类型【card_type】
 * @property string|null $bank_reg_cellphone 银行预留手机号【bank_reg_cellphone】
 * @property int|null $is_open_netbank 是否开通网上银行【is_open_netbank】
 * @property int|null $is_cash_bankcard 是否是提现银行卡【is_cash_bankcard】
 * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
 * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereBankCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereBankRegCellphone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereBindStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereCardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereCustId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereIsCashBankcard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereIsOpenNetbank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereOpenBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereOpenDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereOpenProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\ClientBankCard whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class ClientBankCard extends Base
{
    protected $table = "u_cust_bankcard";
    protected $guarded = ['id', 'created_time', 'updated_time'];

}
