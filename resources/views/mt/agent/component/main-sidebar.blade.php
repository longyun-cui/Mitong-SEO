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
                <p>{{ Auth::guard('agent')->user()->nickname }}</p>
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

            @if(Auth::guard('agent')->user()->usergroup == "Agent" and Auth::guard('agent')->user()->isopen_subagent == 1)
            <li class="treeview {{ $sidebar_user_sub_agent_list_active or '' }}">
                <a href="{{ url('/agent/user/sub-agent-list') }}">
                    <i class="fa fa-user"></i><span>二级代理列表</span>
                </a>
            </li>
            @endif

            <li class="treeview {{ $sidebar_user_client_list_active or '' }}">
                <a href="{{ url('/agent/user/client-list') }}">
                    <i class="fa fa-user"></i><span>客户列表</span>
                </a>
            </li>




            {{--财务管理--}}
            <li class="header">财务管理</li>

            <li class="treeview {{ $sidebar_finance_overview_active or '' }} _none">
                <a href="{{ url('/agent/finance/overview') }}">
                    <i class="fa fa-cny text-red"></i><span>财务概览</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_finance_recharge_active or '' }}">
                <a href="{{ url('/agent/finance/recharge-record') }}">
                    <i class="fa fa-cny text-red"></i><span>充值记录</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_finance_expense_active or '' }}">
                <a href="{{ url('/agent/finance/expense-record') }}">
                    <i class="fa fa-cny text-red"></i><span>支出记录</span>
                </a>
            </li>




            {{--员工管理--}}
            <li class="header _none">员工管理</li>

            <li class="treeview _none">
                <a href="{{ url('/agent/') }}">
                    <i class="fa fa-text-width"></i><span>员工列表</span>
                </a>
            </li>




            {{--通知&公告管理--}}
            <li class="header _none">通知&公告管理</li>

            <li class="treeview _none">
                <a href="{{ url('/agent/notice/notice-list') }}">
                    <i class="fa fa-envelope"></i> <span>全部通知</span>
                </a>
            </li>

            <li class="treeview _none">
                <a href="{{ url('/agent/notice/my-notice-list') }}">
                    <i class="fa fa-envelope"></i> <span>我的公告</span>
                </a>
            </li>








            {{--流量统计--}}
            <li class="header _none">流量统计</li>

            <li class="treeview _none">
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