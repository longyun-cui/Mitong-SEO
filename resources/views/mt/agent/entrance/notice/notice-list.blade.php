@extends('mt.agent.layout.layout')

@section('head_title','公告列表 - 搜索引擎智能营销系统 - 米同科技')

@section('header','公告列表')
@section('description','搜索引擎智能营销系统-米同科技')


@section('breadcrumb')
    <li><a href="{{url('/agent')}}"><i class="fa fa-dashboard"></i>首页</a></li>
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
                    <a href="{{ url('/agent/notice/notice-create') }}">
                        <button type="button" onclick="" class="btn btn-success pull-right"><i class="fa fa-plus"></i> 添加公告</button>
                    </a>
                </div>
                <div class="pull-right" style="display:none;">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>

            <div class="box-body datatable-body item-main-body" id="item-main-body">
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
                        <th>操作</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="text" class="form-control form-filter item-search-keyup" name="title" style="width:100%;" /></td>
                        <td><input type="text" class="form-control form-filter item-search-keyup" name="creator" style="width:100%;" /></td>
                        <td>
                            <select name="sort" class="form-control form-filter">
                                <option value ="0">全部</option>
                                <option value ="1">管理员</option>
                                <option value ="9">代理商</option>
                            </select>
                        </td>
                        <td>
                            <select name="type" class="form-control form-filter">
                                <option value ="0">全部</option>
                                <option value ="1">全体公告</option>
                                <option value ="9">代理商</option>
                                <option value ="11">客户</option>
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <a class="btn btn-xs filter-submit" id="filter-submit">搜索</a>
                            <a class="btn btn-xs filter-cancel">重置</a>
                            {{--<div class="btn-group">--}}
                                {{--<button type="button" class="btn btn-sm btn-success filter-submit" id="filter-submit">搜索</button>--}}
                                {{--<button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown">--}}
                                    {{--<span class="caret"></span>--}}
                                    {{--<span class="sr-only">Toggle Dropdown</span>--}}
                                {{--</button>--}}
                                {{--<ul class="dropdown-menu" role="menu">--}}
                                    {{--<li><a href="javascript:void(0);" class="filter-cancel">重置</a></li>--}}
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
                        <h3 class="box-title">公告详情</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-modal">
                        <div class="box-body">

                            {{csrf_field()}}
                            <input type="hidden" name="operate" value="notice-get" readonly>
                            <input type="hidden" name="id" value="0" readonly>

                            {{--类别--}}

                            {{--标题--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">标题</label>
                                <div class="col-md-8 ">
                                    <div><b class="item-detail-title"></b></div>
                                </div>
                            </div>
                            {{--副标题--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">副标题</label>
                                <div class="col-md-8 ">
                                    <div class="item-detail-subtitle"></div>
                                </div>
                            </div>
                            {{--描述--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">描述</label>
                                <div class="col-md-8 ">
                                    <div class="item-detail-description"></div>
                                </div>
                            </div>
                            {{--内容--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">内容</label>
                                <div class="col-md-8 ">
                                    <div class="item-detail-content"></div>
                                </div>
                            </div>
                            {{--附件--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">附件</label>
                                <div class="col-md-8 ">
                                    <div class="item-detail-attachment"></div>
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
                    'url': "{{ url('/agent/notice/notice-list') }}",
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.title = $('input[name="title"]').val();
                        d.creator = $('input[name="creator"]').val();
                        d.sort = $('select[name="sort"]').val();
                        d.type = $('select[name="type"]').val();
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
                        "title": "ID",
                        "data": "id",
                        "orderable": true,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "className": "text-left",
                        "width": "",
                        "title": "标题",
                        "data": "title",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "className": "text-left",
                        "width": "128px",
                        "title": "发布者",
                        "data": "creator_id",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return row.creator == null ? '未知' : '<a target="_blank" href="/admin/user/agent?id='+row.creator.id+'">'+row.creator.username+'</a>';
                        }
                    },
                    {
                        "className": "",
                        "width": "80px",
                        "title": "发布者类型",
                        "data": "sort",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            if(data == 1) return '管理员';
                            else if(data == 9) return '<small class="btn-xs bg-purple">代理商</small>';
                            else return data;
                        }
                    },
                    {
                        "className": "",
                        "width": "80px",
                        "title": "接收者",
                        "data": "type",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            if(data == 1) return '<small class="btn-xs bg-olive">全体公告</small>';
                            else if(data == 9) return '<small class="btn-xs bg-purple">代理商公告</small>';
                            else if(data == 11) return '<small class="btn-xs bg-primary">客户公告</small>';
                            else return data;
                        }
                    },
                    {
                        "className": "",
                        "width": "144px",
                        "title": "发布时间",
                        "data": "updated_at",
                        "orderable": false,
                        render: function(data, type, row, meta) {
//                            return data;
                            var $date = new Date(data*1000);
                            var $year = $date.getFullYear();
                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
                            var $day = ('00'+($date.getDate())).slice(-2);
                            var $hour = ('00'+$date.getHours()).slice(-2);
                            var $minute = ('00'+$date.getMinutes()).slice(-2);
                            var $second = ('00'+$date.getSeconds()).slice(-2);
//                            return $year+'-'+$month+'-'+$day;
                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
//                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute+':'+$second;
                        }
                    },
                    {
                        "width": "64px",
                        "title": "状态",
                        "data": "active",
                        "orderable": false,
                        render: function(data, type, row, meta) {
//                            return data;
                            if(data == 0)
                            {
                                return '<small class="btn-xs bg-teal">待推送</small>';
                            }
                            else if(data == 1)
                            {
                                return '<small class="btn-xs bg-primary">已推送</small>'
                            }
                            else if(data == 9)
                            {
                                return '<small class="btn-xs bg-purple">已完成</small>';
                            }
                            else return "有误";
                        }
                    },
                    {
                        "width": "192px",
                        "title": "操作",
                        "data": 'id',
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            var $edit = '<a class="btn btn-xs btn-default disabled">编辑</a>';
                            var $push = '<a class="btn btn-xs btn-default disabled">推送</a>';
                            var $delete = '<a class="btn btn-xs btn-default disabled">删除</a>';

                            if(row.creator_id == "{{ Auth::guard('agent')->user()->id }}")
                            {
                                if(row.active == 0)
                                {
                                    $edit = '<a class="btn btn-xs bg-navy item-edit-submit" data-id="'+data+'">编辑</a>';
                                    $push = '<a class="btn btn-xs bg-navy item-push-submit" data-id="'+data+'" >推送</a>';
                                }
                                $delete = '<a class="btn btn-xs bg-navy item-delete-submit" data-id="'+data+'" >删除</a>';
                            }

                            var html =
//                                    '<a class="btn btn-xs item-enable-submit" data-id="'+value+'">启用</a>'+
//                                    '<a class="btn btn-xs item-disable-submit" data-id="'+value+'">禁用</a>'+
//                                    '<a class="btn btn-xs item-download-qrcode-submit" data-id="'+value+'">下载二维码</a>'+
//                                    '<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+
                                    {{--'<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+--}}
                                    $edit+
                                    $push+
                                    '<a class="btn btn-xs bg-primary item-detail-show" data-id="'+data+'">查看详情</a>'+
                                    $delete+
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

        // 表格【查询】
        $(".item-main-body").on('keyup', ".item-search-keyup", function(event) {
            if(event.keyCode ==13)
            {
                $("#filter-submit").click();
            }
        });

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
            window.location.href = "/agent/notice/notice-edit?id="+that.attr('data-id');
        });




        // 【显示详情】
        $("#item-main-body").on('click', ".item-detail-show", function() {
            var that = $(this);
            var $data = new Object();
            $.ajax({
                type:"post",
                dataType:'json',
                async:false,
                url: "{{ url('/agent/notice/notice-get') }}",
                data: {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate:"notice-get",
                    id:that.attr('data-id')
                },
                success:function(data){
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        $data = data.data;
                    }
                }
            });
            $('input[name=id]').val(that.attr('data-id'));
            $('.item-detail-title').html($data.title);
            $('.item-detail-subtitle').html($data.subtitle);
            $('.item-detail-description').html($data.description);
            $('.item-detail-content').html($data.content);
            if($data.attachment_name)
            {
                var $attachment_html = $data.attachment_name+'&nbsp&nbsp&nbsp&nbsp'+'<a href="/all/download-item-attachment?item-id='+$data.id+'">下载</a>';
                $('.item-detail-attachment').html($attachment_html);
            }
            $('#modal-body').modal('show');

        });


        // 【推送】
        $("#item-main-body").on('click', ".item-push-submit", function() {
            var that = $(this);
            layer.msg('确定要"推送"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/agent/notice/notice-push') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "notice-push",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg(data.msg);
//                                location.reload();
                                $('#datatable_ajax').DataTable().ajax.reload();
                            }
                        },
                        'json'
                    );
                }
            });
        });

        // 【删除】
        $("#item-main-body").on('click', ".item-delete-submit", function() {
            var that = $(this);
            layer.msg('确定要"删除"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/agent/notice/notice-delete') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "notice-delete",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg(data.msg);
//                                location.reload();
                                $('#datatable_ajax').DataTable().ajax.reload();
                            }
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
</script>
@endsection
