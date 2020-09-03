@extends('mt.client.layout.layout')

@section('head_title','财务概览 - 搜索引擎智能营销系统 - 米同科技')

@section('header','财务概览')
@section('description','搜索引擎智能营销系统-米同科技')

@section('breadcrumb')
    <li><a href="{{url('/client')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
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
