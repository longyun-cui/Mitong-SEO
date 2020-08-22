<?php
namespace App\Models\MT;
use Illuminate\Database\Eloquent\Model;

class SEOKeyword extends Model
{
    //
    protected $table = "seo_keyword";

    protected $fillable = [
        'owner_id', 'createuserid', 'createusername', 'createtime',
        'taskId', 'updateTime', 'reviewopinion',
        'cartid', 'siteid', 'sitename', 'website', 'keywordid', 'keyword', 'searchengine', 'price', 'unit', 'unit2', 'keywordstatus',
        'keywordstatus', 'standardstatus', 'is_detect', 'detectiondate',
        'standarddays',  'standard_days', 'standard_days_1', 'standard_days_2',
        'initialranking', 'latestranking', 'latestconsumption', 'totalconsumption', 'reviewdate', 'reviewuserid', 'reviewusername',
        'firststandarddate', 'standarddate', 'optimizetime', 'cooperationstopdate', 'freezefunds', 'unfreezedate', 'unfreezetime',
        'status', 'readpriv', 'writepriv', 'notes', 'reguser', 'regtime', 'moduser', 'modtime'
    ];

    protected $dateFormat = 'U';

//    protected $dates = ['created_at','updated_at'];
//    public function getDates()
//    {
//        return array(); // 原形返回；
//        return array('created_at','updated_at');
//    }

    function creator()
    {
        return $this->belongsTo('App\Models\MT\User','createuserid','id');
    }

    function site()
    {
        return $this->belongsTo('App\Models\MT\SEOSite','siteid','id');
    }


    /**
     * 获得此文章的所有评论。
     */
    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'itemable');
    }

    /**
     * 获得此文章的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }
}
