@extends('mt.client.layout.layout')

@section('head_title')
    {{ Auth::guard('client')->user()->username }} - 客户系统 - 搜索引擎智能营销系统
@endsection

@section('header','客户系统')
@section('description','搜索引擎智能营销系统')

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
                <h5 class="widget-user-desc"> &nbsp; </h5>
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
                    @forelse($recently_notices as $notice)
                    <li>
                        <a href="{{ url('/client/item/item-detail?id='.$notice->id) }}">
                            <b class="">{{ $notice->title or '' }}</b>
                            <span class="pull-right badge bg-blue">{{ $notice->updated_at or '' }}</span>
                        </a>
                    </li>
                    @empty
                    <li>
                        <a href="javascript:void(0);">
                            暂无公告
                        </a>
                    </li>
                    @endforelse
                </ul>
            </div>

            <div class="box-footer">
                <a href="{{ url('/client/notice/notice-list') }}">更多公告 </a>
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
                        资金总额 <span class="text-red font-20px">{{ number_format((int)$user_data->fund_total) }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        资金总额 <span class="text-red font-20px">{{ number_format((int)$user_data->fund_total) }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        累计消费 <span class="text-red font-20px">{{ number_format((int)$user_data->fund_expense) }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        资金余额 <span class="text-red font-20px">{{ number_format((int)$user_data->fund_balance) }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        可用金额 <span class="text-red font-20px">{{ number_format((int)$user_data->fund_available) }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        冻结金额 <span class="text-red font-20px">{{ number_format((int)$user_data->fund_frozen) }}</span> 元
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
                        站点数 <span class="text-red font-20px">{{ $user_data->sites_count or '' }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        关键词数 <span class="text-red font-20px">{{ $user_data->keywords_count or '' }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        优化关键词数 <span class="text-red font-20px">{{ $index_data->keyword_count or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        检测关键词数 <span class="text-red font-20px">{{ $index_data->keyword_detect_count or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        今日上词 <span class="text-red font-20px">{{ $index_data->keyword_standard_count or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        上词率 <span class="text-red font-20px">{{ $index_data->keyword_standard_rate or 0 }}</span>
                    </span>

                    <span style="margin-right:12px;">
                        今日消费 <span class="text-red font-20px">{{ $index_data->keyword_standard_cost_sum or 0 }}</span> 元
                    </span>
                </div>
            </div>
        </div>
    </div>
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
