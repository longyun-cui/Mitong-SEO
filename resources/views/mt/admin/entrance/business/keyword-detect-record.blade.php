@extends('mt.admin.layout.layout')

@section('head_title','关键词检测记录 - 搜索引擎智能营销系统 - 米同科技')

@section('header','关键词检测记录')
@section('description','搜索引擎智能营销系统-米同科技')


@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="callout callout-green">
                {{--<h4>关键词概览</h4>--}}
                <div>
                    <span>关键词</span>
                    <span class="text-red" style="font-size:20px;">{{ $data['keyword'] or '' }}</span>
                    <span style="margin-right:12px;"></span>

                    <span>达标</span>
                    <span class="text-red" style="font-size:24px;">{{ $data['standarddays'] or 0 }}</span>
                    <span style="margin-right:12px;">天</span>

                    <span>累计消费</span>
                    <span class="text-red" style="font-size:24px;">￥{{ intval($data['totalconsumption']) }}</span>
                    <span style="margin-right:12px;">元</span>

                    <span>首次达标时间</span>
                    <span class="text-red" style="font-size:20px;">{{ $data['firststandarddate'] or '' }}</span>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>

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
                <div class="caption">
                    <i class="icon-pin font-blue"></i>
                    <span class="caption-subject font-blue sbold uppercase"></span>
                    <a href="javascript:void(0);">
                        <button type="button" class="btn btn-success pull-right item-create-rank-show"><i class="fa fa-plus"></i> 添加记录</button>
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
                        <th>id</th>
                        <th>关键词</th>
                        <th>站点</th>
                        <th>搜索引擎</th>
                        <th>最新排名</th>
                        <th>检测时间</th>
                        <th>历史数据</th>
                    </tr>
                    <tr>
                        <td>
                            {{--<input type="checkbox" class="icheckbox_minimal" id="all_checked">--}}
                        </td>
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
                    <div class="col-md-offset-0 col-md-3 col-sm-4 col-xs-6">
                        {{--<button type="button" class="btn btn-primary"><i class="fa fa-check"></i> 提交</button>--}}
                        {{--<button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>--}}
                        <div class="input-group">
                            <span class="input-group-addon"><input type="checkbox" id="check-all"></span>
                            <input type="text" class="form-control" name="rank" id="rank" placeholder="指定排名">
                            <span class="input-group-addon" id="set-rank-submit"><i class="fa fa-check"></i>提交</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>


<div class="modal fade" id="modal-create-body">
    <div class="col-md-8 col-md-offset-2" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;">

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="box- box-info- form-container">

                    <div class="box-header with-border" style="margin:16px 0;">
                        <h3 class="box-title">添加记录</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <form action="" method="post" class="form-horizontal form-bordered" id="modal-detect-create-form">
                        <div class="box-body">

                            {{csrf_field()}}
                            <input type="hidden" name="detect-create-operate" value="detect-create-rank" readonly>
                            <input type="hidden" name="detect-create-keyword-id" value="{{ $data['id'] or '' }}" readonly>



                            {{--关键词--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">关键词</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="detect-create-keyword">{{ $data['keyword'] or '' }}</span>
                                </div>
                            </div>
                            {{--指定排名--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">选择日期</label>
                                <div class="col-md-8 ">
                                    <input type="text" class="form-control form-filter form_datetime" name="detect-create-date" />
                                </div>
                            </div>
                            {{--指定排名--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">指定排名</label>
                                <div class="col-md-8 ">
                                    <input type="text" class="form-control" name="detect-create-rank" placeholder="指定排名" value="">
                                </div>
                            </div>
                            {{--备注--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">备注</label>
                                <div class="col-md-8 ">
                                    {{--<input type="text" class="form-control" name="description" placeholder="描述" value="">--}}
                                    <textarea class="form-control" name="detect-create-description" rows="3" cols="100%"></textarea>
                                </div>
                            </div>


                        </div>
                    </form>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <button type="button" class="btn btn-success" id="item-detect-create-submit"><i class="fa fa-check"></i> 提交</button>
                                <button type="button" class="btn btn-default" id="item-detect-create-cancel">取消</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET-->
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-set-body">
    <div class="col-md-8 col-md-offset-2" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;">

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="box- box-info- form-container">

                    <div class="box-header with-border" style="margin:16px 0;">
                        <h3 class="box-title">指定排名</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <form action="" method="post" class="form-horizontal form-bordered" id="modal-detect-set-form">
                        <div class="box-body">

                            {{csrf_field()}}
                            <input type="hidden" name="detect-set-operate" value="detect-set-rank" readonly>
                            <input type="hidden" name="detect-set-id" value="0" readonly>
                            <input type="hidden" name="detect-set-date" value="0" readonly>

                            {{--类别--}}


                            {{--关键词--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">关键词</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="detect-set-keyword"></span>
                                </div>
                            </div>
                            {{--检测ID--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">检测ID</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="detect-set-id"></span>
                                </div>
                            </div>
                            {{--指定日期--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">指定日期</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="detect-set-date"></span>
                                </div>
                            </div>
                            {{--原排名--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">原排名</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="detect-set-original-rank"></span>
                                </div>
                            </div>
                            {{--指定排名--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">指定排名</label>
                                <div class="col-md-8 ">
                                    <input type="text" class="form-control" name="detect-set-rank" placeholder="指定排名" value="">
                                </div>
                            </div>
                            {{--备注--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">备注</label>
                                <div class="col-md-8 ">
                                    {{--<input type="text" class="form-control" name="description" placeholder="描述" value="">--}}
                                    <textarea class="form-control" name="detect-set-description" rows="3" cols="100%"></textarea>
                                </div>
                            </div>


                        </div>
                    </form>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <button type="button" class="btn btn-success" id="item-detect-set-submit"><i class="fa fa-check"></i> 提交</button>
                                <button type="button" class="btn btn-default" id="item-detect-set-cancel">取消</button>
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
                "bAutoWidth": false,
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    'url': "{{ url('/admin/business/keyword-detect-record?id='.request('id')) }}",
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.searchengine = $('select[name="searchengine"]').val();
                        d.keyword = $('input[name="keyword"]').val();
                        d.website = $('input[name="website"]').val();
                        d.keywordstatus = $('select[name="keywordstatus"]').val();
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
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return '<label><input type="checkbox" name="detect-record" class="minimal" value="'+data+'"></label>';
                        }
                    },
                    {
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "data": "keyword",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "data": "website",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "data": "searchengine",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            if(data = "baidu") return '百度PC';
                            else if(data = "baidu_mobile") return '百度移动';
                            else if(data = "sougou") return '搜狗';
                            else if(data = "360") return '360';
                            else if(data = "shenma") return '神马';
                            else return data;
                        }
                    },
                    {
                        "data": "rank",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            if((data > 0) && (data <= 10)) return '<samll class="text-red">'+data+'</samll>';
                            else return data;
                        }
                    },
                    {
                        "data": "detect_time",
                        'orderable': false,
                        render: function(data, type, row, meta) {
//                            return data;
//                            newDate = new Date(data);
//                            return newDate.toLocaleDateString('chinese',{hour12:false});
//                            var $date = row.detect_time.trim().split(/\s+/)[0];
                            var $date = row.createtime.trim().split(" ")[0];
                            return $date;
                        }
                    },
                    {
                        'data': 'id',
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var $date = row.createtime.trim().split(" ")[0];
                            var html =
//                                '<a class="btn btn-xs item-enable-submit" data-id="'+value+'">启用</a>'+
//                                '<a class="btn btn-xs item-disable-submit" data-id="'+value+'">禁用</a>'+
//                                '<a class="btn btn-xs item-download-qrcode-submit" data-id="'+value+'">下载二维码</a>'+
//                                '<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+
                                {{--'<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+--}}
//                                '<a class="btn btn-xs item-edit-submit" data-id="'+value+'">编辑</a>'+
                                '<a class="btn btn-xs item-set-rank-show" data-id="'+data+
                                    '" data-name="'+row.keyword+'" data-rank="'+row.rank+'" data-date="'+$date+
                                '">指定排名</a>';
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

//                    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
//                        checkboxClass: 'icheckbox_minimal-blue',
//                        radioClass   : 'iradio_minimal-blue'
//                    });
                },
                "language": { url: '/common/dataTableI18n' },
            });


            dt.on('click', '.filter-submit', function () {
                ajax_datatable.ajax.reload();
            });

            dt.on('click', '.filter-cancel', function () {
                $('textarea.form-filter, input.form-filter, select.form-filter', dt).each(function () {
                    $(this).val("");
                });

//                $('select.form-filter').selectpicker('refresh');
                $('select.form-filter option').attr("selected",false);
                $('select.form-filter').find('option:eq(0)').attr('selected', true);

                ajax_datatable.ajax.reload();
            });


//            dt.on('click', '#all_checked', function () {
////                layer.msg(this.checked);
//                $('input[name="detect-record"]').prop('checked',this.checked);//checked为true时为默认显示的状态
//            });


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

        $(".form_datetime").datepicker({
            language: 'zh-CN',
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });

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




        // 全选or反选
        $("#item-content-body").on('click', '#check-all', function () {
            $('input[name="detect-record"]').prop('checked',this.checked);//checked为true时为默认显示的状态
        });

        // 批量修改排名
        $("#item-content-body").on('click', '#set-rank-submit', function() {
            var $checked = [];
            $('input[name="detect-record"]:checked').each(function() {
                $checked.push($(this).val());
            });

            $.post(
                "{{ url('/admin/business/keyword-detect-set-rank') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
//                    detect_record:$('input[name="detect-record"]').serialize(),
                    detect_record:$checked,
                    rank:$("#rank").val()
                },
                function(data){
                    if(!data.success) layer.msg(data.msg);
                    else location.reload();
                },
                'json'
            );
        });


        // 显示【添加排名】
        $("#item-content-body").on('click', ".item-create-rank-show", function() {
            var that = $(this);
            $('#modal-create-body').modal('show');
        });
        // 【添加排名】提交
        $("#modal-create-body").on('click', "#item-detect-create-submit", function() {
            var that = $(this);
            layer.msg('确定"添加"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/business/keyword-detect-create-rank') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:$('input[name="detect-create-operate"]').val(),
                            keyword_id:$('input[name="detect-create-keyword-id"]').val(),
                            detect_date:$('input[name="detect-create-date"]').val(),
                            detect_rank:$('input[name="detect-create-rank"]').val(),
                            detect_description:$('input[name="detect-create-description"]').val()
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




        // 显示【修改排名】
        $("#item-main-body").on('click', ".item-set-rank-show", function() {
            var that = $(this);
            $('input[name=detect-set-id]').val(that.attr('data-id'));
            $('input[name=detect-set-date]').val(that.attr('data-date'));
            $('.detect-set-keyword').html(that.attr('data-name'));
            $('.detect-set-id').html(that.attr('data-id'));
            $('.detect-set-date').html(that.attr('data-date'));
            $('.detect-set-original-rank').html(that.attr('data-rank'));
            $('#modal-set-body').modal('show');
        });
        // 【修改排名】提交
        $("#modal-set-body").on('click', "#item-detect-set-submit", function() {
            var that = $(this);
            layer.msg('确定"提交"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/business/keyword-detect-set-rank') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:$('input[name="detect-set-operate"]').val(),
                            detect_id:$('input[name="detect-set-id"]').val(),
                            detect_date:$('input[name="detect-set-date"]').val(),
                            detect_rank:$('input[name="detect-set-rank"]').val(),
                            detect_description:$('input[name="detect-set-description"]').val()
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
        // 【修改排名】取消
        $("#modal-set-body").on('click', "#item-detect-set-cancel", function() {
            var that = $(this);
            $('input[name=detect-set-id]').val(0);
            $('.detect-set-keyword').html('');
            $('.detect-set-id').html(0);
            $('.detect-set-date').html('');
            $('.detect-set-original-rank').html('');
            $('#modal-set-body').modal('hide');
        });




        // 【删除】
        $("#item-main-body").on('click', ".item-delete-submit", function() {
            var that = $(this);
            layer.msg('确定要删除该"产品"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
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
