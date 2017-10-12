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
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    static function encryptPassword($password)
    {
        return md5(md5(md5(md5($password))));
    }


    protected $table = "s_system_user";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agent_id', 'email', 'phone', 'name', 'password', 'rank', 'is_lock', 'real_name', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];

    //自定义passport 登陆用户名 id 可以改成其他字段
    public function findForPassport($username) {
        return $this->where('phone', $username)->first();
    }

    /**
     * 登陆用的所属的代理机构
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo('App\Http\Model\Agent', 'agent_id', 'id');
    }




}
