<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    const CREATED_AT = "created_time";
    const UPDATED_AT = "updated_time";

    protected $table = "u_customer";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nick_name', 'password', 'real_name', 'id_card', 'withdraw_pw', "cellphone", "reg_source", "reg_ip",
        "ip_location", "cust_rec_code", "rec_code", "bar_code", "pc_adv_url", "phone_adv_url"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //自定义passport 登陆用户名 id 可以改成其他字段
    public function findForPassport($username) {
        return $this->where('cellphone', $username)->first();
    }
}
