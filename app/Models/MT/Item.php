<?php
namespace App\Models\MT;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $table = "item";
    protected $fillable = [
        'active', 'category', 'type', 'sort', 'user_id', 'item_id', 'menu_id', 'site_id', 'keyword_id',
        'name', 'title', 'subtitle', 'description', 'content', 'custom', 'custom2', 'custom3',
        'cover_pic', 'link_url', 'attachment_name', 'attachment_src',
        'visit_num', 'share_num'
    ];
    protected $dateFormat = 'U';

//    protected $dates = ['created_at','updated_at'];
//    public function getDates()
//    {
//        return array(); // 原形返回；
//        return array('created_at','updated_at');
//    }

    function user()
    {
        return $this->belongsTo('App\Models\MT\User','user_id','id');
    }

    function site()
    {
        return $this->belongsTo('App\Models\MT\SEOSite','site_id','id');
    }

    // 一对多 关联的目录
    function menu()
    {
        return $this->belongsTo('App\Models\Org\OrgMenu','menu_id','id');
    }

    // 多对多 关联的目录
    function menus()
    {
        return $this->belongsToMany('App\Models\Org\OrgMenu','softorg_org_pivot_menu_item','item_id','menu_id');
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
