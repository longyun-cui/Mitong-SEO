@extends('mt.agent.layout.layout')

@section('create-text') 添加二级代理 @endsection
@section('edit-text') 编辑二级代理 @endsection
@section('list-text') 二级代理列表 @endsection

@section('head_title')
    @if($operate == 'create') @yield('create-text') @else @yield('edit-text') @endif - 管理员后台 - 搜索引擎智能营销系统 - 米同科技
@endsection

@section('header')
    @if($operate == 'create') @yield('create-text') @else @yield('edit-text') @endif
@endsection

@section('description', '搜索引擎智能营销系统-米同科技')

@section('breadcrumb')
    <li><a href="{{ url('/agent') }}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li><a href="{{ url('/agent/user/sub-agent-list') }}"><i class="fa "></i> @yield('list-text')</a></li>
    <li><a href="#"><i class="fa "></i> Here</a></li>
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info form-container">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">@if($operate == 'create') @yield('create-text') @else @yield('edit-text') @endif</h3>
                <div class="box-tools pull-right">
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-item">
            <div class="box-body">

                {{csrf_field()}}
                <input type="hidden" name="operate" value="{{ $operate or '' }}" readonly>
                <input type="hidden" name="operate_id" value="{{ $operate_id or 0 }}" readonly>


                {{--类别--}}
                <div class="form-group form-category">
                    <label class="control-label col-md-2">类别</label>
                    <div class="col-md-8">
                        <div class="btn-group">

                            @if($operate == 'create' || ($operate == 'edit' && $data->usergroup == "Agent2"))
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="usergroup" value="Agent2" checked="checked"> 二级代理商
                                        {{--<input type="radio" name="category" value="Agent"--}}
                                               {{--@if($operate == 'edit' && $data->usergroup == "Agent") checked="checked" @endif--}}
                                        {{--> 二级代理商--}}
                                    </label>
                                </div>
                            </button>
                            @endif

                            @if($operate == 'edit' && $data->usergroup == "Service")
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="usergroup" value="Service" checked="checked"> 客户
                                        {{--<input type="radio" name="usergroup" value="Service"--}}
                                           {{--@if($operate == 'edit' && $data->usergroup == "Service") checked="checked" @endif--}}
                                        {{--> 客户--}}
                                    </label>
                                </div>
                            </button>
                            @endif

                        </div>
                    </div>
                </div>


                {{--用户名--}}
                <div class="form-group">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 用户名</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="username" placeholder="用户名" value="{{ $data->username or '' }}">
                    </div>
                </div>
                {{--真实姓名--}}
                <div class="form-group">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 真实姓名</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="truename" placeholder="真实姓名" value="{{ $data->truename or '' }}">
                    </div>
                </div>
                {{--企业全称--}}
                <div class="form-group">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 企业全称</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="epname" placeholder="企业全称" value="{{ $data->epname or '' }}">
                    </div>
                </div>
                {{--手机--}}
                <div class="form-group">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 手机</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="mobileno" placeholder="请输入手机" value="{{ $data->mobileno or '' }}">
                    </div>
                </div>
                {{--邮箱--}}
                <div class="form-group">
                    <label class="control-label col-md-2">邮箱</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="email" placeholder="请输入邮箱" value="{{ $data->email or '' }}">
                    </div>
                </div>
                {{--QQ--}}
                <div class="form-group">
                    <label class="control-label col-md-2">QQ</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="QQnumber" placeholder="请输入QQ" value="{{ $data->QQnumber or '' }}">
                    </div>
                </div>
                {{--微信号--}}
                <div class="form-group">
                    <label class="control-label col-md-2">微信号</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="wechat_id" placeholder="请输入微信号" value="{{ $data->wechat_id or '' }}">
                    </div>
                </div>
                {{--说明--}}
                <div class="form-group _none">
                    <label class="control-label col-md-2">描述</label>
                    <div class="col-md-8 ">
                        {{--<input type="text" class="form-control" name="description" placeholder="描述" value="{{$data->description or ''}}">--}}
                        <textarea class="form-control" name="description" rows="3" cols="100%">{{ $data->description or '' }}</textarea>
                    </div>
                </div>

                {{--链接地址--}}
                <div class="form-group _none">
                    <label class="control-label col-md-2">链接地址</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="link_url" placeholder="链接地址" value="{{ $data->link_url or '' }}">
                    </div>
                </div>

                {{--目录--}}
                <div class="form-group _none">
                    <label class="control-label col-md-2">目录</label>
                    <div class="col-md-8 ">
                        <select class="form-control" onchange="select_menu()">
                            <option data-id="0">未分类</option>
                            {{--@if(!empty($data->menu_id))--}}
                                {{--@foreach($menus as $v)--}}
                                    {{--<option data-id="{{$v->id}}" @if($data->menu_id == $v->id) selected="selected" @endif>{{$v->title}}</option>--}}
                                {{--@endforeach--}}
                            {{--@else--}}
                                {{--@foreach($menus as $v)--}}
                                    {{--<option data-id="{{$v->id}}">{{$v->title}}</option>--}}
                                {{--@endforeach--}}
                            {{--@endif--}}
                        </select>
                        <input type="hidden" value="{{ $data->menu_id or 0 }}" name="menu_id" id="menu-selected">
                    </div>
                </div>
                {{--目录--}}
                <div class="form-group _none">
                    <label class="control-label col-md-2">添加目录</label>
                    <div class="col-md-8 ">
                        <select name="menus[]" id="menus" multiple="multiple" style="width:100%;">
                            {{--<option value="{{$data->people_id or 0}}">{{$data->people->name or '请选择作者'}}</option>--}}
                        </select>
                    </div>
                </div>

                {{--内容--}}
                <div class="form-group _none">
                    <label class="control-label col-md-2">内容详情</label>
                    <div class="col-md-8 ">
                        <div>
                            @include('UEditor::head')
                            <!-- 加载编辑器的容器 -->
                            <script id="container" name="content" type="text/plain">{!! $data->content or '' !!}</script>
                            <!-- 实例化编辑器 -->
                            <script type="text/javascript">
                                var ue = UE.getEditor('container');
                                ue.ready(function() {
                                    ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                                });
                            </script>
                        </div>
                    </div>
                </div>

                {{--多图展示--}}
                <div class="form-group _none">
                    <label class="control-label col-md-2">多图展示</label>
                    <div class="col-md-8 fileinput-group">
                        @if(!empty($data->custom2))
                            @foreach($data->custom2 as $img)
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <img src="{{ url(env('DOMAIN_CDN').'/'.$img->img) }}" alt="" />
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="fileinput-preview fileinput-exists thumbnail"></div>
                        @endif
                    </div>

                    <div class="col-md-8 col-md-offset-2 ">
                        <input id="multiple-images" type="file" class="file-" name="multiple_images[]" multiple >
                    </div>
                </div>

                {{--cover 封面图片--}}
                <div class="form-group _none">
                    <label class="control-label col-md-2">封面图片</label>
                    <div class="col-md-8 fileinput-group">

                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">
                                @if(!empty($data->cover_pic))
                                    <img src="{{ url(env('DOMAIN_CDN').'/'.$data->cover_pic) }}" alt="" />
                                @endif
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail">
                            </div>
                            <div class="btn-tool-group">
                                <span class="btn-file">
                                    <button class="btn btn-sm btn-primary fileinput-new">选择图片</button>
                                    <button class="btn btn-sm btn-warning fileinput-exists">更改</button>
                                    <input type="file" name="cover" />
                                </span>
                                <span class="">
                                    <button class="btn btn-sm btn-danger fileinput-exists" data-dismiss="fileinput">移除</button>
                                </span>
                            </div>
                        </div>
                        <div id="titleImageError" style="color: #a94442"></div>

                    </div>
                </div>

                {{--启用--}}
                @if($operate == 'create')
                    <div class="form-group form-type _none">
                        <label class="control-label col-md-2">启用</label>
                        <div class="col-md-8">
                            <div class="btn-group">

                                <button type="button" class="btn">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="active" value="0" checked="checked"> 暂不启用
                                        </label>
                                    </div>
                                </button>
                                <button type="button" class="btn">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="active" value="1"> 启用
                                        </label>
                                    </div>
                                </button>

                            </div>
                        </div>
                    </div>
                @endif

            </div>
            </form>

            <div class="box-footer">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" class="btn btn-success" id="edit-item-submit"><i class="fa fa-check"></i> 提交</button>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>
@endsection


@section('custom-css')
    <link href="https://cdn.bootcss.com/select2/4.0.5/css/select2.min.css" rel="stylesheet">
@endsection


@section('custom-script')
<script src="https://cdn.bootcss.com/select2/4.0.5/js/select2.min.js"></script>
<script>
    $(function() {

        $("#multiple-images").fileinput({
            allowedFileExtensions : [ 'jpg', 'jpeg', 'png', 'gif' ],
            showUpload: false
        });

        // 添加or编辑
        $("#edit-item-submit").on('click', function() {
            var options = {
                url: "{{ url('/agent/user/sub-agent-edit') }}",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.msg);
                        location.href = "{{ url('/agent/user/sub-agent-list') }}";
                    }
                }
            };
            $("#form-edit-item").ajaxSubmit(options);
        });

        $('#menus').select2({
            ajax: {
                url: "{{url('/admin/item/select2_menus')}}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;
//                    console.log(data);
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            theme: 'classic'
        });

    });
</script>
@endsection
