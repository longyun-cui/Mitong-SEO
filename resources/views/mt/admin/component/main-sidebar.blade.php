{{--<!-- Left side column. contains the logo and sidebar -->--}}
<aside class="main-sidebar">

    {{--<!-- sidebar: style can be found in sidebar.less -->--}}
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel _none">
            <div class="pull-left image">
                <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::guard('admin')->user()->nickname }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form _none">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu tree"data-widget="tree">

            {{--用户管理--}}
            <li class="header">用户管理</li>
            <!-- Optionally, you can add icons to the links -->

            <li class="treeview {{ $sidebar_agent_list_active or '' }}">
                <a href="{{ url('/admin/user/agent-list') }}">
                    <i class="fa fa-user"></i><span>代理商列表</span>
                </a>
            </li>

            <li class="treeview {{ $sidebar_client_list_active or '' }}">
                <a href="{{ url('/admin/user/client-list') }}">
                    <i class="fa fa-user"></i><span>客户列表</span>
                </a>
            </li>




            {{--业务管理--}}
            <li class="header">站点管理</li>

            <li class="treeview {{ $sidebar_business_site_list_active or '' }} ">
                <a href="{{ url('/admin/business/site-list') }}">
                    <i class="fa fa-sitemap"></i><span>站点列表</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_business_site_undo_active or '' }}">
                <a href="{{ url('/admin/business/site-undo') }}">
                    <i class="fa fa-sitemap"></i><span>待审核站点</span>
                </a>
            </li>


            {{--关键词管理--}}
            <li class="header">关键词管理</li>

            <li class="treeview {{ $sidebar_business_keyword_list_active or '' }}">
                <a href="{{ url('/admin/business/keyword-list') }}">
                    <i class="fa fa-text-width"></i><span>关键词列表</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_business_keyword_today_active or '' }}">
                <a href="{{ url('/admin/business/keyword-today') }}">
                    <i class="fa fa-text-width"></i><span>今日优化关键词</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_business_keyword_undo_active or '' }}">
                <a href="{{ url('/admin/business/keyword-undo') }}">
                    <i class="fa fa-text-width"></i><span>待审核关键词</span>
                </a>
            </li>




            {{--财务管理--}}
            <li class="header">财务管理</li>

            <li class="treeview {{ $sidebar_finance_overview_active or '' }}">
                <a href="{{ url('/admin/finance/overview') }}">
                    <i class="fa fa-cny text-red"></i><span>财务概览</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_finance_recharge_active or '' }}">
                <a href="{{ url('/admin/finance/recharge-record') }}">
                    <i class="fa fa-cny text-red"></i><span>充值记录</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_finance_expense_active or '' }}">
                <a href="{{ url('/admin/finance/expense-record') }}">
                    <i class="fa fa-cny text-red"></i><span>消费记录</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_finance_expense_daily_active or '' }}">
                <a href="{{ url('/admin/finance/expense-record-daily') }}">
                    <i class="fa fa-cny text-red"></i><span>每日消费</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_finance_freeze_active or '' }}">
                <a href="{{ url('/admin/finance/freeze-record') }}">
                    <i class="fa fa-cny text-red"></i><span>资金冻结记录</span>
                </a>
            </li>




            {{--工单管理--}}
            <li class="header">工单管理</li>

            <li class="treeview {{ $sidebar_work_list_active or '' }}">
                <a href="{{ url('/client/work/work-list') }}">
                    <i class="fa fa-envelope"></i> <span>工单列表</span>
                </a>
            </li>




            {{--留言管理--}}
            <li class="header">公告&通知管理</li>

            <li class="treeview {{ $sidebar_notice_active or '' }}">
                <a href="#">
                    <i class="fa fa-envelope"></i>
                    <span>公告&通知管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ $sidebar_notice_all_active or '' }}">
                        <a href="{{ url('/admin/notice/notice-all') }}"><i class="fa fa-circle-o"></i> <span>全部通知</span></a>
                    </li>
                    <li class="{{ $sidebar_notice_agent_active or '' }}">
                        <a href="{{ url('/admin/notice/notice-agent') }}"><i class="fa fa-circle-o"></i> <span>代理商公告</span></a>
                    </li>
                    <li class="{{ $sidebar_notice_client_active or '' }}">
                        <a href="{{ url('/admin/notice/notice-client') }}"><i class="fa fa-circle-o"></i> <span>客户公告</span></a>
                    </li>
                </ul>
            </li>




            {{--目录管理--}}
            <li class="header _none">自定义内容管理</li>

            <li class="treeview _none">
                <a href="{{ url('/admin/menu/list') }}">
                    <i class="fa fa-folder-open-o text-blue"></i> <span>目录列表</span>
                </a>
            </li>

            <li class="treeview _none">
                <a href="{{ url('/admin/item/list') }}">
                    <i class="fa fa-file-o text-blue"></i> <span>内容列表</span>
                </a>
            </li>


            <li class="treeview _none">
                <a href="{{ url('/admin/menu/sort') }}">
                    <i class="fa fa-sort text-red"></i> <span>目录排序</span>
                </a>
            </li>


            <li class="treeview _none">
                <a href=""><i class="fa fa-th text-aqua"></i> <span>特殊内容</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ url('/admin/product/list') }}">
                            <i class="fa fa-file-text text-red"></i> <span>产品列表</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/article/list') }}">
                            <i class="fa fa-file-text text-red"></i> <span>文章列表</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/activity/list') }}">
                            <i class="fa fa-calendar-check-o text-red"></i> <span>活动/会议列表</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/survey/list') }}">
                            <i class="fa fa-question-circle text-red"></i> <span>调研问卷列表</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/slide/list') }}">
                            <i class="fa fa-th-large text-red"></i> <span>幻灯片列表</span>
                        </a>
                    </li>
                </ul>
            </li>



            {{--流量统计--}}
            <li class="header _none">流量统计</li>

            <li class="treeview _none">
                {{--<a href="{{ url(config('common.org.admin.prefix').'/admin/website/statistics') }}"><i class="fa fa-bar-chart text-green"></i> <span>流量统计</span></a>--}}
                <a href="{{ url('/admin/statistics/website') }}"><i class="fa fa-bar-chart text-green"></i> <span>流量统计</span></a>
            </li>

            <li class="header _none">管理员管理</li>

            <li class="treeview _none" >
                <a href="{{ url('/admin/administrator/password/reset') }}">
                    <i class="fa fa-circle-o text-aqua"></i><span>修改密码</span>
                </a>
            </li>

        </ul>
        <!-- /.sidebar-menu -->
    </section>
    {{--<!-- /.sidebar -->--}}
</aside>