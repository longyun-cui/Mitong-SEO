<?php
namespace App\Http\Controllers\MT\Root;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\MT\User;
use App\Models\MT\ExpenseRecord;
use App\Models\MT\FundRechargeRecord;
use App\Models\MT\FundFreezeRecord;
use App\Models\MT\SEOSite;
use App\Models\MT\SEOCart;
use App\Models\MT\SEOKeyword;
use App\Models\MT\SEOKeywordDetectRecord;

use App\Repositories\MT\TEST\TestRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

//use App\Repositories\MT\Root\IndexRepository;

class IndexController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
//        $this->repo = new IndexRepository;
    }


    // 返回主页视图
    public function index()
    {
        if(Auth::guard('client')->check()) // 未登录
        {
            return redirect('/client/');
        }
        else
        {
            if(Auth::guard('agent')->check()) // 未登录
            {
                return redirect('/agent/');
            }
            else
            {
                if(Auth::guard('admin')->check()) // 未登录
                {
                    return redirect('/admin/');
                }
                else
                {
                    return redirect('/login/');
                }
            }
        }
    }




}
