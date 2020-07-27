<?php
namespace App\Models\MT;
use Illuminate\Database\Eloquent\Model;

class SEOSite extends Model
{
    //
    protected $table = "seo_site";
    protected $fillable = [
        'sort', 'type', 'active', 'user_id', 'org_id', 'admin_id', 'menu_id',
        'title', 'subtitle', 'description', 'content', 'cover_pic',
        'visit_num', 'share_num'
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

    // 名下关键词
    function keywords()
    {
        return $this->hasMany('App\Models\MT\SEOKeyword','siteid','id');
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
