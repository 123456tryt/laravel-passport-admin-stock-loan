<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Base
{
    protected $table = 'u_cust_cash_flow';

    protected $fillable = ["cust_id", "cash_amount", "apply_time", "cash_status", "cust_remark", "bankcard_id"];

}