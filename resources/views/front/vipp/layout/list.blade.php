<!DOCTYPE html>
<!--[if IE 9]>
<html class="no-js lt-ie10">
<![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" lang="en-us" dir="ltr">

<head profile="http://www.w3.org/1999/xhtml/vocab">
    <title>@yield('title')</title>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="" type="" />
    <meta name="author" content="@yield('meta_author')" />
    <meta name="description" content="@yield('meta_description')" />
    <meta name="keywords" content="@yield('meta_keywords')" />
    <link rel="canonical" href="https://www.softorg.cn" />
    <link rel="shortlink" href="https://www.softorg.cn" />
    <meta property="og:site_name" content="softorg.cn" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="https://www.softorg.cn" />
    <meta property="og:title" content="Official Softorg Online Website" />
    <meta property="og:updated_time" content="" />
    <meta property="article:published_time" content="" />
    <meta property="article:modified_time" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="google" content="notranslate" />
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="icon" sizes="16x16 32x32 64x64" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="196x196" href="/favicon-192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16.png">
    <link rel="icon" sizes="any" mask href="/favicon-192.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon-32.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon-32.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicon-32.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon-32.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicon-32.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon-32.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicon-32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon-32.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="/sites/all/themes/vipp/assets/img/favicons/mstile-150x150.png">
    <meta name="msapplication-config" content="/sites/all/themes/vipp/assets/img/favicons/browserconfig.xml">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{ asset('frontend/themes/vipp/css/all.css') }}" media="all" />
    {{--<script src="https://www.vipp.com/sites/default/files/js/js_SLyXq4zcOYrRlJ8NMZcdVCadUvi6vXyeJgA1IkziDwE.js.pagespeed.jm.KiaDCMyCJY.js"></script>--}}
    {{--<script src="https://www.vipp.com/sites/all,_themes,_vipp,_assets,_js,_plugins,_modernizr.custom.js,qoys8tt+default,_files,_js,_js_gPqjYq7fqdMzw8-29XWQIVoDSWTmZCGy9OqaHppNxuQ.js.pagespeed.jc.E10rRAYkAy.js"></script>--}}

</head>

<body class="html front not-logged-in one-sidebar sidebar-first page-node page-node- page-node-1864 node-type-front-page i18n-en-us has-cookie front-page">
<script>
    var active_country = "US";
</script>
{{--隐藏的头部目录--}}
<div class="top-wrapper">
    <div class="top extra menu sticky">
        <div class="wrap">
            <div class="settings">
            </div>
            <div class="right">
                @if( count($org->menus) != 0 )
                    @foreach($org->menus as $menu)
                        <a class="big-txt" href="{{url('/menu?id='.encode($menu->id))}}">{{ $menu->title }}</a>
                    @endforeach
                @endif
                <a href="#" class="hidden-sm">关于我们</a>
                <a href="#" class="btn-icon-close"><i class="icon-close"></i></a>
            </div>
        </div>
    </div>
</div>
{{--头部--}}
<div class="top-wrapper">
    <div class="top primary menu sticky">
        <div class="wrap">
            <a fade-onload href="/org/@yield('website-name')" title="Home" rel="home" id="logo">
                <img class="logo logo-black" src="http://cdn.{{$_SERVER['HTTP_HOST']}}/{{$org->logo or ''}}" alt="{{$org->short or 'Home'}}"/>
                <img class="logo logo-white" src="http://cdn.{{$_SERVER['HTTP_HOST']}}/{{$org->logo or ''}}" alt="{{$org->short or 'Home'}}"/>
            </a>
            <div class="right" fade-onload>
                <a class="hidden-sm text-item" href="/org/@yield('website-name')">首页</a>
                <a class="hidden-sm text-item" href="/org/@yield('website-name')/product">产品</a>
                <a class="hidden-sm text-item" href="/org/@yield('website-name')/article">文章</a>
                <a class="hidden-sm text-item" href="/org/@yield('website-name')/activity">活动</a>
                <a class="hidden-sm text-item" href="/org/@yield('website-name')/survey">问卷</a>
                <a class="btn-menu-burger" href="#">
                    <img class="icon-menu icon-menu--white" src="{{asset('/frontend/themes/vipp/assets/img/icon-menu-white@2x.svg')}}" alt="目录">
                    <img class="icon-menu icon-menu--black" src="{{asset('/frontend/themes/vipp/assets/img/icon-menu@2x.svg')}}" alt="目录">
                    <i class="icon-close"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="tray-menu--mask"></div>

{{--侧边栏--}}
<div class="tray-menu">
    <ul class="main menu-level menu-current menu-in">
        <li>
            <div>
                <img class="logo" src="http://cdn.{{$_SERVER['HTTP_HOST']}}/{{$org->logo or ''}}" alt="{{$org->short or 'Home'}}">
            </div>
            <div style="display:none;">
                <img class="logo" src="http://cdn.{{$_SERVER['HTTP_HOST']}}/{{$org->logo or ''}}" alt="{{$org->short or 'Home'}}">
            </div>
        </li>
        <li>
            <ul class="first-nav">
                @if( count($org->menus) != 0 )
                    @foreach($org->menus as $menu)
                        <li><a class="big-txt" href="{{url('/menu?id='.encode($menu->id))}}">
                                <i class="fa fa-dot-circle-o" style="font-size:8px;margin-right:8px;"></i> {{ $menu->title }}</a></li>
                        <li class="padder">&nbsp;</li>
                    @endforeach
                @endif
            </ul>
        </li>
        <li class="padder">&nbsp;</li>
        <li>
            <ul class="second-nav">
                <li><a class="" href="/org/@yield('website-name')">首页</a></li>
                <li><a class="" href="/org/@yield('website-name')/product">产品</a></li>
                <li><a class="" href="/org/@yield('website-name')/article">文章</a></li>
                <li><a class="" href="/org/@yield('website-name')/activity">活动</a></li>
                <li><a class="" href="/org/@yield('website-name')/survey">问卷</a></li>
                <li><a href="#" class="">关于我们</a></li>
            </ul>
        </li>
        <li class="padder">&nbsp;</li>
    </ul>
</div>

{{--main--}}
<div class="wrapper-main-content style-main style-{{ $org->style or '0' }}">
    <div class="container-fluid ">
        {{--首页--}}
        <div class="row full has-fold" style="margin-bottom:48px;">
            <div class="col-xs-14">
                <div class="hero-product-container">
                    <div class="hero-product-container-xs">
                    </div>
                    <div class="hero-product-description white" fade-onload>
                        <h4>{{$org->slogan or ''}}</h4>
                        <h1 class="hero-heading">{{$org->name or ''}}</h1>
                        <a href="javascript:void(0);" class="btn-md btn-md-l"><span>@yield('menu-name')</span></a>
                    </div>
                </div>
            </div>
        </div>
        @yield('content')
    </div>
</div>

<div class="overlay"></div>
<div class="overlay"></div>
{{--footer--}}
<div class="footer">
    <div class="bt-scroll-top"><i class="icon-arrow-down"></i> </div>
    <div class="social-links" style="display: none">
        <a href="https://www.instagram.com/softorg/" target="_blank">
            <img src="{{asset('/frontend/themes/vipp/assets/img/instagram.webp')}}" alt="instagram"/>
        </a>
        <a href="https://www.facebook.com/softorgdotcom/" target="_blank">
            <img src="{{asset('/frontend/themes/vipp/assets/img/facebook.webp')}}" alt="facebook"/>
        </a>
        <a href="https://www.pinterest.com/softorgdotcom/" target="_blank">
            <img src="{{asset('/frontend/themes/vipp/assets/img/pinterest.webp')}}" alt="pinterest"/>
        </a>
        <a href="https://www.linkedin.com/company/softorg" target="_blank">
            <img src="{{asset('/frontend/themes/vipp/assets/img/linkedin.webp')}}" alt="linkedin"/>
        </a>
        <a href="https://www.youtube.com/user/softorgdesign" target="_blank">
            <img src="{{asset('/frontend/themes/vipp/assets/img/youtube.webp')}}" alt="youtube"/>
        </a>
        <a href="https://twitter.com/softorg" target="_blank">
            <img src="{{asset('/frontend/themes/vipp/assets/img/twitter.webp')}}" alt="twitter"/>
        </a>
    </div>
    <ul style="margin-bottom:16px;">
        <li><a href="/org/@yield('website-name')">首页</a></li>
        <li><a href="/org/@yield('website-name')/product">产品</a></li>
        <li><a href="/org/@yield('website-name')/article">文章</a></li>
        <li><a href="/org/@yield('website-name')/activity">活动</a></li>
        <li><a href="/org/@yield('website-name')/survey">问卷</a></li>
    </ul>
    <div style="margin-bottom:16px;">

        <div class="term" style="margin-top:4px;">COPYRIGHT©{{$org->name or 'name'}}</div>
        <div class="term" style="margin-top:4px;">技术支持©上海如哉网络科技有限公司</div>
        <div class="term" style="margin-top:4px;">沪ICP备17052782号-1</div>

        <div class="copyright" style="display: none">COPYRIGHT©上海如哉网络科技有限公司 技术支持 (2017-2018) 沪ICP备17052782号-1</div>
        <div class="term" style="display: none"><a href="#">Terms and conditions</a></div>

    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>

<script src="{{ asset('/frontend/themes/vipp/js/jm.js') }}"></script>
<script src="{{ asset('/frontend/themes/vipp/js/jc.js') }}"></script>
<script src="{{ asset('frontend/themes/vipp/js/all.js') }}"></script>

<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
 <script>

    var wechat_config = {!! $wechat_config or '' !!};
    //    console.log(wechat_config);

    $(function(){

//        var link = window.location.href;
        var link = location.href.split('#')[0];
//        console.log(link);

        if(typeof wx != "undefined") wxFn();

        function wxFn() {

            wx.config({
                debug: false,
                appId: wechat_config.app_id, // 必填，公众号的唯一标识
                timestamp: wechat_config.timestamp, // 必填，生成签名的时间戳
                nonceStr: wechat_config.nonce_str, // 必填，生成签名的随机串
                signature: wechat_config.signature, // 必填，签名，见附录1
                jsApiList: [
                    'checkJsApi',
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage',
                    'onMenuShareQQ',
                    'onMenuShareQZone',
                    'onMenuShareWeibo'
                ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
            }) ;

            wx.ready(function(){
                wx.onMenuShareAppMessage({
                    title: '@yield('share_title')',
                    desc: '@yield('share_desc')',
                    link: link,
                    dataUrl: '',
                    imgUrl: '@yield('share_img')',
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        $.get(
                            "/share",
                            {
                                '_token': $('meta[name="_token"]').attr('content'),
                                'website': "{{$org->website_name or '0'}}",
                                'sort': 2,
                                'module': "@yield('share_module')",
                                'id': "{{$encode_id or '0'}}",
                                'share': 1
                            },
                            function(data) {
//                                    if(!data.success) layer.msg(data.msg);
                            }, 'json');
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });
                wx.onMenuShareTimeline({
                    title: '@yield('share_title')',
                    desc: '@yield('share_desc')',
                    link: link,
                    imgUrl: '@yield('share_img')',
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        $.get(
                            "/share",
                            {
                                '_token': $('meta[name="_token"]').attr('content'),
                                'website': "{{$org->website_name or '0'}}",
                                'sort': 2,
                                'module': "@yield('share_module')",
                                'id': "{{$encode_id or '0'}}",
                                'share': 2
                            },
                            function(data) {
//                                    if(!data.success) layer.msg(data.msg);
                            }, 'json');
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });
                wx.onMenuShareQQ({
                    title: '@yield('share_title')',
                    desc: '@yield('share_desc')',
                    link: link,
                    imgUrl: '@yield('share_img')',
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        $.get(
                            "/share",
                            {
                                '_token': $('meta[name="_token"]').attr('content'),
                                'website': "{{$org->website_name or '0'}}",
                                'sort': 2,
                                'module': "@yield('share_module')",
                                'id': "{{$encode_id or '0'}}",
                                'share': 3
                            },
                            function(data) {
//                                    if(!data.success) layer.msg(data.msg);
                            }, 'json');
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });
                wx.onMenuShareQZone({
                    title: '@yield('share_title')',
                    desc: '@yield('share_desc')',
                    link: link,
                    imgUrl: '@yield('share_img')',
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        $.get(
                            "/share",
                            {
                                '_token': $('meta[name="_token"]').attr('content'),
                                'website': "{{$org->website_name or '0'}}",
                                'sort': 2,
                                'module': "@yield('share_module')",
                                'id': "{{$encode_id or '0'}}",
                                'share': 4
                            },
                            function(data) {
//                                    if(!data.success) layer.msg(data.msg);
                            }, 'json');
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });
                wx.onMenuShareWeibo({
                    title: '@yield('share_title')',
                    desc: '@yield('share_desc')',
                    link: link,
                    imgUrl: '@yield('share_img')',
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        $.get(
                            "/share",
                            {
                                '_token': $('meta[name="_token"]').attr('content'),
                                'website': "{{$org->website_name or '0'}}",
                                'sort': 2,
                                'module': "@yield('share_module')",
                                'id': "{{$encode_id or '0'}}",
                                'share': 5
                            },
                            function(data) {
//                                    if(!data.success) layer.msg(data.msg);
                            }, 'json');
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });
            })   ;
        }
    });
</script>

</body>

</html>