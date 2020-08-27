@extends('mt.client.layout.layout')

@section('head_title')
    {{ Auth::guard('client')->user()->username }} - 客户系统 - 搜索引擎智能营销系统 - 米同科技
@endsection

@section('header','客户系统')
@section('description','搜索引擎智能营销系统-米同科技')

@section('breadcrumb')
    <li><a href="{{url('/client')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4" style="display:none;">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active">
                <h3 class="widget-user-username">{{ Auth::guard('client')->user()->username }}</h3>
                <h5 class="widget-user-desc">Founder &amp; CEO</h5>
            </div>
            <div class="widget-user-image">
                <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">3,200</h5>
                            <span class="description-text">SALES</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">13,000</h5>
                            <span class="description-text">FOLLOWERS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4">
                        <div class="description-block">
                            <h5 class="description-header">35</h5>
                            <span class="description-text">PRODUCTS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /.widget-user -->
    </div>
    <div class="col-md-4">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow">
                <div class="widget-user-image">
                    <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <!-- /.widget-user-image -->
                <h3 class="widget-user-username">{{ Auth::guard('client')->user()->parent->username }}</h3>
                <h5 class="widget-user-desc">代理商</h5>
            </div>
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li>
                        <a href="javascript:void(0);">
                            【电话】{{ Auth::guard('client')->user()->parent->mobileno }}
                            {{--<span class="pull-right badge bg-blue">0</span>--}}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            【邮箱】{{ Auth::guard('client')->user()->parent->email }}
                            {{--<span class="pull-right badge bg-aqua">5</span>--}}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            【微信】{{ Auth::guard('client')->user()->parent->wechatid }}
                            {{--<span class="pull-right badge bg-green">0</span>--}}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            【QQ】{{ Auth::guard('client')->user()->parent->QQnumber }}
                            {{--<span class="pull-right badge bg-red">0</span>--}}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /.widget-user -->
    </div>
</div>



{{--财务概览--}}
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="callout callout-green">
                <h4>财务概览</h4>
                <div>
                    <span style="margin-right:12px;">
                        资金总额 <span class="text-red" style="font-size:24px;">{{ $user_data->fund_total or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        资金总额 <span class="text-red font-24px">{{ $user_data->fund_total or 0 }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        累计消费 <span class="text-red font-24px">{{ $user_data->fund_expense or 0 }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        资金余额 <span class="text-red font-24px">{{ $user_data->fund_balance or 0 }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        可用金额 <span class="text-red font-24px">{{ $user_data->fund_available or 0 }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        冻结金额 <span class="text-red font-24px">{{ $user_data->fund_frozen or 0 }}</span> 元
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


{{--关键词优化--}}
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="callout callout-green">
                <h4>关键词优化</h4>
                <div>
                    <span style="margin-right:12px;">
                        站点数 <span class="text-red" style="font-size:24px;">{{ $user_data->sites_count or '' }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        关键词数 <span class="text-red font-24px">{{ $user_data->keywords_count or '' }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        优化关键词数 <span class="text-red" style="font-size:24px;">{{ $user_data->keywords_count or '' }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        关键词数 <span class="text-red font-24px">{{ $user_data->keywords_count or '' }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        今日上词 <span class="text-red font-24px">{{ $user_data->keywords_count or '' }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        今日消费 <span class="text-red font-24px">{{ $user_data->keywords_count or '' }}</span> 元
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('custom-script')
<script>
    $(function() {
    });
</script>
@endsection
