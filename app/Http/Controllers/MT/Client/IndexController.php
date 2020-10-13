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
     * 用户基本信息
     */

    // 返回【主页】视图
    public function view_info_index()
    {
        return $this->repo->view_info_index();
    }

    // 编辑【代理商】
    public function operate_info_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_info_edit();
        else if (request()->isMethod('post')) return $this->repo->operate_info_save(request()->all());
    }

    // 编辑【代理商】
    public function operate_info_password_reset()
    {
        if(request()->isMethod('get')) return $this->repo->view_info_password_reset();
        else if (request()->isMethod('post')) return $this->repo->operate_info_password_reset_save(request()->all());
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
        if(request()->isMethod('get')) return $this->repo->show_my_business_keyword_list();
        else if(request()->isMethod('post')) return $this->repo->get_business_my_keyword_list_datatable(request()->all());
    }

    // 返回【关键词查询】视图
    public function operate_keyword_search()
    {
        if(request()->isMethod('get')) return $this->repo->view_business_keyword_search();
        else if (request()->isMethod('post')) return $this->repo->operate_business_keyword_search(request()->all());
    }

    // 返回【关键词购物车】视图
    public function view_my_keyword_cart_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.client.entrance.business.my-keyword-cart-list')
                ->with(['sidebar_business_active'=>'active','sidebar_business_my_keyword_cart_list_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_business_my_keyword_cart_list_datatable(request()->all());
    }

    // 返回【关键词检测记录】视图
    public function view_business_keyword_detect_record()
    {
        if(request()->isMethod('get')) return $this->repo->show_business_keyword_detect_record(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_business_keyword_detect_record_datatable(request()->all());
    }




    // 新增【站点】
    public function operate_business_site_create()
    {
        if(request()->isMethod('get')) return $this->repo->view_business_site_create();
        else if (request()->isMethod('post')) return $this->repo->operate_business_site_save(request()->all());
    }

    // 编辑【站点】
    public function operate_business_site_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_business_site_edit();
        else if (request()->isMethod('post')) return $this->repo->operate_business_site_save(request()->all());
    }

    // 删除【代理商】
    public function operate_business_site_delete()
    {
        return $this->repo->operate_business_site_delete(request()->all());
    }




    // 添加【关键词】购物车
    public function operate_keyword_cart_add()
    {
        return $this->repo->operate_keyword_cart_add(request()->all());
    }


    // 删除【关键词】【购物车】
    public function operate_keyword_cart_delete()
    {
        return $this->repo->operate_keyword_cart_delete(request()->all());
    }

    // 批量删除【关键词】【购物车】
    public function operate_keyword_cart_delete_bulk()
    {
        return $this->repo->operate_keyword_cart_delete_bulk(request()->all());
    }


    // 购买【关键词】
    public function operate_keyword_buy()
    {
        return $this->repo->operate_keyword_buy(request()->all());
    }

    // 批量购买【关键词】
    public function operate_keyword_buy_bulk()
    {
        return $this->repo->operate_keyword_buy_bulk(request()->all());
    }

    // SELECT2【站点】
    public function operate_business_select2_sites()
    {
        return $this->repo->operate_business_select2_sites(request()->all());
    }




    /*
     * 工单管理
     */
    // 返回【工单列表】视图
    public function view_business_my_work_order_list()
    {
        if(request()->isMethod('get')) return $this->repo->show_business_my_work_order_list();
        else if(request()->isMethod('post')) return $this->repo->get_business_my_work_order_datatable(request()->all());
    }
    // 返回【工单】详情
    public function operate_business_my_work_order_get()
    {
        return $this->repo->operate_business_my_work_order_get(request()->all());
    }
    // 操作【工单】完成
    public function operate_business_my_work_order_complete()
    {
        return $this->repo->operate_business_my_work_order_complete(request()->all());
    }




    /*
     * 财务系统
     */
    // 返回【财务概览】视图
    public function view_finance_overview()
    {
        if(request()->isMethod('get')) return $this->repo->show_finance_overview();
        else if(request()->isMethod('post')) return $this->repo->get_finance_overview_datatable(request()->all());
    }
    // 返回【财务概览】视图
    public function view_finance_overview_month()
    {
        if(request()->isMethod('get')) return $this->repo->show_finance_overview_month(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_finance_overview_month_datatable(request()->all());
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




    /*
     * 公告
     */
    // 返回【公告列表】视图
    public function view_notice_notice_list()
    {
        if(request()->isMethod('get')) return $this->repo->show_notice_notice_list();
        else if(request()->isMethod('post')) return $this->repo->get_notice_notice_list_datatable(request()->all());
    }


    // 返回【工单】详情
    public function operate_notice_notice_get()
    {
        return $this->repo->operate_notice_notice_get(request()->all());
    }




}
