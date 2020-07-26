<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = "users";

    protected $fillable = [
        'sort', 'type', 'mobile', 'email', 'password', 'name', 'nickname',
        'org_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dateFormat = 'U';




    function ext()
    {
        return $this->hasOne('App\UserExt','user_id','id');
    }

    function org()
    {
        return $this->hasOne('App\Org\Organization','user_id','id');
    }


}
