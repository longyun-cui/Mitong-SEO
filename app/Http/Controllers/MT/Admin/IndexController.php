<?php
namespace App\Http\Controllers\MT\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\MT\User;

use App\Repositories\MT\Admin\IndexRepository;

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
    public function index()
    {
        return $this->repo->view_admin_index();
    }


    // 返回主页视图
    public function test()
    {
        $this->repo->test();
    }




    // 返回【代理商列表】视图
    public function view_user_agent_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.admin.entrance.user.agent-list')->with(['sidebar_agent_list_active'=>'active menu-open']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_user_agent_list_datatable(request()->all());
    }

    // 返回【客户列表】视图
    public function view_user_client_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.admin.entrance.user.client-list')->with(['sidebar_client_list_active'=>'active menu-open']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_user_client_list_datatable(request()->all());
    }


    // 登录【代理商】
    public function operate_user_agent_login()
    {
        $agent_id = request()->get('id');
        $agent = User::where('id',$agent_id)->first();
        Auth::guard('agent')->login($agent,true);
        return response_success();
    }

    // 登录【客户】
    public function operate_user_client_login()
    {
        $client_id = request()->get('id');
        $client = User::where('id',$client_id)->first();
        Auth::guard('client')->login($client,true);
        return response_success();
    }




    /*
     * 业务系统
     */
    // 返回【财务概览】视图
    public function view_business_site_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.admin.entrance.business.site-list')
                ->with(['sidebar_business_site_active'=>'active','sidebar_business_site_list_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_business_site_list_datatable(request()->all());
    }

    // 返回【关键词列表】视图
    public function view_business_keyword_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.admin.entrance.business.keyword-list')
                ->with(['sidebar_business_keyword_active'=>'active','sidebar_business_keyword_list_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_business_keyword_list_datatable(request()->all());
    }




    /*
     * 财务系统
     */
    // 返回【财务概览】视图
    public function view_finance_overview()
    {
        return view('mt.admin.entrance.finance.overview')
            ->with(['sidebar_finance_active'=>'active','sidebar_finance_overview_active'=>'active']);
    }

    // 返回【财务概览】视图
    public function view_finance_recharge_record()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.admin.entrance.finance.recharge-record')
                ->with(['sidebar_finance_active'=>'active','sidebar_finance_recharge_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_finance_recharge_record_datatable(request()->all());
    }

    // 返回【财务概览】视图
    public function view_finance_expense_record()
    {

        if(request()->isMethod('get'))
        {
            return view('mt.admin.entrance.finance.expense-record')
                ->with(['sidebar_finance_active'=>'active','sidebar_finance_expense_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_finance_expense_record_datatable(request()->all());
    }





}
