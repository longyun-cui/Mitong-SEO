<?php
namespace App\Models\MT;
use Illuminate\Database\Eloquent\Model;


class FundRechargeRecord extends Model
{
    //
    protected $table = "funds_recharge_record";

    protected $fillable = [
        'owner_id', 'userid', 'usertype', 'createuserid', 'createusername', 'createtime', 'createdate',
        'amount', 'unit',
        'status', 'readpriv', 'writepriv', 'notes', 'reguser', 'regtime', 'moduser', 'modtime',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dateFormat = 'U';




    function user()
    {
        return $this->belongsTo('App\Models\MT\User','userid','id');
    }

    function parent()
    {
        return $this->belongsTo('App\Models\MT\User','puserid','id');
    }

    function creator()
    {
        return $this->belongsTo('App\Models\MT\User','createuserid','id');
    }

    function receiver()
    {
        return $this->belongsTo('App\Models\MT\User','createuserid','id');
    }


}
