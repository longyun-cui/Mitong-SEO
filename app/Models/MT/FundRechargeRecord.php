<?php
namespace App\Models\MT;
use Illuminate\Database\Eloquent\Model;


class FundRechargeRecord extends Model
{
    //
    protected $table = "funds_recharge_record";

    protected $fillable = [
        'status', 'category', 'sort', 'type', 'mobile', 'email', 'password', 'name', 'true_name', 'nickname',
        'org_id',
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


}
