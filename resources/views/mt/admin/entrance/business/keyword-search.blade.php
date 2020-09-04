@extends('mt.admin.layout.layout')

@section('head_title','关键词查询  - 搜索引擎智能营销系统 - 米同科技')

@section('header','关键词查询')
@section('description','搜索引擎智能营销系统-米同科技')

@section('breadcrumb')
    <li><a href="{{url('/client')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info form-container">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">关键词查询</h3>
                <div class="box-tools pull-right">
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered" id="form-search-item">
                <div class="box-body">

                    {{csrf_field()}}
                    <input type="hidden" name="operate" value="{{ $operate or '' }}" readonly>
                    <input type="hidden" name="operate_id" value="{{ $operate_id or 0 }}" readonly>


                    {{--关键词--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">关键词</label>
                        <div class="col-md-8 ">
                            <textarea class="form-control" name="keywords" rows="3" cols="100%" placeholder="请输入关键词，多个关键词查询请回车，关键词不能超过10个"></textarea>
                        </div>
                    </div>

                </div>
            </form>

            <div class="box-footer">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" class="btn btn-success" id="search-item-submit"><i class="fa fa-check"></i> 提交</button>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>

<div class="box box-danger box-solid" id="search-overlay" style="display:none;">
    <div class="box-header">
        <h3 class="box-title">关键词价格查询中</h3>
    </div>
    <div class="box-body">
        请耐心等待...
    </div>
    <!-- /.box-body -->
    <!-- Loading (remove the following to stop the loading)-->
    <div class="overlay">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
</div>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info form-container">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">关键词查询结果</h3>
                <div class="box-tools pull-right">
                </div>
            </div>

            <div class="box-body">
                {{--关键词结果--}}
                <div class="form-group" id="keyword-search-result">

                    <div id="search">

                        <div class="box box-success _none">

                            <div class="box-header with-border" style="margin:16px 0;">
                                <h3 class="box-title">系统为您推荐的词</h3>
                                <div class="caption">
                                    <i class="icon-pin font-blue"></i>
                                    <span class="caption-subject font-blue sbold uppercase"></span>
                                </div>
                                <div class="pull-right" style="display:none;">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                                        <i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                                        <i class="fa fa-times"></i></button>
                                </div>
                            </div>

                            <div class="box-body datatable-body" id="recommend">

                                <!-- 加载进度 begin -->
                                <!-- <div class="progress">
                                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="sr-only">45% Complete</span></div>
                                </div> -->

                                <div class="progress" style="margin: 0px auto; text-align: center; padding: 0px 20px 20px; display: none;">
                                    <img class="progress-img" alt="" src="/seo/img/laoding.gif">
                                    <div class="progress-text mt10">系统正在为你查询相关推荐的关键词</div>

                                </div>
                                <!-- 加载进度 end -->
                                <div id="show_recommend">
                                    <div class="alert alert-warning" role="alert">未能获取到相关推荐关键词！</div>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="row" style="margin:16px 0;">
                                    <div class="col-md-offset-0 col-md-9">
                                        <button type="button" onclick="" class="btn btn-primary _none"><i class="fa fa-check"></i> 提交</button>
                                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                                    </div>
                                </div>
                            </div>

                        </div>


                        {{--<form name="form2" id="form2" action="" method="post" class="table-responsive">--}}
                        <input type="hidden" name="keywords" value="">

                        <div class="box box-success">

                            <div class="box-header with-border" style="margin:16px 0;">
                                <h3 class="box-title">关键词清单</h3>
                                <div class="caption">
                                    <i class="icon-pin font-blue"></i>
                                    <span class="caption-subject font-blue sbold uppercase"></span>
                                </div>
                                <div class="pull-right" style="display:none;">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                                        <i class="fa fa-minus"></i></button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                                        <i class="fa fa-times"></i></button>
                                </div>
                            </div>

                            <div class="box-body datatable-body" id="form-main-body">
                                <!-- datatable start -->
                                <form action="" method="post" class="form-horizontal form-bordered" id="form-keyword">
                                    {{csrf_field()}}
                                    <table class='table table-striped table-bordered list' id='tablelist'>
                                        <thead>
                                        <tr role='row' class='heading'>
                                            <td class="text-left" style="text-align:left">关键词</td>
                                            <td class="text-center key_pc"><img alt="" src="/seo/img/baidu.png" style="width: 100px;"></td>
                                            <td class="text-center key_yidong"><img alt="" src="/seo/img/baidu_mobile.png" style="width: 100px;"></td>
                                            <td class="text-center key_360"><img alt="" src="/seo/img/360.png" style="width: 100px;"></td>
                                            <td class="text-center key_sougou"><img alt="" src="/seo/img/sougou.png" style="width: 100px;"></td>
                                            <td class="text-center key_shenma"><img alt="" src="/seo/img/shenma.png" style="width: 100px;"></td>
                                            <td class="text-center" width="96px">难度指数</td>
                                            <td class="text-center" width="96px">优化周期</td>
                                        </tr>
                                        </thead>
                                        <tbody id="keyword-result-list">
                                        </tbody>
                                    </table>
                                </form>
                                <!-- datatable end -->
                            </div>

                        </div>

                        {{--</form>--}}



                    </div>

                </div>

            </div>

            {{--<div class="box-footer">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-md-8 col-md-offset-2">--}}
                        {{--<button type="button" class="btn btn-success" id="search-item-submit"><i class="fa fa-check"></i> 提交</button>--}}
                        {{--<button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
        <!-- END PORTLET-->
    </div>
</div>
@endsection


@section('custom-css')
<link rel="stylesheet" href="{{ asset('seo/css/keyword_add.css') }}">
@endsection

@section('custom-style')
    <style type="text/css">
        .list td, .list th {
            /* padding: 5px; */
            vertical-align: middle;
            text-align: center;
        }
        .list th, .list td {
            border: 1px solid #eee;
        }
        .list td, .list {
            font-size: 13px;
        }
        .list td, .list th {
            padding: 9px 15px;
            min-height: 20px;
            line-height: 20px;
            border: 1px solid #e2e2e2;
            font-size: 14px;
        }
    </style>
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
        $("#search-item-submit").on('click', function() {
            var options = {
                url: "{{ url('/admin/business/keyword-search') }}",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        $("#search-overlay").hide();
//                        $('#keyword-search-result').html(data.data.html);
                        $('#keyword-result-list').html(data.data.html);
                    }
                }
            };
            $("#form-search-item").ajaxSubmit(options);
            $("#search-overlay").show();
        });

        // 添加or编辑
        $("#keyword-search-result").on('click', '.keyword-cart-add', function() {
            var options = {
                url: "{{ url('/client/business/keyword-cart-add') }}",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.msg);
                        location.href = "{{ url('/client/business/my-keyword-cart-list') }}";
                    }
                    return false;
                }
            };
            var $form = $(this).parents('#form-main-body').find('form');
            $form.ajaxSubmit(options);
        });

        // 添加or编辑
        $("#keyword-search-result").on('click', '.keyword-cart-all-add', function() {
            var options = {
                url: "{{ url('/client/business/keyword-cart-add') }}",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.msg);
                        location.href = "{{ url('/client/business/my-keyword-cart-list') }}";
                    }
                    return false;
                }
            };
            var $form = $("#form-main-body").find('form');
            $form.ajaxSubmit(options);
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
<script>
    $(function() {


        $('.recommend .btn').click(checked);
        // $('.list .keyword_price').click(checked);
        // $('.list').delegate('.keyword_price', 'click',checked);
        $('#keyword-search-result').on('click', '.keyword_price', checked1);
        // $('#tablelist').on('click','.keyword_price',checked);

        $('.key_pc').click(function() {
            $('.price_pc').each(checked1);
        });
        $('.key_yidong').click(function() {
            $('.price_yidong').each(checked1);
        });
        $('.key_360').click(function() {
            $('.price_360').each(checked1);
        });
        $('.key_sougou').click(function() {
            $('.price_sougou').each(checked1);
        });
        $('.key_shenma').click(function() {
            $('.price_shenma').each(checked1);
        });

        // $('.list').delegate('.')
        /*$('#tablelist').on('click','.keyword',function () {
                                        $(this).parents('tr').find('.keyword_price').each(checked);
                                    });*/

        $('.list').delegate('.keyword', 'click', function() {
                $(this).parents('tr').find('.keyword_price').each(checked);
            });

        $('#recommend').on('click', 'a', function() {
                if (this.className.indexOf('checked') > 0) {
                    $(this).removeClass('checked');
                    var k = $(this).attr("mark");
                    $("#tr" + k).addClass("hidden1");
                    $("#tr" + k).find("input:checkbox").prop("checked", false);

                } else {
                    $(this).addClass('checked');
                    var k = $(this).attr("mark");
                    $("#tr" + k).removeClass("hidden1");
                    $("#tr" + k).find("input:checkbox").prop("checked", true);
                }
            });

    });

    //
    function checked()
    {
        var keyword_checked = $(this).parents('tr').find('.keyword').get(0).checked;
        if (!keyword_checked) {
            $(this).removeClass('checked');
        } else {
            $(this).addClass('checked');
        }

        $('.list tbody tr').each(function() {
            var kstr = "";
            var pricestr="";
            var kw = $(this).find('input:checkbox').val();
            $(this).find('td').each(function(i,e) {
                var price ="";
                if($(e).hasClass('keyword_price')){
                    price = $(e).find(':hidden').val()
                }

                if ($(this).hasClass('checked')) {
                    kstr += '1';
                    if($(e).hasClass('keyword_price')){
                        if(pricestr){
                            pricestr +=  "," + price;
                        }else{
                            pricestr +=   price;
                        }
                    }

                } else {
                    kstr += '0';
                    if($(e).hasClass('keyword_price')){
                        if(pricestr){
                            pricestr +=  ",0";
                        }else{
                            pricestr +=   "0";
                        }
                    }
                }
            });
            kstr = kstr.substr(1, 5);
            if (kw != undefined) {
                $(this).find('a:eq(0)').attr('href', '/Keyword/doAdd/keyword/' + kw.substr(0, kw.indexOf('::')) + '/keywords/nihao,wohao/type/' + kstr + '/pricestr/' + pricestr);
                $(this).find(':checkbox').val(kw.substr(0, kw.indexOf('::')) + '::' + kstr + '::' + pricestr);
            }

        });
    }

    /**
     * 点击td事件
     */

    function checked1()
    {

        if ($(this).hasClass('checked'))
        {
            $(this).removeClass('checked');
//            var that = $(this).find('input[name^="price"]').val(0);
            var that = $(this).find('input[name^="keywords"]');
            that.val(0);
        }
        else
        {
            $(this).addClass('checked');
//            $(this).find('input[name^="price"]').val($(this).find('input[name^="price"]').attr("data-price"));
            var that = $(this).find('input[name^="keywords"]');
            that.val(that.attr("data-price"));
        }

        $('.list tbody tr').each(function() {
            var kstr = "";
            var pricestr="";
            var kw = $(this).find('input:checkbox').val();
            $(this).find('td').each(function(i,e) {
                var price ="";
                if($(e).hasClass('keyword_price')){
                    price = $(e).find(':hidden').val()
                }
                console.log(price);
                if ($(this).hasClass('checked')) {
                    kstr += '1';
                    if($(e).hasClass('keyword_price')){
                        if(pricestr){
                            pricestr +=  "," + price;
                        }else{
                            pricestr +=   price;
                        }
                    }

                } else {
                    kstr += '0';
                    if($(e).hasClass('keyword_price')){
                        if(pricestr){
                            pricestr +=  ",0";
                        }else{
                            pricestr +=   "0";
                        }
                    }
                }
            });



            kstr = kstr.substr(1, 5);
            if (kw != undefined) {
                $(this).find('a:eq(0)').attr('href', '/Keyword/doAdd/keyword/' + kw.substr(0, kw.indexOf('::')) + '/keywords/nihao,wohao/type/' + kstr + '/pricestr/' + pricestr);
                $(this).find(':checkbox').val(kw.substr(0, kw.indexOf('::')) + '::' + kstr + '::' + pricestr);
            }

        });
    }

    function selectAll()
    {
        $(".list tr").not(".hidden1").find(":checkbox")
            .each(function() {
                $(this).trigger("click");
            });
    }
    function addAll()
    {

        /* $.each($('input:checked'), function(i, n){
             //console.log($(n).css("display"));
             id = $(n).val();
             if (id  && $(n).css("display") !="none" && id != "on") ids.push(id);
         });

        $("input[name='check[]']").each(function(i,e){
           console.log($(this).attr('checked'));

       }) */
        var ids = getChecked();
        if(ids == ''){
            layer_alert("请您选择关键词！");
            return false;
        }


        $('#form2').submit();
    }
    function rm(obj) {
        var num = obj.getAttribute('mark');
        $("#tr" + num).remove();

    }

</script>
@endsection
