<?php
namespace App\Repositories\MT\TEST;

use App\Models\MT\User;
use App\Models\MT\ExpenseRecord;
use App\Models\MT\FundRechargeRecord;
use App\Models\MT\FundFreezeRecord;
use App\Models\MT\SEOSite;
use App\Models\MT\SEOCart;
use App\Models\MT\SEOKeyword;
use App\Models\MT\SEOKeywordDetectRecord;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class TestRepository {

    private $model;
    private $repo;
    public function __construct()
    {
        $this->model = new User;
    }


    // 返回（后台）主页视图
    public function index()
    {
    }





}