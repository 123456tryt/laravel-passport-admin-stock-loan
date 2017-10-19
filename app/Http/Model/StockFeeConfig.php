<?php

namespace App\Http\Model;

/**
 * App\Http\Model\StockFeeConfig
 *
 * @property int $id 主键【id】
 * @property int|null $agent_id 代理商id【agent_id】
 * @property float|null $buy_commission_rate 买入佣金比例【buy_commission_rate】
 * @property float|null $sell_commission_rate 卖出佣金比例【sell_commission_rate】
 * @property float|null $buy_stampduty_rate 买入印花税比例【buy_stampduty_rate】
 * @property float|null $sell_stampduty_rate 卖出印花税比例【sell_stampduty_rate】
 * @property float|null $buy_transfer_rate 买入过户费比例【buy_transfer_rate】
 * @property float|null $sell_transfer_rate 卖出过户费比例【sell_transfer_rate】
 * @property float|null $buy_witness_rate 买入见证费比例【buy_witness_rate】
 * @property float|null $sell_witness_rate 卖出见证费比例【sell_witness_rate】
 * @property float|null $buy_brokerage_rate 买入经手费比例【buy_ brokerage_rate】
 * @property float|null $sell_brokerage_rate 卖出经手费比例【sell_ brokerage_rate】
 * @property float|null $buy_fee_rate 买入规费比例【buy_fee_rate】
 * @property float|null $sell_fee_rate 卖出规费比例【sell_fee_rate】
 * @property int|null $cust_id 客户id【cust_id】
 * @property \Carbon\Carbon|null $created_time 创建时间【created_time】
 * @property \Carbon\Carbon|null $updated_time 更新时间【updated_time】
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereBuyBrokerageRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereBuyCommissionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereBuyFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereBuyStampdutyRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereBuyTransferRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereBuyWitnessRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereCustId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereSellBrokerageRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereSellCommissionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereSellFeeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereSellStampdutyRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereSellTransferRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereSellWitnessRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Model\StockFeeConfig whereUpdatedTime($value)
 * @mixin \Eloquent
 */
class StockFeeConfig extends Base
{
    protected $table = "s_stock_fees";
    protected $guarded = ['id', 'create_time', 'updated_time'];


}
