@extends('mt.agent.layout.layout')

@section('head_title','内容详情 - 代理商系统 - 搜索引擎智能营销系统 - 米同科技')

@section('header','内容详情')
@section('description','搜索引擎智能营销系统-米同科技')
@section('breadcrumb')
    <li><a href="{{ url('/agent') }}"><i class="fa fa-home"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info form-container">

            <div class="box-header with-border" style="margin: 15px 0;">
                <h3 class="box-title">内容详情</h3>
            </div>

            <form class="form-horizontal form-bordered-">
            <div class="box-body">
                {{--标题--}}
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-2">
                        <div><b>{{ $data->title or '' }}</b></div>
                    </div>
                </div>
                {{--副标题--}}
                @if($data->subtitle)
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-2">
                        <div>{{ $data->subtitle or '' }}</div>
                    </div>
                </div>
                @endif
                {{--描述--}}
                @if($data->description)
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-2">
                        <div>{{ $data->description or '' }}</div>
                    </div>
                </div>
                @endif
                {{--内容--}}
                @if($data->content)
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-2">
                        <div>{!! $data->content or '' !!}</div>
                    </div>
                </div>
                @endif
            </div>
            </form>

            <div class="box-footer">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        {{--<a href="{{url('/admin/administrator/edit')}}">--}}
                            {{--<button type="button" onclick="" class="btn btn-success"><i class="fa "></i>编辑信息</button>--}}
                        {{--</a>--}}
                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>
@endsection

@section('style')
    <style>
        .info-img-block {width: 100px;height: 100px;overflow: hidden;}
        .info-img-block img {width: 100%;}
    </style>
@endsection

@section('js')
<script>
    $(function() {
        // with plugin options
        $("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});
    });
</script>
@endsection
