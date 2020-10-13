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
     * 用户系统
     */

    // 【修改密码】
    public function operate_user_change_password()
    {
        return $this->repo->operate_user_change_password(request()->all());
    }


    // 返回【代理商列表】视图
    public function view_user_agent_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.admin.entrance.user.agent-list')
                ->with(['sidebar_agent_list_active'=>'active menu-open']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_user_agent_list_datatable(request()->all());
    }

    // 返回【客户列表】视图
    public function view_user_client_list()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_client_list();
        else if(request()->isMethod('post')) return $this->repo->get_user_client_list_datatable(request()->all());
    }




    // 新增【代理商】
    public function operate_user_agent_create()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_agent_create();
        else if (request()->isMethod('post')) return $this->repo->operate_user_agent_save(request()->all());
    }

    // 编辑【代理商】
    public function operate_user_agent_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_agent_edit();
        else if (request()->isMethod('post')) return $this->repo->operate_user_agent_save(request()->all());
    }





    // 返回【代理商详情】
    public function view_user_agent()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_agent(request()->all());
        else if (request()->isMethod('post')) return $this->repo->get_user_client_list_datatable(request()->all());
    }

    // 返回【客户详情】
    public function view_user_client()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_client(request()->all());
        else if (request()->isMethod('post')) return $this->repo->get_user_client_list_datatable(request()->all());
    }


    // 返回【代理商详情】【客户列表】
    public function view_user_agent_client_list()
    {
        if(request()->isMethod('get'))
        {
//            return view('mt.admin.entrance.user.agent-list')->with(['sidebar_agent_list_active'=>'active menu-open']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_user_agent_client_list_datatable(request()->all());
    }

    // 返回【客户详情】【关键词列表】
    public function view_user_client_keyword_list()
    {
        if(request()->isMethod('get'))
        {
//            return view('mt.admin.entrance.user.client-list')->with(['sidebar_client_list_active'=>'active menu-open']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_user_client_keyword_list_datatable(request()->all());
    }




    // 【1级代理商】充值
    public function operate_user_agent_recharge()
    {
        return $this->repo->operate_user_agent_recharge(request()->all());
    }

    // 关闭【代理商】充值限制
    public function operate_user_agent_recharge_limit_close()
    {
        return $this->repo->operate_user_agent_recharge_limit_close(request()->all());
    }

    // 开启【代理商】充值限制
    public function operate_user_agent_recharge_limit_open()
    {
        return $this->repo->operate_user_agent_recharge_limit_open(request()->all());
    }


    // 关闭【代理商】充值限制
    public function operate_user_agent_sub_agent_close()
    {
        return $this->repo->operate_user_agent_sub_agent_close(request()->all());
    }

    // 开启【代理商】充值限制
    public function operate_user_agent_sub_agent_open()
    {
        return $this->repo->operate_user_agent_sub_agent_open(request()->all());
    }




    // 删除【代理商】
    public function operate_user_agent_delete()
    {
        return $this->repo->operate_user_agent_delete(request()->all());
    }

    // 删除【客户】
    public function operate_user_client_delete()
    {
        return $this->repo->operate_user_client_delete(request()->all());
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




    // SELECT2【站点】
    public function operate_business_select2_agent()
    {
        return $this->repo->operate_business_select2_agent(request()->all());
    }




    /*
     * 业务系统
     */

    // 返回【关键词查询】视图
    public function operate_keyword_search()
    {
        if(request()->isMethod('get')) return $this->repo->view_business_keyword_search();
        else if (request()->isMethod('post')) return $this->repo->operate_business_keyword_search(request()->all());
    }

    // 返回【关键词推荐】视图
    public function operate_keyword_recommend()
    {
        return $this->repo->operate_business_keyword_recommend(request()->all());
    }

    // 返回【关键词推荐】视图
    public function operate_keyword_search_export()
    {
        return $this->repo->operate_business_keyword_search_export(request()->all());
    }


    // 返回【站点列表】视图
    public function view_business_site_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.admin.entrance.business.site-list')
                ->with([
                    'sidebar_business_site_active'=>'active',
                    'sidebar_business_site_list_active'=>'active'
                ]);
        }
        else if(request()->isMethod('post')) return $this->repo->get_business_site_list_datatable(request()->all());
    }

    // 返回【待审核站点列表】视图
    public function view_business_site_todo_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.admin.entrance.business.site-todo-list')
                ->with([
                    'sidebar_business_keyword_active'=>'active',
                    'sidebar_business_site_todo_active'=>'active'
                ]);
        }
        else if(request()->isMethod('post')) return $this->repo->get_business_site_todo_list_datatable(request()->all());
    }


    // 返回【关键词列表】视图
    public function view_business_keyword_list()
    {
        if(request()->isMethod('get')) return $this->repo->show_business_keyword_list();
        else if(request()->isMethod('post')) return $this->repo->get_business_keyword_list_datatable(request()->all());
    }

    // 返回【今日关键词列表】视图
    public function view_business_keyword_today_list()
    {
        if(request()->isMethod('get')) return $this->repo->show_business_keyword_today_list();
        else if(request()->isMethod('post')) return $this->repo->get_business_keyword_today_list_datatable(request()->all());
    }

    // 返回【今日关键词列表】视图
    public function view_business_keyword_today_newly_list()
    {
        if(request()->isMethod('get')) return $this->repo->show_business_keyword_today_newly_list();
        else if(request()->isMethod('post')) return $this->repo->get_business_keyword_today_newly_list_datatable(request()->all());
    }

    // 返回【异常关键词列表】视图
    public function view_business_keyword_anomaly_list()
    {
        if(request()->isMethod('get')) return $this->repo->show_business_keyword_anomaly_list();
        else if(request()->isMethod('post')) return $this->repo->get_business_keyword_anomaly_list_datatable(request()->all());
    }

    // 返回【待审核关键词列表】视图
    public function view_business_keyword_todo_list()
    {
        if(request()->isMethod('get'))
        {
            return view('mt.admin.entrance.business.keyword-todo-list')
                ->with([
                    'sidebar_business_keyword_active'=>'active',
                    'sidebar_business_keyword_todo_active'=>'active'
                ]);
        }
        else if(request()->isMethod('post')) return $this->repo->get_business_keyword_todo_list_datatable(request()->all());
    }




    // 返回【关键词检测记录】视图
    public function view_business_keyword_detect_record()
    {
        if(request()->isMethod('get')) return $this->repo->show_business_keyword_detect_record(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_business_keyword_detect_record_datatable(request()->all());
    }

    // 添加【关键词检测记录】
    public function operate_business_keyword_detect_create_rank()
    {
        return $this->repo->operate_business_keyword_detect_create_rank(request()->all());
    }

    // 修改【关键词检测记录】
    public function operate_business_keyword_detect_set_rank()
    {
        return $this->repo->operate_business_keyword_detect_set_rank(request()->all());
    }

    // 批量修改【关键词检测记录】
    public function operate_business_keyword_detect_set_rank_bulk()
    {
        return $this->repo->operate_business_keyword_detect_set_rank_bulk(request()->all());
    }





    // 审核【站点】
    public function operate_business_site_review()
    {
        return $this->repo->operate_business_site_review(request()->all());
    }
    // 批量审核【站点】
    public function operate_business_site_review_bulk()
    {
        return $this->repo->operate_business_site_review_bulk(request()->all());
    }


    // 审核【关键词】
    public function operate_business_keyword_review()
    {
        return $this->repo->operate_business_keyword_review(request()->all());
    }
    // 批量审核【关键词】
    public function operate_business_keyword_review_bulk()
    {
        return $this->repo->operate_business_keyword_review_bulk(request()->all());
    }




    // 删除【待选站点】
    public function operate_business_site_todo_delete()
    {
        return $this->repo->operate_business_site_todo_delete(request()->all());
    }
    // 批量删除【待选站点】
    public function operate_business_site_todo_delete_bulk()
    {
        return $this->repo->operate_business_site_todo_delete_bulk(request()->all());
    }

    // 删除【待选关键词】
    public function operate_business_keyword_todo_delete()
    {
        return $this->repo->operate_business_keyword_todo_delete(request()->all());
    }
    // 批量删除【待选关坚持】
    public function operate_business_keyword_todo_delete_bulk()
    {
        return $this->repo->operate_business_keyword_todo_delete_bulk(request()->all());
    }




    // 删除【站点】
    public function operate_business_site_delete()
    {
        return $this->repo->operate_business_site_delete(request()->all());
    }
    // 删除【关键词】
    public function operate_business_keyword_delete()
    {
        return $this->repo->operate_business_keyword_delete(request()->all());
    }


    // 合作停【站点】
    public function operate_business_site_stop()
    {
        return $this->repo->operate_business_site_stop(request()->all());
    }
    // 再合作【站点】
    public function operate_business_site_start()
    {
        return $this->repo->operate_business_site_start(request()->all());
    }


    // 合作停【关键词】
    public function operate_business_keyword_stop()
    {
        return $this->repo->operate_business_keyword_stop(request()->all());
    }
    // 再合作【关键词】
    public function operate_business_keyword_start()
    {
        return $this->repo->operate_business_keyword_start(request()->all());
    }




    /*
     * 工单管理
     */
    // 新增【站点工单】
    public function operate_business_site_work_order_create()
    {
        if(request()->isMethod('get')) return $this->repo->view_business_site_work_order_create(request()->all());
        else if (request()->isMethod('post')) return $this->repo->operate_business_site_work_order_save(request()->all());
    }
    // 编辑【站点工单】
    public function operate_business_site_work_order_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_business_site_work_order_edit(request()->all());
        else if (request()->isMethod('post')) return $this->repo->operate_business_site_work_order_save(request()->all());
    }

    // 返回【站点工单】视图
    public function view_business_site_work_order_list()
    {
        if(request()->isMethod('get')) return $this->repo->show_business_site_work_order_list(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_business_site_work_order_datatable(request()->all());
    }

    // 返回【工单列表】视图
    public function view_business_work_order_list()
    {
        if(request()->isMethod('get')) return $this->repo->show_business_work_order_list();
        else if(request()->isMethod('post')) return $this->repo->get_business_work_order_list_datatable(request()->all());
    }
    // 返回【工单】详情
    public function operate_business_work_order_get()
    {
        return $this->repo->operate_business_work_order_get(request()->all());
    }
    // 删除【工单】
    public function operate_business_work_order_push()
    {
        return $this->repo->operate_business_work_order_push(request()->all());
    }
    // 删除【工单】
    public function operate_business_work_order_delete()
    {
        return $this->repo->operate_business_work_order_delete(request()->all());
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

    // 返回【财务概览】视图
    public function view_finance_expense_record_daily()
    {

        if(request()->isMethod('get'))
        {
            return view('mt.admin.entrance.finance.expense-record-daily')
                ->with(['sidebar_finance_active'=>'active','sidebar_finance_expense_daily_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_finance_expense_record_daily_datatable(request()->all());
    }

    // 返回【冻结最近】视图
    public function view_finance_freeze_record()
    {
        if(request()->isMethod('get')) return $this->repo->show_finance_freeze_record();
        else if(request()->isMethod('post')) return $this->repo->get_finance_freeze_record_datatable(request()->all());

        if(request()->isMethod('get'))
        {
            return view('mt.admin.entrance.finance.freeze-record')
                ->with(['sidebar_finance_active'=>'active','sidebar_finance_expense_active'=>'active']);
        }
        else if(request()->isMethod('post')) return $this->repo->get_finance_freeze_record_datatable(request()->all());
    }




    public function operate_download_keyword_today()
    {
        $this->repo->operate_download_keyword_today();
    }

    public function operate_download_keyword_detect()
    {
        $this->repo->operate_download_keyword_detect(request()->all());
    }




    /*
     * 公告
     */
    // 新增【公告】
    public function operate_notice_notice_create()
    {
        if(request()->isMethod('get')) return $this->repo->view_notice_notice_create();
        else if (request()->isMethod('post')) return $this->repo->operate_notice_notice_save(request()->all());
    }
    // 编辑【公告】
    public function operate_notice_notice_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_notice_notice_edit(request()->all());
        else if (request()->isMethod('post')) return $this->repo->operate_notice_notice_save(request()->all());
    }


    // 返回【公告列表】视图
    public function view_notice_notice_list()
    {
        if(request()->isMethod('get')) return $this->repo->show_notice_notice_list();
        else if(request()->isMethod('post')) return $this->repo->get_notice_notice_list_datatable(request()->all());
    }

    // 返回【我发布的公告】视图
    public function view_notice_my_notice_list()
    {
        if(request()->isMethod('get')) return $this->repo->show_notice_my_notice_list();
        else if(request()->isMethod('post')) return $this->repo->get_notice_my_notice_list_datatable(request()->all());
    }


    // 返回【工单】详情
    public function operate_notice_notice_get()
    {
        return $this->repo->operate_notice_notice_get(request()->all());
    }
    // 删除【工单】
    public function operate_notice_notice_push()
    {
        return $this->repo->operate_notice_notice_push(request()->all());
    }
    // 删除【工单】
    public function operate_notice_notice_delete()
    {
        return $this->repo->operate_notice_notice_delete(request()->all());
    }


}
