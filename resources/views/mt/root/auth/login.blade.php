@extends('mt.root.layout.auth')

@section('title','用户登陆 - 米同科技')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>{{ config('common.name') }}</b> 搜索引擎智能营销系统</a>
        <a href="/" style="font-size:24px;"><b>{{ config('common.name') }}</b> 米同科技</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">用户登陆</p>

        <form action="/admin/login" method="post" id="form-admin-login">
            {{ csrf_field() }}
            {{--<div class="form-group has-feedback">--}}
                {{--<input type="email" class="form-control" name="email" placeholder="邮箱">--}}
                {{--<span class="glyphicon glyphicon-envelope form-control-feedback"></span>--}}
            {{--</div>--}}
            <div class="form-group has-feedback">
                <input type="user" class="form-control" name="username" placeholder="用户名">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="密码">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember"> 记住我
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="button" class="btn btn-primary btn-block btn-flat" id="admin-login-submit">登陆</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <div class="social-auth-links text-center" style="display: none">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> 微信登陆</a>
            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> 支付宝登陆</a>
        </div>
        <!-- /.social-auth-links -->

        <a href="#">忘记密码</a><br>
        {{--<a href="/admin/register" class="text-center">注册新用户</a>--}}

    </div>
    <!-- /.login-box-body -->
</div>
@endsection


@section('js')
<script>
    $(function() {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        // 提交表单
        $("#admin-login-submit").on('click', function() {
            var options = {
                url: "/login",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.data.usergroup);
                        var $user_group = data.data.usergroup;
                        if($user_group == "Manage")
                        {
                            location.href = "/admin";
                        }
                        else if($user_group == "Agent" || $user_group == "Agent")
                        {
                            location.href = "/agent";
                        }
                        else if($user_group == "Service")
                        {
                            location.href = "/agent";
                        }
                    }
                }
            };
            $("#form-admin-login").ajaxSubmit(options);
        });
    });
</script>
@endsection
