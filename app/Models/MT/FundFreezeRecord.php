<?php
namespace App\Models\MT;
use Illuminate\Database\Eloquent\Model;


class FundFreezeRecord extends Model
{
    //
    protected $table = "funds_freeze_record";

    protected $fillable = [
        'owner_id','createuserid', 'createusername', 'createtime', 'createdate',
        'fundsid', 'siteid', 'keywordid',
        'freezefunds', 'unfreezedate',
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


}
