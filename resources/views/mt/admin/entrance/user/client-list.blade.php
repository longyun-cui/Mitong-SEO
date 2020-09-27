@extends('mt.admin.layout.layout')

@section('head_title','客户列表 - 管理员后台 - 搜索引擎智能营销系统 - 米同科技')

@section('header','客户列表')
@section('description','搜索引擎智能营销系统-米同科技')

@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
{{--用户余额不足--}}
@if(count($insufficient_clients))
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">

                <div class="box-header with-border" style="margin:16px 0;">
                    <h3 class="box-title">用户余额不足提醒</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                            <i class="fa fa-times"></i></button>
                    </div>
                </div>

                <div class="box-body">
                    @foreach($insufficient_clients as $client)
                        <div>
                            用户 <b class="text-red">{{ $client->username }}</b>  资金余额【<span class="text-red font-20px">{{ $client->fund_balance }}</span>】，余额不足一周消耗，请提醒续费！！
                        </div>
                    @endforeach
                </div>

                <div class="box-footer">
                    Footer
                </div>

            </div>
        </div>
    </div>
@endif


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">内容列表</h3>
                <div class="pull-right" style="display:none;">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>

            <div class="box-body datatable-body" id="item-main-body">
                <!-- datatable start -->
                <table class='table table-striped- table-bordered table-hover' id='datatable_ajax'>
                    <thead>
                    <tr role='row' class='heading'>
                        <th>ID</th>
                        <th>客户</th>
                        <th>代理商</th>
                        <th>站点</th>
                        <th>关键词</th>
                        <th>资产总额</th>
                        <th>累计消费</th>
                        <th>资金余额</th>
                        <th>初始冻结金额</th>
                        <th>冻结余额</th>
                        <th>可用余额</th>
                        <th>创建时间</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="text" class="form-control form-filter item-search-keyup" name="username" /></td>
                        <td><input type="text" class="form-control form-filter item-search-keyup" name="agentname" /></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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


<div class="modal fade" id="modal-password-body">
    <div class="col-md-8 col-md-offset-2" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;">

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="box- box-info- form-container">

                    <div class="box-header with-border" style="margin:16px 0;">
                        <h3 class="box-title">修改密码</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <form action="" method="post" class="form-horizontal form-bordered" id="form-change-password-modal">
                        <div class="box-body">

                            {{csrf_field()}}
                            <input type="hidden" name="operate" value="change-password" readonly>
                            <input type="hidden" name="id" value="0" readonly>

                            {{--类别--}}


                            {{--用户ID--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">新密码</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <input type="password" class="form-control form-filter" name="user-password" value="">
                                    6-20位英文、数值、下划线构成
                                </div>
                            </div>
                            {{--用户名--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">确认密码</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <input type="password" class="form-control form-filter" name="user-password-confirm" value="">
                                </div>
                            </div>


                        </div>
                    </form>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <button type="button" class="btn btn-success" id="item-change-password-submit"><i class="fa fa-check"></i> 提交</button>
                                <button type="button" class="btn btn-default" id="item-change-password-cancel">取消</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET-->
            </div>
        </div>
    </div>
</div>
@endsection


@section('custom-script')
<script>
    var TableDatatablesAjax = function () {
        var datatableAjax = function () {

            var dt = $('#datatable_ajax');
            var ajax_datatable = dt.DataTable({
//                "aLengthMenu": [[20, 50, 200, 500, -1], ["20", "50", "200", "500", "全部"]],
                "aLengthMenu": [[20, 50, 200, 500], ["20", "50", "200", "500"]],
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    'url': "{{ url('/admin/user/client-list') }}",
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.username = $('input[name="username"]').val();
                        d.agentname = $('input[name="agentname"]').val();
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
                        'width':"48px",
                        "title": "ID",
                        "data": "id",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        'className':"text-left",
                        'width':"96px",
                        "title": "客户",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return '<a target="_blank" href="/admin/user/client?id='+data+'">'+row.username+'</a>';
                        }
                    },
                    {
                        'className':"text-left",
                        'width':"96px",
                        "title": "所属代理商",
                        "data": "pid",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            if(row.parent) {
                                return '<a target="_blank" href="/admin/user/agent?id='+data+'">'+row.parent.username+'</a>';
                            } else {
                                return '--';
                            }

                        }
                    },
                    {
                        'width':"48px",
                        "title": "站点数",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return '<a target="_blank" href="/admin/user/client?id='+data+'">'+row.sites_count+'</a>';

                        }
                    },
                    {
                        'width':"48px",
                        "title": "关键词",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return '<a target="_blank" href="/admin/user/client?id='+data+'">'+row.keywords_count+'</a>';

                        }
                    },
                    {
                        'width':"64px",
                        "title": "资金总额",
                        "data": "fund_total",
                        'orderable': false,
                        render: function(data, type, row, meta) {
//                                return row.fund == null ? '未知' : row.fund.balancefunds;
                            return parseInt(data).toLocaleString();
                        }
                    },
                    {
                        'width':"64px",
                        "title": "累计消费",
                        "data": "fund_expense",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            return parseInt(data).toLocaleString();
                        }
                    },
                    {
                        'width':"64px",
                        "title": "余额",
                        "data": "fund_balance",
                        'orderable': true,
                        render: function(data, type, row, meta) {
//                                return row.fund == null ? '未知' : row.fund.balancefunds;
//                                return data;
                            if(data < 0) return '<b class="text-red">'+parseInt(data).toLocaleString()+'</b>';
                            else return parseInt(data).toLocaleString();
                        }
                    },
                    {
                        'width':"64px",
                        "title": "可用余额",
                        "data": "fund_available",
                        'orderable': true,
                        render: function(data, type, row, meta) {
//                                return row.fund == null ? '未知' : row.fund.availablefunds;
                            return parseInt(data).toLocaleString();
                        }
                    },
                    {
                        'width':"64px",
                        "title": "初始冻结",
                        "data": "fund_frozen_init",
                        'orderable': true,
                        render: function(data, type, row, meta) {
//                                return row.fund == null ? '未知' : row.fund.availablefunds;
                            return parseInt(data).toLocaleString();
                        }
                    },
                    {
                        'width':"64px",
                        "title": "冻结金额",
                        "data": "fund_frozen",
                        'orderable': true,
                        render: function(data, type, row, meta) {
//                                return row.fund == null ? '未知' : row.fund.availablefunds;
                            return parseInt(data).toLocaleString();
                        }
                    },
//                    {
//                        'data': 'menu_id',
//                        'orderable': false,
//                        render: function(data, type, row, meta) {
////                            return row.menu == null ? '未分类' : row.menu.title;
//                            if(row.menu == null) return '<small class="label btn-info">未分类</small>';
//                            else {
//                                return '<a href="/org-admin/item/menu?id='+row.menu.encode_id+'">'+row.menu.title+'</a>';
//                            }
//                        }
//                    },
//                    {
//                        'data': 'id',
//                        'orderable': false,
//                        render: function(data, type, row, meta) {
//                            return row.menu == null ? '未分类' : row.menu.title;
////                            var html = '';
////                            $.each(data,function( key, val ) {
////                                html += '<a href="/org-admin/item/menu?id='+this.id+'">'+this.title+'</a><br>';
////                            });
////                            return html;
//                        }
//                    },
                    {
                        'width':"80px",
                        'data': 'createtime',
                        'orderable': true,
                        render: function(data, type, row, meta) {
//                            return data;
                            var $date = new Date(data);
                            var $year = $date.getFullYear();
                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
                            var $day = ('00'+($date.getDate())).slice(-2);
                            return $year+'-'+$month+'-'+$day;
                        }
                    },
//                        {
//                            'data': 'created_at',
//                            'orderable': true,
//                            render: function(data) {
//                                newDate = new Date();
//                                newDate.setTime(data * 1000);
////                            return newDate.toLocaleString('chinese',{hour12:false});
//                                return newDate.toLocaleDateString();
//                            }
//                        },
                    {
                        'width':"64px",
                        'data': 'status',
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            if(data == 0) return '<small class="btn btn-xs">未启用</small>';
                            else if(data == 1) return '<small class="btn btn-xs">正常</small>';
                            else return '<small class="btn btn-xs bg-red">禁用</small>';
                        }
                    },
                    {
                        'data': 'id',
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var html =
//                                    '<a class="btn btn-xs item-enable-submit" data-id="'+value+'">启用</a>'+
//                                    '<a class="btn btn-xs item-disable-submit" data-id="'+value+'">禁用</a>'+
//                                    '<a class="btn btn-xs item-download-qrcode-submit" data-id="'+value+'">下载二维码</a>'+
//                                    '<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+
                                {{--'<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+--}}
                                '<a class="btn btn-xs btn-default disabled item-edit-submit-" data-id="'+data+'">编辑</a>'+
                                '<a class="btn btn-xs bg-navy item-change-password-show" data-id="'+data+'">修改密码</a>'+
                                '<a class="btn btn-xs bg-navy item-delete-submit" data-id="'+data+'" >删除</a>'+
                                '<a class="btn btn-xs bg-navy item-login-submit" data-id="'+data+'">登录</a>'+
                                '';
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
            window.location.href = "/admin/user/client-edit?id="+that.attr('data-id');
        });




        // 【登录】
        $("#item-main-body").on('click', ".item-login-submit", function() {
            var that = $(this);
            $.post(
                "{{ url('/admin/user/client-login') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    id:that.attr('data-id')
                },
                function(data){
                    if(!data.success) layer.msg(data.msg);
                    else window.open('/client/');
                },
                'json'
            );
        });

        // 【删除】
        $("#item-main-body").on('click', ".item-delete-submit", function() {
            var that = $(this);
            layer.msg('确定"删除"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/user/client-delete') }}",
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




        // 显示【修改密码】
        $("#item-main-body").on('click', ".item-change-password-show", function() {
            var that = $(this);
            $('input[name=id]').val(that.attr('data-id'));
            $('input[name=user-password]').val('');
            $('input[name=user-password-confirm]').val('');
            $('#modal-password-body').modal('show');
        });
        // 【修改密码】取消
        $("#modal-password-body").on('click', "#item-change-password-cancel", function() {
            $('input[name=id]').val('');
            $('input[name=user-password]').val('');
            $('input[name=user-password-confirm]').val('');
            $('#modal-password-body').modal('hide');
        });
        // 【修改密码】提交
        $("#modal-password-body").on('click', "#item-change-password-submit", function() {
            var that = $(this);
            layer.msg('确定"修改"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    var options = {
                        url: "{{ url('/admin/user/change-password') }}",
                        type: "post",
                        dataType: "json",
                        // target: "#div2",
                        success: function (data) {
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg(data.msg);
                                $('#modal-password-body').modal('hide');
                            }
                        }
                    };
                    $("#form-change-password-modal").ajaxSubmit(options);
                }
            });
        });




        // 【启用】
        $("#item-main-body").on('click', ".item-enable-submit", function() {
            var that = $(this);
            layer.msg('确定"启用"？', {
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
            layer.msg('确定"禁用"？', {
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
</script>
@endsection
