@extends('mt.admin.layout.layout')

@section('head_title','管理员后台 - 米同科技')

@section('header','管理员后台')
@section('description','搜索引擎智能营销系统')

@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
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
    <!-- /.col -->
</div>


<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="callout callout-green">
                <h4>今日概览</h4>
                <div>
                    <span style="margin-right:12px;">
                        总数 <span class="text-red" style="font-size:24px;">{{ $test_data['keyword_count'] or 0 }}</span> 个
                    </span>
                </div>
                <div>
                    <span style="margin-right:12px;">
                        达标1 <span class="text-red font-24px">{{ $test_data['standarddays_sum'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        累计消费 <span class="text-red font-24px">{{ $test_data['totalconsumption_sum'] or 0 }}</span> 个
                    </span>
                </div>
                <div>
                    <span style="margin-right:12px;">
                        达标2 <span class="text-red font-24px">{{ $test_data['standard_days_sum'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        消费统计 <span class="text-red font-24px">{{ $test_data['standard_days_consumption_sum'] or 0 }}</span> 元
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="callout callout-green">
                <h4>关键词概览</h4>
                <div>
                    <span style="margin-right:12px;">
                        总数 <span class="text-red" style="font-size:24px;">{{ $test_data['keyword_count'] or 0 }}</span> 个
                    </span>
                </div>
                <div>
                    <span style="margin-right:12px;">
                        达标1 <span class="text-red font-24px">{{ $test_data['standarddays_sum'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        累计消费 <span class="text-red font-24px">{{ $test_data['totalconsumption_sum'] or 0 }}</span> 个
                    </span>
                </div>
                <div>
                    <span style="margin-right:12px;">
                        达标2 <span class="text-red font-24px">{{ $test_data['standard_days_sum'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        消费统计 <span class="text-red font-24px">{{ $test_data['standard_days_consumption_sum'] or 0 }}</span> 元
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
