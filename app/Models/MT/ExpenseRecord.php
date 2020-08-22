<?php
namespace App\Models\MT;
use Illuminate\Database\Eloquent\Model;


class ExpenseRecord extends Model
{
    //
    protected $table = "expense_record";

    protected $fillable = [
        'owner_id', 'ownuserid', 'createuserid', 'createusername', 'createtime',
        'detect_id', 'productid', 'siteid', 'sitename', 'website', 'keywordid', 'keyword', 'searchengine', 'price',
        'standarddate', 'durationdays',
        'status', 'readpriv', 'writepriv', 'notes', 'reguser', 'regtime', 'moduser', 'modtime',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dateFormat = 'U';




    function user()
    {
        return $this->belongsTo('App\Models\MT\User','ownuserid','id');
    }

    //
    function detects()
    {
        return $this->hasMany('App\Models\MT\SEOKeywordDetectRecord','expense_id','id');
    }

    function site()
    {
        return $this->belongsTo('App\Models\MT\SEOSite','siteid','id');
    }

    function keyword()
    {
        return $this->belongsTo('App\Models\MT\SEOKeyword','keywordid','id');
    }


}
