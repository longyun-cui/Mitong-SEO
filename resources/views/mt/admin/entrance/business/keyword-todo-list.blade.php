@extends('mt.admin.layout.layout')

@section('head_title','今日关键词列表 - 搜索引擎智能营销系统 - 米同科技')

@section('header','今日关键词列表')
@section('description','搜索引擎智能营销系统-米同科技')


@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i>首页</a></li>
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
                        <th></th>
                        <th>操作</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><input type="text" class="form-control form-filter item-search-keyup" name="keyword" /></td>
                        <td><input type="text" class="form-control form-filter item-search-keyup" name="website" /></td>
                        <td>
                            <select name="searchengine" class="form-control form-filter">
                                <option value ="0">全部</option>
                                <option value ="baidu">百度PC</option>
                                <option value ="baidu_mobile">百度移动</option>
                                <option value ="sougou">搜狗</option>
                                <option value ="360">360</option>
                                <option value ="shenma">神马</option>
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <a href="javascript:void(0);" class="btn btn-xs filter-submit" id="filter-submit">搜索</a>
                            <a href="javascript:void(0);" class="btn btn-xs filter-cancel">重置</a>
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
                    <div class="col-md-offset-0 col-md-6 col-sm-8 col-xs-12">
                        {{--<button type="button" class="btn btn-primary"><i class="fa fa-check"></i> 提交</button>--}}
                        {{--<button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>--}}
                        <div class="input-group">
                            <span class="input-group-addon"><input type="checkbox" id="check-all"></span>
                            <select name="bulk-status" class="form-control form-filter" id="bulk-keyword-status" style="min-width:100%;">
                                <option value ="0">请选择</option>
                                <option value ="优化中">优化中</option>
                                <option value ="被拒绝">被拒绝</option>
                            </select>
                            <span class="btn input-group-addon" id="bulk-review-submit"><i class="fa fa-check"></i> 批量审核</span>
                            <span class="btn input-group-addon" id="bulk-delete-submit"><i class="fa fa-trash-o"></i> 批量删除</span>
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


<div class="modal fade" id="modal-body">
    <div class="col-md-8 col-md-offset-2" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;">

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="box- box-info- form-container">

                    <div class="box-header with-border" style="margin:16px 0;">
                        <h3 class="box-title">代理商充值</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-modal">
                        <div class="box-body">

                            {{csrf_field()}}
                            <input type="hidden" name="operate" value="review" readonly>
                            <input type="hidden" name="id" value="0" readonly>

                            {{--类别--}}


                            {{--站点ID--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">关键词ID</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="review-site-id"></span>
                                </div>
                            </div>
                            {{--关键词--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">关键词</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="review-keyword"></span>
                                </div>
                            </div>
                            {{--站点名称--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">站点名称</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="review-site-name"></span>
                                </div>
                            </div>
                            {{--站点--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">站点</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="review-website"></span>
                                </div>
                            </div>
                            {{--站点--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">调整价格</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <input type="text" class="form-control review-price" name="review-price" placeholder="调整价格" value="0">
                                </div>
                            </div>
                            {{--审核意见--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">审核结果</label>
                                <div class="col-md-8 ">
                                    <select name="keywordstatus" class="form-control form-filter">
                                        <option value ="0">请选择</option>
                                        <option value ="优化中">优化中</option>
                                        <option value ="被拒绝">被拒绝</option>
                                    </select>
                                </div>
                            </div>
                            {{--备注--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">备注</label>
                                <div class="col-md-8 ">
                                    {{--<input type="text" class="form-control" name="description" placeholder="描述" value="{{$data->description or ''}}">--}}
                                    <textarea class="form-control" name="description" rows="3" cols="100%">{{ $data->description or '' }}</textarea>
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
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <button type="button" class="btn btn-success" id="item-review-submit"><i class="fa fa-check"></i> 提交</button>
                                <button type="button" class="btn btn-default" id="item-review-cancel">取消</button>
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
                    'url': "{{ url('/admin/business/keyword-todo') }}",
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.keyword = $('input[name="keyword"]').val();
                        d.website = $('input[name="website"]').val();
                        d.searchengine = $('select[name="searchengine"]').val();
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
                            return '<label><input type="checkbox" name="bulk-keyword-id" class="minimal" value="'+data+'"></label>';
                        }
                    },
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
                        "width": "",
                        "title": "客户",
                        "data": "createuserid",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return row.creator == null ? '未知' : row.creator.username;
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
                        "width": "",
                        "title": "站点",
                        "data": "website",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "width": "",
                        "title": "搜索引擎",
                        "data": "searchengine",
                        'orderable': false,
                        render: function(data, type, row, meta) {
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
                        }
                    },
                    {
                        "width": "",
                        "title": "单价",
                        "data": "price",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return parseInt(data)+'元/天';
                        }
                    },
                    {
                        "width": "",
                        "title": "状态",
                        "data": "keywordstatus",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            if(data == '待审核') return '<small class="btn-xs bg-teal">待审核</small>';
                            else if(data == '合作停') return '<small class="btn-xs bg-red">合作停</small>';
                            else return data;
                        }
                    },
                    {
                        "data": 'id',
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var html =
//                                '<a class="btn btn-xs item-enable-submit" data-id="'+value+'">启用</a>'+
//                                '<a class="btn btn-xs item-disable-submit" data-id="'+value+'">禁用</a>'+
//                                '<a class="btn btn-xs item-download-qrcode-submit" data-id="'+value+'">下载二维码</a>'+
//                                '<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+
                                {{--'<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+--}}
//                                '<a class="btn btn-xs item-edit-submit" data-id="'+value+'">编辑</a>'+
//                                '<a class="btn btn-xs item-delete-submit" data-id="'+value+'" >删除</a>';
//                                '<a class="btn btn-xs item-show-submit" data-id="'+value+'" >数据详情</a>';
                                '<a class="btn btn-xs bg-primary item-review-show" data-id="'+data+'" data-name="'+row.sitename+'" data-website="'+row.website+'" data-keyword="'+row.keyword+'" data-price="'+row.price+'">审核</a>'+
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

        // 表格【查询】
        $("#product-list-body").on('keyup', ".item-search-keyup", function(event) {
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
            {{--layer.msg("/item/edit?id="+that.attr('data-id'));--}}
                window.location.href = "/item/edit?id="+that.attr('data-id');
        });




        // 【审核】显示
        $("#item-main-body").on('click', ".item-review-show", function() {
            var that = $(this);
            $('input[name=id]').val(that.attr('data-id'));
            $('.review-site-id').html(that.attr('data-id'));
            $('.review-site-name').html(that.attr('data-name'));
            $('.review-website').html(that.attr('data-website'));
            $('.review-keyword').html(that.attr('data-keyword'));
            $('.review-price').val(that.attr('data-price'));
            $('#modal-body').modal('show');
        });
        // 【审核】取消
        $("#modal-body").on('click', "#item-review-cancel", function() {
            $('.review-user-id').html('');
            $('.review-user-name').html('');
            $('.review-website').html('');
            $('.review-keyword').html('');
            $('.review-price').val(0);
            $('#modal-body').modal('hide');
        });

        // 【审核】提交
        $("#modal-body").on('click', "#item-review-submit", function() {
            var that = $(this);
            layer.msg('确定"审核"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){

                    var options = {
                        url: "{{ url('/admin/business/keyword-review') }}",
                        type: "post",
                        dataType: "json",
                        // target: "#div2",
                        success: function (data) {
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg(data.msg);
                                location.reload();
                            }
                        }
                    };
                    $("#form-edit-modal").ajaxSubmit(options);
                }
            });
        });




        // 【批量选择】全选or反选
        $("#item-content-body").on('click', '#check-all', function () {
            $('input[name="bulk-keyword-id"]').prop('checked',this.checked);//checked为true时为默认显示的状态
        });

        // 【批量审核】【关键词】
        $("#item-content-body").on('click', '#bulk-review-submit', function() {
            var $checked = [];
            $('input[name="bulk-keyword-id"]:checked').each(function() {
                $checked.push($(this).val());
            });

            layer.msg('确定要"批量审核"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/business/keyword-review-bulk') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            bulk_keyword_id:$checked,
                            bulk_keyword_status:$('#bulk-keyword-status').find("option:selected").val()
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg(data.msg);
                                location.reload();
                            }
                        },
                        'json'
                    );
                }
            });

        });


        // 【删除】【待审核】【关键词】
        $("#item-main-body").on('click', ".item-delete-submit", function() {
            var that = $(this);
            layer.msg('确定要"删除"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/business/keyword-todo-delete') }}",
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
        // 【批量删除】【待审核】【关键词】
        $("#item-content-body").on('click', '#bulk-delete-submit', function() {
            var $checked = [];
            $('input[name="bulk-keyword-id"]:checked').each(function() {
                $checked.push($(this).val());
            });

            layer.msg('确定要"批量删除"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/business/keyword-todo-delete-bulk') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            bulk_keyword_id:$checked
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
</script>
@endsection
