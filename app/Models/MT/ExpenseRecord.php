<?php
namespace App\Models\MT;
use Illuminate\Database\Eloquent\Model;


class ExpenseRecord extends Model
{
    //
    protected $table = "expense_record";

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
        return $this->belongsTo('App\Models\MT\User','ownuserid','id');
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
