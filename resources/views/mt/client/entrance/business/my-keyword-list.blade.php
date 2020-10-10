@extends('mt.client.layout.layout')

@section('head_title','我的关键词 - 搜索引擎智能营销系统')

@section('header','我的关键词')
@section('description','搜索引擎智能营销系统')


@section('breadcrumb')
    <li><a href="{{url('/client')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">内容列表</h3>

                <div class="caption pull-right">
                    <i class="icon-pin font-blue"></i>
                    <span class="caption-subject font-blue sbold uppercase"></span>
                    <a href="{{ url('/client/business/keyword-search')}}">
                        <button type="button" onclick="" class="btn btn-success pull-right"><i class="fa fa-plus"></i> 添加关键词</button>
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
                <table class='table table-striped- table-bordered table-hover' id='datatable_ajax'>
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
                        <th>历史数据</th>
                    </tr>
                    <tr>
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
                                <option value ="默认" selected="selected">默认</option>
                                <option value ="全部">全部</option>
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
                    'url': "{{ url('/client/business/my-keyword-list') }}",
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
                        "width": "48px",
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
                        "width": "",
                        "title": "站点",
                        "data": "website",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "width": "64px",
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
                        "width": "48px",
                        "className": "text-center",
                        "title": "价格",
                        "data": "price",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return parseInt(data);
                        }
                    },
                    {
                        "width": "96px",
                        "title": "创建时间",
                        "data": "createtime",
                        "className": "text-center",
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
                        "width": "64px",
                        "className": "text-center",
                        "title": "初始排名",
                        "data": "initialranking",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "width": "64px",
                        "className": "text-center",
                        "title": "最新排名",
                        "data": "latestranking",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            if((data > 0) && (data <= 10)) return '<samll class="text-red"><b>'+data+'</b></samll>';
                            else return data;
                        }
                    },
                    {
                        "width": "64px",
                        "title": "最新消费",
                        "data": "latestconsumption",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return parseInt(data);
                        }
                    },
                    {
                        "width": "96px",
                        "className": "text-center",
                        "title": "检测时间",
                        "data": "detectiondate",
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
                        "width": "64px",
                        "title": "达标天数",
                        "data": "standarddays",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return parseInt(data);
                        }
                    },
                    {
                        "width": "64px",
                        "title": "累计消费",
                        "data": "totalconsumption",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return parseInt(data);
                        }
                    },
                    {
                        "width": "72px",
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
                        "data": "id",
                        'orderable': false,
                        render: function(value) {
                            var html =
//                                '<a class="btn btn-xs item-enable-submit" data-id="'+value+'">启用</a>'+
//                                '<a class="btn btn-xs item-disable-submit" data-id="'+value+'">禁用</a>'+
//                                '<a class="btn btn-xs item-download-qrcode-submit" data-id="'+value+'">下载二维码</a>'+
//                                '<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+
                                {{--'<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+--}}
//                                '<a class="btn btn-xs item-edit-submit" data-id="'+value+'">编辑</a>'+
//                                '<a class="btn btn-xs item-delete-submit" data-id="'+value+'" >删除</a>';
                                '<a class="btn btn-xs bg-primary item-data-detail-link" data-id="'+value+'" >历史数据</a>';
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
            {{--layer.msg("/{{config('common.org.admin.prefix')}}/item/edit?id="+that.attr('data-id'));--}}
                window.location.href = "/item/edit?id="+that.attr('data-id');
        });
        // 【数据详情】
        $("#item-main-body").on('click', ".item-data-detail-link", function() {
            var that = $(this);
            window.open("/client/business/keyword-detect-record?id="+that.attr('data-id'));
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
            ('#modal-body').modal('show');
        });

        // 【删除】
        $("#item-main-body").on('click', ".item-delete-submit", function() {
            var that = $(this);
            layer.msg('确定要删除该"产品"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
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
