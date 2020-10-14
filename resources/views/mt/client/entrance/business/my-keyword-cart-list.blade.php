@extends('mt.client.layout.layout')

@section('head_title','待选关键词  - 搜索引擎智能营销系统')

@section('header','待选关键词')
@section('description','搜索引擎智能营销系统')

@section('breadcrumb')
    <li><a href="{{url('/client')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->
            <div class="box box-info" id="item-content-body">

                <div class="box-header with-border" style="margin:16px 0;">
                    <h3 class="box-title">内容列表</h3>

                    <div class="caption">
                        <i class="icon-pin font-blue"></i>
                        <span class="caption-subject font-blue sbold uppercase"></span>
                        <a href="{{url(config('common.org.admin.prefix').'/item/create')}}">
                            <button type="button" onclick="" class="btn btn-success pull-right"><i class="fa fa-plus"></i> 添加内容</button>
                        </a>
                    </div>
                    <div class="pull-right" style="display:none;">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                            <i class="fa fa-times"></i></button>
                    </div>
                </div>

                <div class="box-body datatable-body" id="item-main-body">
                    <!-- datatable start -->
                    <table class='table table-striped table-bordered' id='datatable_ajax'>
                        <thead>
                        <tr role='row' class='heading'>
                            <th>选择</th>
                            <th>ID</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>历史数据</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><input type="text" class="form-control form-filter item-search-keyup" name="keyword" /></td>
                            <td></td>
                            <td>
                                {{--<select name="site" class="form-control form-filter select2-site" id="select2-site">--}}
                                    {{--<option value="0">请选择</option>--}}
                                {{--</select>--}}
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <select name="cartstatus" class="form-control form-filter">
                                    <option value ="0">全部</option>
                                    <option value ="未购买">未购买</option>
                                    <option value ="已购买">已购买</option>
                                </select>
                            </td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-xs filter-submit" id="filter-submit">搜索</a>
                                <a href="javascript:void(0);" class="btn btn-xs filter-cancel">重置</a>
                                {{--<div class="btn-group">--}}
                                {{--<button type="button" class="btn btn-sm btn-success">搜索</button>--}}
                                {{--<button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown">--}}
                                {{--<span class="caret"></span>--}}
                                {{--<span class="sr-only">Toggle Dropdown</span>--}}
                                {{--</button>--}}
                                {{--<ul class="dropdown-menu" role="menu">--}}
                                {{--<li><a href="#">重置</a></li>--}}
                                {{--<li class="divider"></li>--}}
                                {{--<li><a href="#">Separated link</a></li>--}}
                                {{--</ul>--}}
                                {{--</div>--}}
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!-- datatable end -->
                </div>

                <div class="box-footer">
                    <div class="row" style="margin:16px 0;">
                        <div class="col-md-offset-0 col-md-6 col-sm-8 col-xs-12">
                            {{--<button type="button" class="btn btn-primary"><i class="fa fa-check"></i> 提交</button>--}}
                            {{--<button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>--}}
                            <div class="input-group">
                                <span class="input-group-addon"><input type="checkbox" id="check-all"></span>
                                <select name="bulk-site" class="form-control form-filter select2-site" id="bulk-site" style="min-width:100%;"></select>
                                <span class="btn input-group-addon btn btn-default" id="bulk-buy-submit"><i class="fa fa-check"></i> 批量购买</span>
                                <span class="btn input-group-addon btn btn-default" id="bulk-delete-submit"><i class="fa fa-trash-o"></i> 批量删除</span>
                            </div>
                        </div>
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
            <!-- END PORTLET-->

        </div>
    </div>
@endsection


@section('custom-css')
<link href="https://cdn.bootcss.com/select2/4.0.5/css/select2.min.css" rel="stylesheet">
@endsection




@section('custom-js')
<script src="https://cdn.bootcss.com/select2/4.0.5/js/select2.min.js"></script>
@endsection
@section('custom-script')
<script>
    var TableDatatablesAjax = function () {
        var datatableAjax = function () {

            var dt = $('#datatable_ajax');
            var ajax_datatable = dt.DataTable({
//                "aLengthMenu": [[20, 50, 200, 500, -1], ["20", "50", "200", "500", "全部"]],
                "aLengthMenu": [[20, 50, 200], ["20", "50", "200"]],
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    'url': "{{ url('/client/business/my-keyword-cart-list') }}",
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.keyword = $('input[name="keyword"]').val();
                        d.website = $('input[name="website"]').val();
                        d.cartstatus = $('select[name="cartstatus"]').val();
//                        d.nickname 	= $('input[name="nickname"]').val();
//                        d.certificate_type_id = $('select[name="certificate_type_id"]').val();
//                        d.certificate_state = $('select[name="certificate_state"]').val();
//                        d.admin_name = $('input[name="admin_name"]').val();
//
//                        d.created_at_from = $('input[name="created_at_from"]').val();
//                        d.created_at_to = $('input[name="created_at_to"]').val();
//                        d.updated_at_from = $('input[name="updated_at_from"]').val();
//                        d.updated_at_to = $('input[name="updated_at_to"]').val();

                    },
                },
                "pagingType": "simple_numbers",
                "order": [],
                "orderCellsTop": true,
                "columns": [
                    {
                        "width": "48px",
                        "title": "选择",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return '<label><input type="checkbox" name="bulk-cart-id" class="minimal" value="'+data+'"></label>';
                        }
                    },
                    {
                        "width": "64px",
                        "title": "ID",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "width": "",
                        "title": "关键词",
                        "data": "keyword",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "width": "192px",
                        "title": "站点",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return '<select name="sites" class="form-control form-filter select2-site" style="min-width:100%;"></select>';

                        }
                    },
                    {
                        "width": "96px",
                        "title": "搜索引擎",
                        "data": "searchengine",
                        'orderable': false,
                        render: function(data, type, row, meta) {
//                            return data;
                            if(data == "baidu") return '百度PC';
                            else if(data == "baidu_mobile") return '百度移动';
                            else if(data == "sougou") return '搜狗';
                            else if(data == "360") return '360';
                            else if(data == "shenma") return '神马';
                            else return data;
                        }
                    },
                    {
                        "width": "",
                        "title": "创建时间",
                        "data": "createtime",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
//                            var $date = new Date(data);
//                            var $year = $date.getFullYear();
//                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
//                            var $day = ('00'+($date.getDate())).slice(-2);
//                            return $year+'-'+$month+'-'+$day;
                        }
                    },
                    {
                        "width": "",
                        "title": "价格",
                        "data": "price",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return parseInt(data)+'元/天';
                        }
                    },
                    {
                        "width": "",
                        "title": "状态",
                        "data": "cartstatus",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            if(data == '未购买') return '<small class="btn-xs bg-teal">未购买</small>';
                            else if(data == '已购买') return '<small class="btn-xs bg-red">已购买</small>';
                            else return data;
                        }
                    },
                    {
                        "width": "",
                        "title": "操作",
                        "data": 'id',
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var html =
                                {{--'<a class="btn btn-xs item-enable-submit" data-id="'+value+'">启用</a>'+--}}
                                {{--'<a class="btn btn-xs item-disable-submit" data-id="'+value+'">禁用</a>'+--}}
                                {{--'<a class="btn btn-xs item-download-qrcode-submit" data-id="'+value+'">下载二维码</a>'+--}}
                                {{--'<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+--}}
                                {{--'<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+--}}
                                {{--'<a class="btn btn-xs item-edit-submit" data-id="'+data+'">编辑</a>'+--}}
                                '<a class="btn btn-xs bg-primary item-buy-submit" data-id="'+data+'" >购买</a>'+
                                '<a class="btn btn-xs bg-navy item-delete-submit" data-id="'+data+'" >删除</a>';
                            return html;
                        }
                    }
                ],
                "drawCallback": function (settings) {
                    ajax_datatable.$('.tooltips').tooltip({placement: 'top', html: true});
                    $("a.verify").click(function(event){
                        event.preventDefault();
                        var node = $(this);
                        var tr = node.closest('tr');
                        var nickname = tr.find('span.nickname').text();
                        var cert_name = tr.find('span.certificate_type_name').text();
                        var action = node.attr('data-action');
                        var certificate_id = node.attr('data-id');
                        var action_name = node.text();

                        var tpl = "{{trans('labels.crc.verify_user_certificate_tpl')}}";
                        layer.open({
                            'title': '警告',
                            content: tpl
                                .replace('@action_name', action_name)
                                .replace('@nickname', nickname)
                                .replace('@certificate_type_name', cert_name),
                            btn: ['Yes', 'No'],
                            yes: function(index) {
                                layer.close(index);
                                $.post(
                                    '/admin/medsci/certificate/user/verify',
                                    {
                                        action: action,
                                        id: certificate_id,
                                        _token: '{{csrf_token()}}'
                                    },
                                    function(json){
                                        if(json['response_code'] == 'success') {
                                            layer.msg('操作成功!', {time: 3500});
                                            ajax_datatable.ajax.reload();
                                        } else {
                                            layer.alert(json['response_data'], {time: 10000});
                                        }
                                    }, 'json');
                            }
                        });
                    });
                },
                "language": { url: '/common/dataTableI18n' },
            });


            dt.on('click', '.select2-site', function () {
                $(this).select2({
                    ajax: {
                        url: "{{url('/client/business/select2_sites')}}",
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


            dt.on('click', '.filter-submit', function () {
                ajax_datatable.ajax.reload();
            });

            dt.on('click', '.filter-cancel', function () {
                $('textarea.form-filter, select.form-filter, input.form-filter', dt).each(function () {
                    $(this).val("");
                });

                $('select.form-filter').selectpicker('refresh');

                ajax_datatable.ajax.reload();
            });

        };
        return {
            init: datatableAjax
        }
    }();
    $(function () {
        TableDatatablesAjax.init();
    });
</script>
<script>
    $(function() {

        // 【下载二维码】
        $("#item-main-body").on('click', ".item-download-qrcode-submit", function() {
            var that = $(this);
            window.open("/download-qrcode?sort=org-item&id="+that.attr('data-id'));
        });

        // 【数据分析】
        $("#item-main-body").on('click', ".item-statistics-submit", function() {
            var that = $(this);
            window.open("/statistics/item?id="+that.attr('data-id'));
        });

        // 【编辑】
        $("#item-main-body").on('click', ".item-edit-submit", function() {
            var that = $(this);
            {{--layer.msg("/item/edit?id="+that.attr('data-id'));--}}
                window.location.href = "/item/edit?id="+that.attr('data-id');
        });




        // 【批量选择】全选or反选
        $("#item-content-body").on('click', '#check-all', function () {
            $('input[name="bulk-cart-id"]').prop('checked',this.checked);//checked为true时为默认显示的状态
        });


        // 【购买】【购物车】【关键词】
        $("#item-main-body").on('click', ".item-buy-submit", function() {
            var that = $(this);
            layer.msg('确定要"购买"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/client/business/keyword-buy') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            id:that.attr('data-id'),
                            website:that.parents('tr').find('select option:checked').val()
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else location.href = "{{ url('/client/business/my-keyword-list') }}";
                        },
                        'json'
                    );
                }
            });
        });
        // 【批量购买】【购物车】【关键词】
        $("#item-content-body").on('click', '#bulk-buy-submit', function() {
            var $checked = [];
            $('input[name="bulk-cart-id"]:checked').each(function() {
                $checked.push($(this).val());
            });

            layer.msg('确定要"批量购买"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/client/business/keyword-buy-bulk') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            bulk_cart_id:$checked,
                            bulk_site_id:$('#bulk-site').find("option:selected").val()
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else location.href = "{{ url('/client/business/my-keyword-list') }}";
                        },
                        'json'
                    );
                }
            });

        });


        // 【删除】【购物车】【关键词】
        $("#item-main-body").on('click', ".item-delete-submit", function() {
            var that = $(this);
            layer.msg('确定要"删除"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/client/business/keyword-cart-delete') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else location.reload();
                        },
                        'json'
                    );
                }
            });
        });
        // 【批量删除】【购物车】【关键词】
        $("#item-content-body").on('click', '#bulk-delete-submit', function() {
            var $checked = [];
            $('input[name="bulk-cart-id"]:checked').each(function() {
                $checked.push($(this).val());
            });

            layer.msg('确定要"批量删除"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/client/business/keyword-cart-delete-bulk') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            bulk_cart_id:$checked
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else location.reload();
                        },
                        'json'
                    );
                }
            });

        });




        // 【启用】
        $("#item-main-body").on('click', ".item-enable-submit", function() {
            var that = $(this);
            layer.msg('确定启用该"产品"？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/item/enable') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else location.reload();
                        },
                        'json'
                    );
                }
            });
        });
        // 【禁用】
        $("#item-main-body").on('click', ".item-disable-submit", function() {
            var that = $(this);
            layer.msg('确定禁用该"产品"？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/item/disable') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else location.reload();
                        },
                        'json'
                    );
                }
            });
        });

    });

    $('.select2-site').select2({
        ajax: {
            url: "{{url('/client/business/select2_sites')}}",
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

</script>
@endsection
