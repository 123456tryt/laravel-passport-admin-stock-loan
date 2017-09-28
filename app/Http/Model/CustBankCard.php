<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class CustBankCard extends Base
{
    protected $table = "u_cust_bankcard";

    protected $fillable = ["cust_id", "bind_status", "bank_card", "open_bank", "open_district", "open_province",
        "bank_name", "card_type", "bank_reg_cellphone", "is_open_netbank", "is_cash_bankcard"];

}