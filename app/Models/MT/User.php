<?php
namespace App\Models\MT;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = "user";

    protected $fillable = [
        'status', 'category', 'sort', 'type', 'mobile', 'email', 'password', 'name', 'true_name', 'nickname',
        'org_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dateFormat = 'U';




    function ep()
    {
        return $this->belongsTo('App\Models\MT\EPDir','epid','id');
    }



    // 所属代理商
    function parent()
    {
        return $this->belongsTo('App\Models\MT\User','pid','id');
    }

    // 名下代理商
    function agents()
    {
        return $this->hasMany('App\Models\MT\User','pid','id');
    }

    // 名下客户
    function clients()
    {
        return $this->hasMany('App\Models\MT\User','pid','id');
    }




    // 关联资金
    function fund()
    {
        return $this->hasOne('App\Models\MT\Fund','userid','id');
    }




    // 名下站点
    function sites()
    {
        return $this->hasMany('App\Models\MT\SEOSite','createuserid','id');
    }

    // 名下关键词
    function keywords()
    {
        return $this->hasMany('App\Models\MT\SEOKeyword','createuserid','id');
    }


}
