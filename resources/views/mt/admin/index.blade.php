@extends('mt.admin.layout.layout')

@section('head_title','管理员后台 - 米同科技')

@section('header','管理员后台')
@section('description','搜索引擎智能营销系统')

@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i>首页</a></li>
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


{{--用户信息--}}
<div class="row" style="display:none;">
    <div class="col-md-4">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow">
                <div class="widget-user-image">
                    <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <!-- /.widget-user-image -->
                <h3 class="widget-user-username">Nadia Carmichael</h3>
                <h5 class="widget-user-desc">Lead Developer</h5>
            </div>
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li><a href="#">Projects <span class="pull-right badge bg-blue">31</span></a></li>
                    <li><a href="#">Tasks <span class="pull-right badge bg-aqua">5</span></a></li>
                    <li><a href="#">Completed Projects <span class="pull-right badge bg-green">12</span></a></li>
                    <li><a href="#">Followers <span class="pull-right badge bg-red">842</span></a></li>
                </ul>
            </div>
        </div>
        <!-- /.widget-user -->
    </div>
    <!-- /.col -->
    <div class="col-md-4">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active">
                <h3 class="widget-user-username">Alexander Pierce</h3>
                <h5 class="widget-user-desc">Founder &amp; CEO</h5>
            </div>
            <div class="widget-user-image">
                <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">0</h5>
                            <span class="description-text">SALES</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">0</h5>
                            <span class="description-text">FOLLOWERS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4">
                        <div class="description-block">
                            <h5 class="description-header">0</h5>
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
    <!-- /.col -->
</div>


{{--消费统计--}}
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">消费统计</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="echart-browse" style="width:100%;height:320px;"></div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
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
                        优化关键词 <span class="text-red" style="font-size:24px;">{{ $index_data['keyword_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        检测 <span class="text-red font-24px">{{ $index_data['keyword_detect_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        达标 <span class="text-red font-24px">{{ $index_data['keyword_standard_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        达标消费 <span class="text-red font-24px">{{ $index_data['keyword_standard_cost_sum'] or 0 }}</span> 元
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


{{--用户信息--}}
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="callout callout-green">
                <h4>用户概览</h4>
                <div>
                    <span style="margin-right:12px;">
                        代理商 <span class="text-red" style="font-size:24px;">{{ $index_data['agent_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        1级代理商 <span class="text-red" style="font-size:24px;">{{ $index_data['agent1_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        2级代理商 <span class="text-red font-24px">{{ $index_data['agent2_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        客户 <span class="text-red font-24px">{{ $index_data['client_count'] or 0 }}</span> 个
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


{{--代理商概览--}}
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="callout callout-green">
                <h4>代理商概览</h4>
                <div>
                    <span style="margin-right:12px;">
                        代理商 <span class="text-red" style="font-size:24px;">{{ $index_data['agent_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        资金总额 <span class="text-red font-24px">{{ $index_data['agent_fund_total_sum'] or 0 }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        资金余额 <span class="text-red font-24px">{{ $index_data['agent_fund_balance_sum'] or 0 }}</span> 元
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


{{--客户概览--}}
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="callout callout-green">
                <h4>客户概览</h4>
                <div>
                    <span style="margin-right:12px;">
                        客户 <span class="text-red" style="font-size:24px;">{{ $index_data['client_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        资金总额 <span class="text-red font-24px">{{ $index_data['client_fund_total_sum'] or 0 }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        累计消费 <span class="text-red font-24px">{{ $index_data['client_fund_expense_sum'] or 0 }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        资金余额 <span class="text-red font-24px">{{ $index_data['client_fund_balance_sum'] or 0 }}</span> 元
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Title</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">
        Start creating your amazing application!
    </div>
    <div class="box-footer">
        Footer
    </div>
</div>
@endsection


@section('custom-js')
    <script src="{{ asset('/lib/js/echarts-3.7.2.min.js') }}"></script>
@endsection
@section('custom-script')
<script>
    $(function() {

        var option_browse = {
            title: {
                text: '消费统计'
            },
            tooltip : {
                trigger: 'axis',
                axisPointer: {
                    type: 'line',
                    label: {
                        backgroundColor: '#6a7985'
                    }
                }
            },
            legend: {
                data:['{{ $consumption_data[0]["month"] }}','{{ $consumption_data[1]["month"] }}']
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    axisLabel : { interval:0 },
                    data : [
                        1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31
                        {{--@if(count($data[0]["data"]) > count($data[1]["data"]))--}}
                        {{--@foreach($data[0]["data"] as $v)--}}
                        {{--@if (!$loop->last) '{{ $v->day }}', @else '{{ $v->day }}' @endif--}}
                        {{--@endforeach--}}
                        {{--@else--}}
                        {{--@foreach($data[1]["data"] as $v)--}}
                        {{--@if (!$loop->last) '{{ $v->day }}', @else '{{ $v->day }}' @endif--}}
                        {{--@endforeach--}}
                        {{--@endif--}}
                    ]
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'{{ $consumption_data[0]["month"] }}',
                    type:'line',
                    label: {
                        normal: {
                            show: true,
                            position: 'top'
                        }
                    },
                    itemStyle : { normal: {label : {show: true}}},
                    data:[
                            @foreach($consumption_data[0]["data"] as $v)
                            @if (!$loop->last)
                        { value:'{{ $v->sum }}', name:'{{ $v->day }}' },
                            @else
                        { value:'{{ $v->sum }}', name:'{{ $v->day }}' }
                        @endif
                        @endforeach
                    ]
                },
                {
                    name:'{{ $consumption_data[1]["month"] }}',
                    type:'line',
                    label: {
                        normal: {
                            show: true,
                            position: 'top'
                        }
                    },
                    itemStyle : { normal: {label : {show: true}}},
                    data: [
                            @foreach($consumption_data[1]["data"] as $v)
                            @if (!$loop->last)
                        { value:'{{ $v->sum }}', name:'{{ $v->day }}' },
                            @else
                        { value:'{{ $v->sum }}', name:'{{ $v->day }}' }
                        @endif
                        @endforeach
                    ]
                }
            ]
        };
        var myChart_browse = echarts.init(document.getElementById('echart-browse'));
        myChart_browse.setOption(option_browse);

    });
</script>
<script>
    $(function() {
    });
</script>
@endsection
