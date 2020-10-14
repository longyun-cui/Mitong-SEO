@extends('mt.agent.layout.layout')

@section('head_title')
    {{ Auth::guard('agent')->user()->username }} -  代理商后台 - 搜索引擎智能营销系统 - 米同科技
@endsection

@section('header','代理商系统')
@section('description','搜索引擎智能营销系统 - 米同科技')

@section('breadcrumb')
    <li><a href="{{url('/agent')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
{{--用户余额不足--}}
@if(count($insufficient_clients))
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">

                <div class="box-header with-border" style="margin:16px 0;">
                    <h3 class="box-title">用户余额不足提醒</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                            <i class="fa fa-times"></i></button>
                    </div>
                </div>

                <div class="box-body">
                    @foreach($insufficient_clients as $client)
                        <div>
                            用户 <b class="text-red">{{ $client->username }}</b>  资金余额【<span class="text-red font-20px">{{ $client->fund_balance }}</span>】，余额不足一周消耗，请提醒续费！！
                        </div>
                    @endforeach
                </div>

                <div class="box-footer">
                    Footer
                </div>

            </div>
        </div>
    </div>
@endif


<div class="row">

    <div class="col-md-4">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow">
                <div class="widget-user-image">
                    <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <!-- /.widget-user-image -->
                <h3 class="widget-user-username">{{ Auth::guard('agent')->user()->username }}</h3>
                <h5 class="widget-user-desc">用户ID【{{ Auth::guard('agent')->user()->id }}】</h5>
            </div>
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li>
                        <a href="javascript:void(0);">
                            【全称】{{ Auth::guard('agent')->user()->truename }}
                            <span class="pull-right badge bg-blue _none">31</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            【电话】{{ Auth::guard('agent')->user()->mobileno }}
                            <span class="pull-right badge bg-aqua _none">5</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            【邮箱】{{ Auth::guard('agent')->user()->email }}
                            {{--<span class="pull-right badge bg-aqua">5</span>--}}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            【微信】{{ Auth::guard('agent')->user()->wechatid }}
                            {{--<span class="pull-right badge bg-green">0</span>--}}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            【QQ】{{ Auth::guard('agent')->user()->QQnumber }}
                            {{--<span class="pull-right badge bg-red">0</span>--}}
                        </a>
                    </li>
                    <li class="_none">
                        <a href="javascript:void(0);">Completed Projects <span class="pull-right badge bg-green">12</span></a>
                    </li>
                    <li class="_none">
                        <a href="javascript:void(0);">Followers <span class="pull-right badge bg-red">842</span></a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /.widget-user -->
    </div>


    <div class="col-md-4 _none">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active">
                <h3 class="widget-user-username">{{ Auth::guard('agent')->user()->username }}</h3>
                <h5 class="widget-user-desc">用户ID : {{ Auth::guard('agent')->user()->id }}</h5>
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
        <div class="box box-info">

            <div class="box-header with-border">

                <h3 class="box-title">
                    <span class="text-red">最新公告</span>
                </h3>

                <div class="box-tools pull-right _none">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="box-body no-padding">
                <ul class="nav nav-stacked">
                    @foreach($recently_notices as $notice)
                        <li>
                            <a href="{{ url('/agent/item/item-detail?id='.$notice->id) }}">
                                <b class="">{{ $notice->title or '' }}</b>
                                <span class="pull-right badge bg-blue">{{ $notice->updated_at or '' }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="box-footer">
                <a href="{{ url('/agent/notice/notice-list') }}">更多公告 </a>
            </div>

        </div>
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
                        资金总额 <span class="text-red" style="font-size:24px;">{{ $agent_data->fund_total or '' }}</span> 元
                    </span>

                    <span style="margin-right:12px;" class="_none">
                        支出总额 <span class="text-red font-24px">{{ $agent_data->fund_balance or 0 }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        资金余额 <span class="text-red font-24px">{{ $agent_data->fund_balance or 0 }}</span> 元
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


{{--关键词优化--}}
<div class="row _none">
    <div class="col-md-12">
        <div class="box">
            <div class="callout callout-green">
                <h4>二级代理</h4>
                <div>
                    @if(Auth::guard('agent')->user()->usergroup == "Agent" and Auth::guard('agent')->user()->isopen_subagent == 1)
                    <span style="margin-right:12px;">
                        二级代理 <span class="text-red" style="font-size:24px;">{{ $user_data->sites_count or '' }}</span> 个
                    </span>
                    @endif
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
                <h4>客户概览</h4>
                <div>
                    <span style="margin-right:12px;">
                        客户 <span class="text-red font-24px">{{ $agent_data->clients_count or '' }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        优化关键词数 <span class="text-red" style="font-size:24px;">{{ $agent_data->keywords_count or '' }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        今日上词 <span class="text-red font-24px">{{ $agent_data->keyword_standard_count or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        今日消费 <span class="text-red font-24px">{{ $agent_data->keyword_standard_cost_sum or 0 }}</span> 元
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
