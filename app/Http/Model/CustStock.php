<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class CustStock extends Base
{
    protected $table = "u_cust_stock";

    protected $fillable = ["stock_code", "stock_name", "cust_id"];

}