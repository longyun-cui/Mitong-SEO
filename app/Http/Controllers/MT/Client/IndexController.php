<?php
namespace App\Http\Controllers\MT\Client;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\MT\Client\IndexRepository;

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
        return $this->repo->view_client_index();
    }




    /*
     * 业务系统
     */
    // 返回【我的站点】视图
    public function view_my_site_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.client.entrance.business.my-site-list')
                ->with(['sidebar_business_active'=>'active','sidebar_business_my_site_list_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_business_my_site_list_datatable(request()->all());
    }

    // 返回【我的关键词】视图
    public function view_my_keyword_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.client.entrance.business.my-keyword-list')
                ->with(['sidebar_business_active'=>'active','sidebar_business_my_keyword_list_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_business_my_keyword_list_datatable(request()->all());
    }

    // 返回【关键词查询】视图
    public function view_keyword_search()
    {
        return view('mt.client.entrance.business.keyword-search')
            ->with(['sidebar_business_active'=>'active','sidebar_business_keyword_search_active'=>'active']);
    }

    // 返回【财务概览】视图
    public function view_my_keyword_undo_list()
    {
        return view('mt.client.entrance.business.my-keyword-undo-list')
            ->with(['sidebar_business_active'=>'active','sidebar_business_my_keyword_undo_list_active'=>'active']);
    }




    /*
     * 财务系统
     */
    // 返回【财务概览】视图
    public function view_finance_overview()
    {
        return view('mt.client.entrance.finance.overview')
            ->with(['sidebar_finance_active'=>'active','sidebar_finance_overview_active'=>'active']);
    }

    // 返回【财务概览】视图
    public function view_finance_recharge_record()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.client.entrance.finance.recharge-record')
                ->with(['sidebar_finance_active'=>'active','sidebar_finance_recharge_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_finance_recharge_record_datatable(request()->all());
    }

    // 返回【财务概览】视图
    public function view_finance_expense_record()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.client.entrance.finance.expense-record')
                ->with(['sidebar_finance_active'=>'active','sidebar_finance_expense_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_finance_expense_record_datatable(request()->all());
    }





}
