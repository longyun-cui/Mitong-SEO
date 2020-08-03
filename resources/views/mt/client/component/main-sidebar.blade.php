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
                <p>{{ Auth::guard('admin')->user()->username }}</p>
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

            {{--业务管理--}}
            <li class="header">业务管理</li>
            <!-- Optionally, you can add icons to the links -->

            <li class="treeview {{ $sidebar_business_my_site_list_active or '' }}">
                <a href="{{ url('/client/business/my-site-list') }}">
                    <i class="fa fa-sitemap text-aqua"></i><span>我的站点</span>
                </a>
            </li>

            <li class="treeview {{ $sidebar_business_my_keyword_list_active or '' }}">
                <a href="{{ url('/client/business/my-keyword-list') }}">
                    <i class="fa fa-text-width text-aqua"></i><span>我的关键词</span>
                </a>
            </li>

            <li class="treeview {{ $sidebar_business_keyword_search_active or '' }}">
                <a href="{{ url('/client/business/keyword-search') }}">
                    <i class="fa fa-search text-aqua"></i><span>关键词查询</span>
                </a>
            </li>

            <li class="treeview {{ $sidebar_business_my_keyword_undo_list_active or '' }}">
                <a href="{{ url('/client/business/my-keyword-undo-list') }}">
                    <i class="fa fa-text-width text-aqua"></i><span>待选关键词</span>
                </a>
            </li>




            {{--站点&关键词管理--}}
            <li class="header">财务管理</li>

            <li class="treeview {{ $sidebar_finance_overview_active or '' }}">
                <a href="{{ url('/client/finance/overview') }}">
                    <i class="fa fa-cny text-red"></i><span>财务总览</span>
                </a>
            </li>

            <li class="treeview {{ $sidebar_finance_recharge_active or '' }}">
                <a href="{{ url('/client/finance/recharge-record') }}">
                    <i class="fa fa-cny text-red"></i><span>充值记录</span>
                </a>
            </li>

            <li class="treeview {{ $sidebar_finance_expense_active or '' }}">
                <a href="{{ url('/client/finance/expense-record') }}">
                    <i class="fa fa-cny text-red"></i><span>消费记录</span>
                </a>
            </li>




            {{--站点&关键词管理--}}
            <li class="header">工单管理</li>

            <li class="treeview {{ $sidebar_item_active or '' }}">
                <a href="#">
                    <i class="fa fa-text-width"></i>
                    <span>工单管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ $sidebar_finance_recharge_active or '' }}">
                        <a href="{{ url('/admin/item/list') }}"><i class="fa fa-circle-o"></i> <span>我的工单</span></a>
                    </li>
                </ul>
            </li>




            {{--留言管理--}}
            <li class="header">留言管理</li>

            <li class="treeview">
                <a href="{{ url('/admin/message/list?category=all') }}">
                    <i class="fa fa-envelope"></i> <span>全部留言</span>
                </a>
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