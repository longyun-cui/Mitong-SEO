@extends('front.'.config('common.view.front.template').'.layout.auth')

@section('title','注册')

@section('content')
<div class="register-box">
    <div class="register-logo">
        <a href="/"><b>欢迎来到轻企</b></a>
    </div>

    <div class="register-box-body">
        <p class="login-box-msg">用户注册</p>

        <form action="/user/register" method="post" id="form-user-register">
            {{ csrf_field() }}
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="name" placeholder="用户名">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="email" class="form-control" name="email" placeholder="邮箱">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="密码">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password_confirm" placeholder="确认密码">
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck" name="agree">
                        <label>
                            <input type="checkbox"> 阅读并接受 <a href="#">《用户协议》</a>及<a href="#">《隐私权保护声明》</a>
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="button" class="btn btn-primary btn-block btn-flat" id="user-register-submit">注册</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <div class="social-auth-links text-center" style="display: none">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> 微信登陆</a>
            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> 支付宝登陆</a>
        </div>

        <a href="/user/login" class="text-center">返回登陆</a>
    </div>
    <!-- /.form-box -->
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
        $("#user-register-submit").on('click', function() {
            var options = {
                url: "/user/register",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        $(".form-group input").val("");
                        layer.msg(data.msg);
//                        location.href = "/user/login";
                    }
                }
            };
            $("#form-user-register").ajaxSubmit(options);
        });
    });
</script>
@endsection
