@extends('mt.admin.layout.layout')

@section('head_title','关键词列表 - 搜索引擎智能营销系统 - 米同科技')

@section('header','关键词列表')
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
                <h4>今日概览</h4>
                <div>
                    <span style="margin-right:12px;">
                        优化关键词 <span class="text-red" style="font-size:24px;">{{ $data['keyword_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        检测 <span class="text-red font-24px">{{ $data['keyword_detect_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        达标 <span class="text-red font-24px">{{ $data['keyword_standard_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        达标消费 <span class="text-red font-24px">{{ $data['keyword_standard_fund_sum'] or 0 }}</span> 元
                    </span>
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
                        <th>序号</th>
                        <th>ID</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
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
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <select name="keywordstatus" class="form-control form-filter">
                                <option value ="0">全部</option>
                                <option value ="优化中">优化中</option>
                                <option value ="待审核">待审核</option>
                                <option value ="合作停">合作停</option>
                                <option value ="已删除">已删除</option>
                            </select>
                        </td>
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
    <div class="col-md-8 col-md-offset-2" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;"></div>
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
                    'url': "{{ url('/admin/business/keyword-list') }}",
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.keyword = $('input[name="keyword"]').val();
                        d.website = $('input[name="website"]').val();
                        d.searchengine = $('select[name="searchengine"]').val();
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
                        "width": "32px",
                        "title": "序号",
                        "data": null,
                        "targets": 0,
                        'orderable': false
                    },
                    {
                        "width": "48px",
                        "title": "ID",
                        "data": "id",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "className": "text-left",
                        "width": "",
                        "title": "客户",
                        "data": "createuserid",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return row.creator == null ? '未知' : '<a target="_blank" href="/admin/user/client?id='+row.creator.id+'">'+row.creator.username+'</a>';
                        }
                    },
                    {
                        "className": "text-left",
                        "width": "",
                        "title": "关键词",
                        "data": "keyword",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            if(row.searchengine == "baidu")
                            {
                                return '<a target="_blank" href="http://www.baidu.com/#ie=UTF-8&wd='+data+'">'+data+'</a>';
                            }
                            else if(row.searchengine == "baidu_mobile")
                            {
                                return '<a target="_blank" href="https://m.baidu.com/ssid=fd5379616e677a696c676c8223/from=1012971h/s?&ie=utf-8&word='+data+'">'+data+'</a>';
                            }
                            else if(row.searchengine == "sougou")
                            {
                                return '<a target="_blank" href="https://www.sogou.com/web?ie=utf8&query='+data+'">'+data+'</a>';
                            }
                            else if(row.searchengine == "360")
                            {
                                return '<a target="_blank" href="https://www.so.com/s?ie=utf-8&q='+data+'">'+data+'</a>';
                            }
                            else if(row.searchengine == "shenma")
                            {
                                return '<a target="_blank" href="http://www.baidu.com/#ie=UTF-8&wd='+data+'">'+data+'</a>';
                            }
                            else return data;
                        }
                    },
                    {
                        "className": "text-left",
                        "width": "72px",
                        "title": "站点",
                        "data": "website",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "width": "72px",
                        "title": "搜索引擎",
                        "data": "searchengine",
                        'orderable': true,
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
                        "width": "72px",
                        "title": "创建时间",
                        "data": "createtime",
                        'orderable': false,
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
                        "width": "48px",
                        "title": "价格",
                        "data": "price",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            return '<span class="text-blue">'+parseInt(data)+'</span>';
                        }
                    },
                    {
                        "width": "64px",
                        "title": "初始排名",
                        "data": "initialranking",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "width": "64px",
                        "title": "最新排名",
                        "data": "latestranking",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            var $gif = '';
                            if(data < row.initialranking)
                            {
                                $gif = '<img src="/seo/img/up.gif" style="vertical-align:middle;float:right;">';
                            }
                            if((data > 0) && (data <= 10)) return '<samll class="text-red">'+data+'</samll>'+$gif;
                            else return data+$gif;
                        }
                    },
                    {
                        "width": "56px",
                        "title": "最新消费",
                        "data": "latestconsumption",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            if(parseInt(data) > 0) return '<span class="text-blue">'+parseInt(data)+'</span>';
                            else return parseInt(data);
                        }
                    },
                    {
                        "width": "56px",
                        "title": "达标天数",
                        "data": "standarddays",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            if(parseInt(data) > 0) return '<span class="text-blue">'+parseInt(data).toLocaleString()+'</span>';
                            else return parseInt(data);
                        }
                    },
                    {
                        "width": "56px",
                        "title": "累计消费",
                        "data": "totalconsumption",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            if(parseInt(data) > 0) return '<span class="text-blue">'+parseInt(data).toLocaleString()+'</span>';
                            else return parseInt(data);
                        }
                    },
                    {
                        "width": "72px",
                        "title": "检测时间",
                        "data": "detectiondate",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            if(!data) return '--';
//                            return data;
//                            newDate = new Date(data);
//                            return newDate.toLocaleDateString('chinese',{hour12:false});
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
                        "data": "keywordstatus",
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
                            var html =
                                    {{--'<a class="btn btn-xs item-enable-submit" data-id="'+data+'">启用</a>'+--}}
                                    {{--'<a class="btn btn-xs item-disable-submit" data-id="'+data+'">禁用</a>'+--}}
                                    {{--'<a class="btn btn-xs item-download-qrcode-submit" data-id="'+data+'">下载二维码</a>'+--}}
                                    {{--'<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+--}}
                                    {{--'<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+--}}
                                    {{--'<a class="btn btn-xs item-edit-submit" data-id="'+value+'">编辑</a>'+--}}
                                    '<a class="btn btn-xs bg-navy item-stop-submit" data-id="'+data+'" >合作停</a>'+
                                    '<a class="btn btn-xs bg-navy item-delete-submit" data-id="'+data+'" >删除</a>'+
                                    '<a class="btn btn-xs bg-primary item-data-detail-link" data-id="'+data+'" >数据详情</a>'+
                                    '<a class="btn btn-xs bg-olive item-download-link" data-id="'+data+'" >下载</a>'+
                                    ''
                            return html;
                        }
                    }
                ],
                "drawCallback": function (settings) {

                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
                    this.api().column(0).nodes().each(function(cell, i) {
                        cell.innerHTML =  startIndex + i + 1;
                    });

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
                $('textarea.form-filter, input.form-filter, select.form-filter', dt).each(function () {
                    $(this).val("");
                });

//                $('select.form-filter').selectpicker('refresh');
                $('select.form-filter option').attr("selected",false);
                $('select.form-filter').find('option:eq(0)').attr('selected', true);

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
            window.location.href = "/item/edit?id="+that.attr('data-id');
        });


        // 【数据详情】
        $("#item-main-body").on('click', ".item-data-detail-link", function() {
            var that = $(this);
            window.open("/admin/business/keyword-detect-record?id="+that.attr('data-id'));
        });

        // 【数据详情】
        $("#item-main-body").on('click', ".item-data-detail-show", function() {
            var that = $(this);
            $.post(
                "{{ url('/item/delete') }}",
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
            $('#modal-body').modal('show');
        });




        // 【删除】
        $("#item-main-body").on('click', ".item-delete-submit", function() {
            var that = $(this);
            layer.msg('确定要"删除"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/business/keyword-delete') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"delete-keyword",
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
                        "{{ url('/admin/business/keyword-stop') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"stop-keyword",
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


        // 【下载】
        $("#item-main-body").on('click', ".item-download-link", function() {
            var that = $(this);
            layer.msg('确定要"下载"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    layer.close(index);
                    window.open("/admin/business/download/keyword-detect?id="+that.attr('data-id'));
                }
            });
        });
        // 【下载】
        $("#item-main-body").on('click', ".item-download-submit", function() {
            var that = $(this);
            layer.msg('确定要"下载"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/business/download/keyword-detect') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"download-keyword-detect",
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
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
