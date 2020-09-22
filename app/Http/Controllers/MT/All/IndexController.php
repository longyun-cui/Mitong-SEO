<?php
namespace App\Http\Controllers\MT\All;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\MT\User;
use App\Models\MT\ExpenseRecord;
use App\Models\MT\FundRechargeRecord;
use App\Models\MT\SEOKeywordDetectRecord;
use App\Models\MT\SEOSite;
use App\Models\MT\SEOKeyword;

use App\Models\TEST\Temp;

use App\Repositories\MT\All\IndexRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class IndexController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
        $this->repo = new IndexRepository;
    }


    // 返回【主页】视图
    public function download_item_attachment()
    {
        return $this->repo->download_item_attachment(request()->all());
    }





}
