<?php
namespace App\Models\MT;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = "user";

    protected $fillable = [
        'active', 'pid', 'epid', 'epname', 'usergroup', 'userno', 'departno', 'roleno',
        'createuserid', 'createusername', 'createtime',
        'username', 'truename', 'email', 'mobileno', 'telephone', 'QQnumber', 'wechat_id', 'contact',
        'userpass', 'password', 'question', 'answer',
        'usertype', 'usertype_desc', 'product', 'product_desc', 'seller_id', 'operation_id', 'customer_id',
        'isopen_oem', 'isopen_subagent', 'is_recharge_limit',
        'regfrom', 'userstatus', 'usersessionid', 'expand_info',
        'fund_total', 'fund_expense', 'fund_expense_2', 'fund_balance', 'fund_available', 'fund_frozen', 'fund_frozen_init',
        'status', 'readpriv', 'writepriv', 'notes', 'reguser', 'regtime', 'moduser', 'modtime',
    ];

    protected $datas = ['deleted_at'];

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
