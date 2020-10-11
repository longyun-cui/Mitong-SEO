@extends('mt.agent.layout.layout')

@section('head_title','编辑基本资料 - 代理商系统 - 米同科技')

@section('header','编辑基本资料')
@section('description','搜索引擎智能营销系统')
@section('breadcrumb')
    <li><a href="{{ url('/agent') }}"><i class="fa fa-home"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info form-container">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">基本信息</h3>
                <div class="box-tools pull-right">
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-administrator">
            <div class="box-body">
                {{csrf_field()}}

                {{--用户名--}}
                <div class="form-group">
                    <label class="control-label col-md-2">用户名</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="username" placeholder="请输入用户名" value="{{ $data->username or '' }}">
                    </div>
                </div>

                {{--真实名称--}}
                <div class="form-group">
                    <label class="control-label col-md-2">真实名称</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="truename" placeholder="请输入真实名称" value="{{ $data->truename or '' }}">
                    </div>
                </div>

                {{--手机号--}}
                <div class="form-group">
                    <label class="control-label col-md-2">手机号</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="mobileno" placeholder="请输入手机号" value="{{ $data->mobileno or '' }}">
                    </div>
                </div>

                {{--固定电话--}}
                <div class="form-group">
                    <label class="control-label col-md-2">固定电话</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="telephone" placeholder="请输入固定电话" value="{{ $data->telephone or '' }}">
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

                {{--portrait--}}
                <div class="form-group _none">
                    <label class="control-label col-md-2">头像</label>
                    <div class="col-md-8 fileinput-group">

                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">
                                @if(!empty($data->portrait_img))
                                    <img src="{{ url(env('DOMAIN_CDN').'/'.$data->portrait_img.'?'.rand(0,99)) }}" alt="" />
                                @endif
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail">
                            </div>
                            <div class="btn-tool-group">
                                <span class="btn-file">
                                    <button class="btn btn-sm btn-primary fileinput-new">选择图片</button>
                                    <button class="btn btn-sm btn-warning fileinput-exists">更改</button>
                                    <input type="file" name="portrait_img" />
                                </span>
                                <span class="">
                                    <button class="btn btn-sm btn-danger fileinput-exists" data-dismiss="fileinput">移除</button>
                                </span>
                            </div>
                        </div>
                        <div id="titleImageError" style="color: #a94442"></div>

                    </div>
                </div>

            </div>
            </form>

            <div class="box-footer">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" onclick="" class="btn btn-success" id="edit-info-submit"><i class="fa fa-check"></i>提交</button>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>
@endsection


@section('custom-js')
<script>
    $(function() {
        // 添加or修改产品信息
        $("#edit-info-submit").on('click', function() {
            var options = {
                url: "{{url('/agent/info/edit')}}",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.msg);
                        location.href = "{{url('/agent/info/index')}}";
                    }
                }
            };
            $("#form-edit-administrator").ajaxSubmit(options);
        });
    });
</script>
@endsection