@extends('mt.admin.layout.layout')

@section('head_title','站点列表 - 搜索引擎智能营销系统 - 米同科技')

@section('header','站点列表')
@section('description','搜索引擎智能营销系统-米同科技')


@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">内容列表</h3>
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

            <div class="box-body datatable-body" id="item-main-body">
                <!-- datatable start -->
                <table class='table table-striped table-bordered' id='datatable_ajax'>
                    <thead>
                    <tr role='row' class='heading'>
                        <th>ID</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        {{--<th></th>--}}
                        <th></th>
                        <th></th>
                        <th>操作</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><input type="text" class="form-control form-filter item-search-keyup" name="sitename" /></td>
                        <td><input type="text" class="form-control form-filter item-search-keyup" name="website" /></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        {{--<td></td>--}}
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


<div class="modal fade" id="modal-body">
    <div class="col-md-8 col-md-offset-2" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;">

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="box- box-info- form-container">

                    <div class="box-header with-border" style="margin:16px 0;">
                        <h3 class="box-title">站点详情</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-modal">
                        <div class="box-body">

                            {{csrf_field()}}
                            <input type="hidden" name="operate" value="recharge" readonly>
                            <input type="hidden" name="id" value="0" readonly>

                            {{--类别--}}


                            {{--用户ID--}}
                            <div class="form-group _none">
                                <label class="control-label col-md-2">用户ID</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="site-user-id"></span>
                                </div>
                            </div>
                            {{--用户名--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">用户名</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="site-username"></span>
                                </div>
                            </div>
                            {{--站点名称--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">站点名称</label>
                                <div class="col-md-8 ">
                                    <span class="site-name"></span>
                                </div>
                            </div>
                            {{--站点地址--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">站点地址</label>
                                <div class="col-md-8 ">
                                    <span class="site-website"></span>
                                </div>
                            </div>
                            {{--FTP--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">FTP</label>
                                <div class="col-md-8 ">
                                    <span class="site-ftp"></span>
                                </div>
                            </div>
                            {{--管理后台--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">管理后台</label>
                                <div class="col-md-8 ">
                                    <span class="site-managebackground"></span>
                                </div>
                            </div>
                            {{--说明--}}
                            <div class="form-group _none">
                                <label class="control-label col-md-2">说明</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="">正数为充值，负数为退款，退款金额不能超过资金余额。</span>
                                </div>
                            </div>


                        </div>
                    </form>

                    <div class="box-footer">
                        <div class="row _none">
                            <div class="col-md-8 col-md-offset-2">
                                <button type="button" class="btn btn-success" id="item-site-submit"><i class="fa fa-check"></i> 提交</button>
                                <button type="button" class="btn btn-default modal-cancel" id="item-site-cancel">取消</button>
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
                "aLengthMenu": [[20, 50, 200], ["20", "50", "200"]],
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    'url': "{{ url('/admin/business/site-list') }}",
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.sitename = $('input[name="sitename"]').val();
                        d.website = $('input[name="website"]').val();
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
                        "title": "ID",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "className": "text-left",
                        "width": "108px",
                        "title": "客户",
                        "data": "createuserid",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return row.creator == null ? '未知' : '<a target="_blank" href="/admin/user/client?id='+row.creator.id+'">'+row.creator.username+'</a>';
                        }
                    },
                    {
                        "className": "text-left",
                        "width": "192px",
                        "title": "站点",
                        "data": "sitename",
                        'orderable': false,
                        render: function(data, type, row, meta) {
//                            return data;
                            return '<span href="javascript:void(0);" class="item-site-show text-blue _pointer" data-user-id="'+row.creator.id+'" data-username="'+row.creator.username+'" data-name="'+data+'" data-website="'+row.website+'" data-ftp="'+row.ftp+'" data-managebackground="'+row.managebackground+'">'+data+'</span>'
                        }
                    },
                    {
                        "className": "text-left",
                        "width": "",
                        "title": "Website",
                        "data": "website",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "width": "64px",
                        "title": "关键词数",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
//                            return row.keywords_count == null ? 0 : row.keywords_count;
                            if(row.keywords_count)
                            {
                                if(row.keywords_count > 0) return '<span class="text-blue">'+row.keywords_count+'</span>';
                                else row.keywords_count;
                            }
                            else return 0;
                        }
                    },
                    {
                        "width": "64px",
                        "title": "今日达标",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            if(row.standard_today_count)
                            {
                                if(row.standard_today_count > 0)
                                {
                                    return '<span class="text-blue">'+row.standard_today_count.toLocaleString()+'</span>';
                                }
                                else return parseInt(row.standard_today_count).toLocaleString();
                            }
                            else return 0;
                        }
                    },
                    {
                        "width": "64px",
                        "title": "今日消费",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
//                            return row.consumption_sum == null ? 0 : row.consumption_sum;
                            if(row.consumption_today_sum)
                            {
                                if(row.consumption_today_sum > 0)
                                {
                                    return '<span class="text-blue">'+parseInt(row.consumption_today_sum).toLocaleString()+'</span>';
                                }
                                else return parseInt(row.consumption_today_sum).toLocaleString();
                            }
                            else return 0;
                        }
                    },
//                    {
//                        "width": "64px",
//                        "title": "累计达标",
//                        "data": "id",
//                        'orderable': false,
//                        render: function(data, type, row, meta) {
//                            if(row.standard_all_sum)
//                            {
//                                if(row.standard_all_sum > 0)
//                                {
//                                    return '<span class="text-blue">'+row.standard_all_sum.toLocaleString()+'</span>';
//                                }
//                                else return parseInt(row.standard_all_sum).toLocaleString();
//                            }
//                            else return 0;
//                        }
//                    },
                    {
                        "width": "64px",
                        "title": "累计消费",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
//                            return row.consumption_sum == null ? 0 : row.consumption_sum;
                            if(row.consumption_all_sum)
                            {
                                if(row.consumption_all_sum > 0)
                                {
                                    return '<span class="text-blue">'+parseInt(row.consumption_all_sum).toLocaleString()+'</span>';
                                }
                                else return parseInt(row.consumption_all_sum);
                            }
                            else return 0;
                        }
                    },
                    {
                        "width": "80px",
                        "title": "创建时间",
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
                    {
                        "width": "64px",
                        "title": "状态",
                        "data": "sitestatus",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            if(row.status == 1)
                            {
                                if(data == '优化中') return '<small class="btn-xs bg-primary">优化中</small>';
                                else if(data == '待审核') return '<small class="btn-xs bg-teal">待审核</small>';
                                else if(data == '合作停') return '<small class="btn-xs bg-red">合作停</small>';
                                else return data;
                            }
                            else
                            {
                                return '<small class="btn-xs bg-navy">已删除</small>';
                            }
                        }
                    },
                    {
                        "width": "",
                        "title": "操作",
                        "data": 'id',
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var $work_order_html = '';
                            if(row.work_order_count > 0)
                            {
                                $work_order_html = '<a class="btn btn-xs bg-navy item-work-order-link" data-id="'+data+'" >Ta的工单</a>';
                            }
                            else
                            {
                                $work_order_html = '<a class="btn btn-xs btn-default disabled" data-id="'+data+'" >Ta的工单</a>';
                            }
                            var html =
//                                '<a class="btn btn-xs item-enable-submit" data-id="'+value+'">启用</a>'+
//                                '<a class="btn btn-xs item-disable-submit" data-id="'+value+'">禁用</a>'+
//                                '<a class="btn btn-xs item-download-qrcode-submit" data-id="'+value+'">下载二维码</a>'+
//                                '<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+
                                    {{--'<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+--}}
//                                '<a class="btn btn-xs item-edit-submit" data-id="'+value+'">编辑</a>'+
                                '<a class="btn btn-xs bg-navy item-work-order-create-link" data-id="'+data+'">+工单</a>'+
                                $work_order_html+
                                '<a class="btn btn-xs bg-navy item-stop-submit" data-id="'+data+'" >合作停</a>'+
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
            window.location.href = "/item/edit?id="+that.attr('data-id');
        });

        // 跳转【添加工单】
        $("#item-main-body").on('click', ".item-work-order-create-link", function() {
            var that = $(this);
            window.open("/admin/business/site/work-order-create?site-id="+that.attr('data-id'));
        });

        // 跳转【站点工单】
        $("#item-main-body").on('click', ".item-work-order-link", function() {
            var that = $(this);
            window.open("/admin/business/site/work-order-list?site-id="+that.attr('data-id'));
        });

        // 【删除】
        $("#item-main-body").on('click', ".item-delete-submit", function() {
            var that = $(this);
            layer.msg('确定要"删除"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/business/site-delete') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"delete-site",
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

        // 【合作停】
        $("#item-main-body").on('click', ".item-stop-submit", function() {
            var that = $(this);
            layer.msg('确定要"合作停"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/business/site-stop') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"stop-site",
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

        // 【再合作】
        $("#item-main-body").on('click', ".item-start-submit", function() {
            var that = $(this);
            layer.msg('确定要"再合作"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/business/site-start') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"start-site",
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


        // 显示【站点详情】
        $("#item-main-body").on('click', ".item-site-show", function() {
            var that = $(this);
            $('input[name=id]').val(that.attr('data-id'));
            $('.site-user-id').html(that.attr('data-user-id'));
            $('.site-username').html(that.attr('data-username'));
            $('.site-name').html(that.attr('data-name'));
            $('.site-website').html(that.attr('data-website'));
            $('.site-ftp').html(that.attr('data-ftp'));
            $('.site-managebackground').html(that.attr('data-managebackground'));
            $('#modal-body').modal('show');
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
</script>
@endsection
