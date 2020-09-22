<?php
namespace App\Repositories\MT\All;

use App\Models\MT\User;
use App\Models\MT\ExpenseRecord;
use App\Models\MT\FundRechargeRecord;
use App\Models\MT\FundFreezeRecord;
use App\Models\MT\SEOSite;
use App\Models\MT\SEOCart;
use App\Models\MT\SEOKeyword;
use App\Models\MT\SEOKeywordDetectRecord;
use App\Models\MT\Item;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class IndexRepository {

    private $model;
    private $repo;
    public function __construct()
    {
        $this->model = new User;
    }


    // 返回（后台）主页视图
    public function download_item_attachment($post_data)
    {
//        $me = Auth::guard("admin")->user();

//        $attachment_name = $post_data['attachment_name'];
//        $attachment_src = $post_data['attachment_src'];

        $item_id = $post_data['item-id'];
        $item = Item::find($item_id);
        $attachment_src = 'resource/'.$item->attachment_src;

        if(file_exists(storage_path($attachment_src)))
        {
            return response()->download(storage_path($attachment_src), $item->attachment_name);
        }
        else echo "文件不存在！";
    }



}