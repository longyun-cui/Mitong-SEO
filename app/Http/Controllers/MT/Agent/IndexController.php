<?php
namespace App\Http\Controllers\MT\Agent;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\MT\Agent\IndexRepository;

class IndexController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
        $this->repo = new IndexRepository;
    }


    // 返回主页视图
    public function index()
    {
        return $this->repo->view_agent_index();
    }




    // 返回【代理商列表】视图
    public function view_user_sub_agent_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.agent.entrance.user.sub-agent-list')
                ->with(['sidebar_user_sub_agent_list_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_user_sub_agent_list_datatable(request()->all());
    }

    // 返回【客户列表】视图
    public function view_user_client_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.agent.entrance.user.client-list')
                ->with(['sidebar_user_client_list_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_user_client_list_datatable(request()->all());
    }




    // 新增【代理商】
    public function operate_user_sub_agent_create()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_sub_agent_create();
        else if (request()->isMethod('post')) return $this->repo->operate_user_sub_agent_save(request()->all());
    }

    // 编辑【代理商】
    public function operate_user_sub_agent_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_sub_agent_edit();
        else if (request()->isMethod('post')) return $this->repo->operate_user_sub_agent_save(request()->all());
    }


    // 新增【客户】
    public function operate_user_client_create()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_client_create();
        else if (request()->isMethod('post')) return $this->repo->operate_user_client_save(request()->all());
    }

    // 编辑【客户】
    public function operate_user_client_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_client_edit();
        else if (request()->isMethod('post')) return $this->repo->operate_user_client_save(request()->all());
    }


    // 【二级代理商】充值
    public function operate_user_sub_agent_recharge()
    {
        return $this->repo->operate_user_sub_agent_recharge(request()->all());
    }


    // 【客户】充值
    public function operate_user_client_recharge()
    {
        return $this->repo->operate_user_client_recharge(request()->all());
    }




    // 删除【二级代理商】
    public function operate_user_sub_agent_delete()
    {
        return $this->repo->operate_user_sub_agent_delete(request()->all());
    }

    // 删除【客户】
    public function operate_user_client_delete()
    {
        return $this->repo->operate_user_client_delete(request()->all());
    }




    /*
     * 财务系统
     */
    // 返回【财务概览】视图
    public function view_finance_overview()
    {
        return view('mt.agent.entrance.finance.overview')
            ->with(['sidebar_finance_active'=>'active','sidebar_finance_overview_active'=>'active']);
    }

    // 返回【财务概览】视图
    public function view_finance_recharge_record()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.agent.entrance.finance.recharge-record')
                ->with(['sidebar_finance_active'=>'active','sidebar_finance_recharge_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_finance_recharge_record_datatable(request()->all());
    }

    // 返回【财务概览】视图
    public function view_finance_expense_record()
    {

        if(request()->isMethod('get'))
        {
            return view('mt.agent.entrance.finance.expense-record')
                ->with(['sidebar_finance_active'=>'active','sidebar_finance_expense_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_finance_expense_record_datatable(request()->all());
    }





}
