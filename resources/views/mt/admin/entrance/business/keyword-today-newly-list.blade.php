@extends('mt.admin.layout.layout')

@section('head_title','今日新增上词 - 搜索引擎智能营销系统 - 米同科技')

@section('header','今日新增上词')
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

            <div class="box-body datatable-body item-main-body" id="item-main-body">
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
                        <th></th>
                        <th>历史数据</th>
                    </tr>
                    <tr class="_none">
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
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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


<div class="modal fade" id="modal-data-detect-body">
    <div class="col-md-8 col-md-offset-2" id="edit-ctn-" style="background:#fff;">
        <div class="box box-info- form-container" id="item-content-body">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">内容列表</h3>
                <div class="caption">
                    <i class="icon-pin font-blue"></i>
                    <span class="caption-subject font-blue sbold uppercase"></span>
                    <a href="javascript:void(0);">
                        <button type="button" class="btn btn-success pull-right item-create-rank-show"><i class="fa fa-plus"></i> 添加记录</button>
                    </a>
                </div>
            </div>

            <div class="box-body datatable-body item-main-body" id="item-main-body">
                <!-- datatable start -->
                <table class='table table-striped table-bordered' id='datatable_ajax_inner'>
                    <thead>
                    <tr role='row' class='heading'>
                        <th>序号</th>
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
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <select name="inner_rank" class="form-control form-filter">
                                <option value ="0">全部</option>
                                <option value ="1">已达标</option>
                            </select></td>
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
                    <div class="col-md-offset-0 col-md-4 col-sm-8 col-xs-12">
                        {{--<button type="button" class="btn btn-primary"><i class="fa fa-check"></i> 提交</button>--}}
                        {{--<button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>--}}
                        <div class="input-group">
                            <span class="input-group-addon"><input type="checkbox" id="check-all"></span>
                            <input type="text" class="form-control" name="bulk-detect-rank" id="bulk-detect-rank" placeholder="指定排名">
                            <span class="input-group-addon btn btn-default" id="set-rank-bulk-submit"><i class="fa fa-check"></i>提交</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>


<div class="modal fade" id="modal-create-body">
    <div class="col-md-4 col-md-offset-4" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;">

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
                            <input type="hidden" name="detect-create-keyword-id" value="0" readonly>



                            {{--关键词ID--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">关键词ID</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="detect-create-keyword-id"></span>
                                </div>
                            </div>
                            {{--关键词--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">关键词</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="detect-create-keyword"></span>
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
                                    <input type="text" class="form-control detect-create-rank" name="detect-create-rank" placeholder="指定排名" value="">
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
    <div class="col-md-4 col-md-offset-4" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;">

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
                                    <input type="text" class="form-control detect-set-rank" name="detect-set-rank" placeholder="指定排名" value="">
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
//                "aLengthMenu": [[20, 50, 200], ["20", "50", "200"]],
                "aLengthMenu": [[-1], ["全部"]],
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    'url': "{{ url('/admin/business/keyword-today-newly') }}",
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.keyword = $('input[name="keyword"]').val();
                        d.website = $('input[name="website"]').val();
                        d.searchengine = $('select[name="searchengine"]').val();
                        d.latest_ranking = $('select[name="latest_ranking"]').val();
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
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "className": "text-left",
                        "width": "160px",
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
                        "width": "64px",
                        "title": "搜索<br>引擎",
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
                        "className": "",
                        "width": "48px",
                        "title": "价格",
                        "data": "price",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "width": "40px",
                        "title": "7天前",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var $html = "";
                            if(row.detects[7])
                            {
                                var $detect = row.detects[7];
                                var $date = $detect.detect_time.trim().split(" ")[0];
                                var $rank = $detect.rank;
                                if(($rank > 0) && ($rank <= 10)) $rank = '<samll class="text-red">'+$rank+'</samll>';

                                $html = '<a class="btn btn-xs item-set-rank-show" '+
                                    ' data-id="'+$detect.id+'" data-name="'+$detect.keyword+'" data-rank="'+$detect.rank+'" data-date="'+$date+
                                    '">'+$rank+'</a>';
                            }
                            return $html;
                        }
                    },
                    {
                        "width": "40px",
                        "title": "6天前",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var $html = "";
                            if(row.detects[6])
                            {
                                var $detect = row.detects[6];
                                var $date = $detect.detect_time.trim().split(" ")[0];
                                var $rank = $detect.rank;
                                if(($rank > 0) && ($rank <= 10)) $rank = '<samll class="text-red">'+$rank+'</samll>';

                                $html = '<a class="btn btn-xs item-set-rank-show" '+
                                    ' data-id="'+$detect.id+'" data-name="'+$detect.keyword+'" data-rank="'+$detect.rank+'" data-date="'+$date+
                                    '">'+$rank+'</a>';
                            }
                            return $html;
                        }
                    },
                    {
                        "width": "40px",
                        "title": "5天前",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var $html = "";
                            if(row.detects[5])
                            {
                                var $detect = row.detects[5];
                                var $date = $detect.detect_time.trim().split(" ")[0];
                                var $rank = $detect.rank;
                                if(($rank > 0) && ($rank <= 10)) $rank = '<samll class="text-red">'+$rank+'</samll>';

                                $html = '<a class="btn btn-xs item-set-rank-show" '+
                                    ' data-id="'+$detect.id+'" data-name="'+$detect.keyword+'" data-rank="'+$detect.rank+'" data-date="'+$date+
                                    '">'+$rank+'</a>';
                            }
                            return $html;
                        }
                    },
                    {
                        "width": "40px",
                        "title": "4天前",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var $html = "";
                            if(row.detects[4])
                            {
                                var $detect = row.detects[4];
                                var $date = $detect.detect_time.trim().split(" ")[0];
                                var $rank = $detect.rank;
                                if(($rank > 0) && ($rank <= 10)) $rank = '<samll class="text-red">'+$rank+'</samll>';

                                $html = '<a class="btn btn-xs item-set-rank-show" '+
                                    ' data-id="'+$detect.id+'" data-name="'+$detect.keyword+'" data-rank="'+$detect.rank+'" data-date="'+$date+
                                    '">'+$rank+'</a>';
                            }
                            return $html;
                        }
                    },
                    {
                        "width": "40px",
                        "title": "3天前",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var $html = "";
                            if(row.detects[3])
                            {
                                var $detect = row.detects[3];
                                var $date = $detect.detect_time.trim().split(" ")[0];
                                var $rank = $detect.rank;
                                if(($rank > 0) && ($rank <= 10)) $rank = '<samll class="text-red">'+$rank+'</samll>';

                                $html = '<a class="btn btn-xs item-set-rank-show" '+
                                    ' data-id="'+$detect.id+'" data-name="'+$detect.keyword+'" data-rank="'+$detect.rank+'" data-date="'+$date+
                                    '">'+$rank+'</a>';
                            }
                            return $html;
                        }
                    },
                    {
                        "width": "40px",
                        "title": "前天",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var $html = "";
                            if(row.detects[2])
                            {
                                var $detect = row.detects[2];
                                var $date = $detect.detect_time.trim().split(" ")[0];
                                var $rank = $detect.rank;
                                if(($rank > 0) && ($rank <= 10)) $rank = '<samll class="text-red">'+$rank+'</samll>';

                                $html = '<a class="btn btn-xs item-set-rank-show" '+
                                    ' data-id="'+$detect.id+'" data-name="'+$detect.keyword+'" data-rank="'+$detect.rank+'" data-date="'+$date+
                                    '">'+$rank+'</a>';
                            }
                            return $html;
                        }
                    },
                    {
                        "width": "40px",
                        "title": "昨天",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var $html = "";
                            if(row.detects[1])
                            {
                                var $detect = row.detects[2];
                                var $date = $detect.detect_time.trim().split(" ")[0];
                                var $rank = $detect.rank;
                                if(($rank > 0) && ($rank <= 10)) $rank = '<samll class="text-red">'+$rank+'</samll>';

                                $html = '<a class="btn btn-xs item-set-rank-show" '+
                                    ' data-id="'+$detect.id+'" data-name="'+$detect.keyword+'" data-rank="'+$detect.rank+'" data-date="'+$date+
                                    '">'+$rank+'</a>';
                            }
                            return $html;
                        }
                    },
                    {
                        "width": "40px",
                        "title": "最新 排名",
                        "data": "latestranking",
                        'orderable': false,
                        render: function(data, type, row, meta) {
//                            if((data > 0) && (data <= 10)) return '<samll class="text-red">'+data+'</samll>';
//                            else return data;
                            var $html = "";
                            if(row.detects[0])
                            {
                                var $detect = row.detects[0];
                                var $date = $detect.detect_time.trim().split(" ")[0];
                                var $rank = $detect.rank;
                                if(($rank > 0) && ($rank <= 10)) $rank = '<samll class="text-red">'+$rank+'</samll>';

                                $html = '<a class="btn btn-xs item-set-rank-show" '+
                                    ' data-id="'+$detect.id+'" data-name="'+$detect.keyword+'" data-rank="'+$detect.rank+'" data-date="'+$date+
                                    '">'+$rank+'</a>';
                            }
                            return $html;
                        }
                    },
                    {
                        "width": "72px",
                        "title": "检测日期",
                        "data": "detectiondate",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            if(!data) return '--';
//                            return data;
                            var $date = new Date(data);
                            var $year = $date.getFullYear();
                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
                            var $day = ('00'+($date.getDate())).slice(-2);
                            return $year+'-'+$month+'-'+$day;
                        }
                    },
                    {
                        "width": "",
                        "title": "操作",
                        "data": 'id',
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var html =
//                                '<a class="btn btn-xs item-enable-submit" data-id="'+data+'">启用</a>'+
//                                '<a class="btn btn-xs item-disable-submit" data-id="'+data+'">禁用</a>'+
//                                '<a class="btn btn-xs item-download-qrcode-submit" data-id="'+data+'">下载二维码</a>'+
//                                '<a class="btn btn-xs item-statistics-submit" data-id="'+data+'">流量统计</a>'+
                                {{--'<a class="btn btn-xs" href="/item/edit?id='+data+'">编辑</a>'+--}}
//                                '<a class="btn btn-xs item-edit-submit" data-id="'+data+'">编辑</a>'+
//                                '<a class="btn btn-xs item-delete-submit" data-id="'+data+'" >删除</a>';
//                                '<a class="btn btn-xs bg-primary item-data-detail-link" data-id="'+data+'" >数据详情</a>'+
                                '<a class="btn btn-xs bg-primary item-data-detail-show" data-id="'+data+'" data-keyword="'+row.keyword+'">数据详情</a>'+
                                '';
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
    var TableDatatablesAjax_inner = function ($id) {
        var datatableAjax_inner = function ($id) {

            var dt = $('#datatable_ajax_inner');
            dt.DataTable().destroy();
            var ajax_datatable_inner = dt.DataTable({
                "retrieve": true,
                "destroy": true,
//                "aLengthMenu": [[20, 50, 200, 500, -1], ["20", "50", "200", "500", "全部"]],
                "aLengthMenu": [[20, 50, 200], ["20", "50", "200"]],
                "bAutoWidth": false,
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    'url': "/admin/business/keyword-detect-record?id="+$id,
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.searchengine = $('select[name="searchengine"]').val();
                        d.keyword = $('input[name="keyword"]').val();
                        d.website = $('input[name="website"]').val();
                        d.keywordstatus = $('select[name="keywordstatus"]').val();
                        d.rank = $('select[name="inner_rank"]').val();
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
                        "title": "选择",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return '<label><input type="checkbox" name="bulk-detect-record-id" class="minimal" value="'+data+'"></label>';
                        }
                    },
                    {
                        "title": "ID",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "title": "扣费ID",
                        "data": "expense_id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "title": "关键词",
                        "data": "keyword",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "title": "站点",
                        "data": "website",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
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
                        "title": "排名",
                        "data": "rank",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            if((data > 0) && (data <= 10)) return '<samll class="text-red">'+data+'</samll>';
                            else return data;
                        }
                    },
                    {
                        "title": "检测时间",
                        "data": "detect_time",
                        'orderable': false,
                        render: function(data, type, row, meta) {
//                            return data;
//                            newDate = new Date(data);
//                            return newDate.toLocaleDateString('chinese',{hour12:false});
//                            var $date = row.detect_time.trim().split(/\s+/)[0];
                            var $date = row.detect_time.trim().split(" ")[0];
                            return $date;
                        }
                    },
                    {
                        "title": "操作",
                        'data': 'id',
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            var $date = row.detect_time.trim().split(" ")[0];
                            var html =
//                                '<a class="btn btn-xs item-enable-submit" data-id="'+value+'">启用</a>'+
//                                '<a class="btn btn-xs item-disable-submit" data-id="'+value+'">禁用</a>'+
//                                '<a class="btn btn-xs item-download-qrcode-submit" data-id="'+value+'">下载二维码</a>'+
//                                '<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+
                                    {{--'<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+--}}
                                //                                '<a class="btn btn-xs item-edit-submit" data-id="'+value+'">编辑</a>'+
                                '<a class="btn btn-xs item-set-rank-show" data-id="'+data+
                                '" data-name="'+row.keyword+'" data-rank="'+row.rank+'" data-date="'+$date+
                                '">指定排名</a>'+
                                '';
                            return html;
                        }
                    }
                ],
                "drawCallback": function (settings) {

                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
                    this.api().column(0).nodes().each(function(cell, i) {
                        cell.innerHTML =  startIndex + i + 1;
                    });

                    ajax_datatable_inner.$('.tooltips').tooltip({placement: 'top', html: true});
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
                ajax_datatable_inner.ajax.reload();
            });

            dt.on('click', '.filter-cancel', function () {
                $('textarea.form-filter, input.form-filter, select.form-filter', dt).each(function () {
                    $(this).val("");
                });

//                $('select.form-filter').selectpicker('refresh');
                $('select.form-filter option').attr("selected",false);
                $('select.form-filter').find('option:eq(0)').attr('selected', true);

                ajax_datatable_inner.ajax.reload();
            });


//            dt.on('click', '#all_checked', function () {
////                layer.msg(this.checked);
//                $('input[name="detect-record"]').prop('checked',this.checked);//checked为true时为默认显示的状态
//            });


        };
        return {
            init: datatableAjax_inner
        }
    }();
    //    $(function () {
    //        TableDatatablesAjax_inner.init();
    //    });
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
        $(".item-main-body").on('click', ".item-download-qrcode-submit", function() {
            var that = $(this);
            window.open("/download-qrcode?sort=org-item&id="+that.attr('data-id'));
        });

        // 【数据分析】
        $(".item-main-body").on('click', ".item-statistics-submit", function() {
            var that = $(this);
            window.open("/statistics/item?id="+that.attr('data-id'));
        });

        // 【编辑】
        $(".item-main-body").on('click', ".item-edit-submit", function() {
            var that = $(this);
            {{--layer.msg("/item/edit?id="+that.attr('data-id'));--}}
                window.location.href = "/item/edit?id="+that.attr('data-id');
        });

        // 【数据详情】
        $(".item-main-body").on('click', ".item-data-detail-link", function() {
            var that = $(this);
            window.open("/admin/business/keyword-detect-record?id="+that.attr('data-id'));
        });

        // 【数据详情】
        $(".item-main-body").on('click', ".item-data-detail-show", function() {
            var that = $(this);
            var $id = that.attr("data-id");
            var $keyword = that.attr("data-keyword");

            $('#set-rank-bulk-submit').attr('data-keyword-id',$id);
            $('input[name="detect-create-keyword-id"]').val($id);
            $('.detect-create-keyword-id').html($id);
            $('.detect-create-keyword').html($keyword);

            TableDatatablesAjax_inner.init($id);

            $('#modal-data-detect-body').modal('show');
        });

        // 【删除】
        $(".item-main-body").on('click', ".item-delete-submit", function() {
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
        $(".item-main-body").on('click', ".item-enable-submit", function() {
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
        $(".item-main-body").on('click', ".item-disable-submit", function() {
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
        $(".item-main-body").on('click', ".item-download-qrcode-submit", function() {
            var that = $(this);
            window.open("/download-qrcode?sort=org-item&id="+that.attr('data-id'));
        });

        // 【数据分析】
        $(".item-main-body").on('click', ".item-statistics-submit", function() {
            var that = $(this);
            window.open("/statistics/item?id="+that.attr('data-id'));
        });

        // 【编辑】
        $(".item-main-body").on('click', ".item-edit-submit", function() {
            var that = $(this);
            {{--layer.msg("/item/edit?id="+that.attr('data-id'));--}}
                window.location.href = "/item/edit?id="+that.attr('data-id');
        });





        // 【批量选择】全选or反选
        $("#item-content-body").on('click', '#check-all', function () {
            $('input[name="bulk-detect-record-id"]').prop('checked',this.checked);//checked为true时为默认显示的状态
        });

        // 【批量修改】【排名】
        $("#item-content-body").on('click', '#set-rank-bulk-submit', function() {
            var $checked = [];
            $('input[name="bulk-detect-record-id"]:checked').each(function() {
                $checked.push($(this).val());
            });

            $.post(
                "{{ url('/admin/business/keyword-detect-set-rank-bulk') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: "detect-set-rank-bulk",
                    bulk_detect_id: $checked,
                    bulk_detect_rank:$("#bulk-detect-rank").val()
                },
                function(data){
                    if(!data.success) layer.msg(data.msg);
//                    else location.reload();
                    else
                    {
                        var $keyword_id = $("#set-rank-bulk-submit").attr("data-keyword-id");
                        TableDatatablesAjax_inner.init($keyword_id);
                    }
                },
                'json'
            );
        });


        // 显示【添加排名】
        $("#item-content-body").on('click', ".item-create-rank-show", function() {
            var that = $(this);

            $('#modal-create-body').modal({show: true,backdrop: 'static'});
            $('.modal-backdrop').each(function() {
                $(this).attr('id', 'id_' + Math.random());
            });
        });
        // 【添加排名】取消
        $("#modal-create-body").on('click', "#item-detect-create-cancel", function() {
            var that = $(this);
            $('input[name=detect-set-id]').val(0);
            $('.detect-set-keyword').html('');
            $('.detect-set-id').html(0);
            $('.detect-set-date').html('');
            $('.detect-set-original-rank').html('');
            $('input[name=detect-set-rank]').val('');

            $('#modal-create-body').modal('hide');
            $("#modal-create-body").on("hidden.bs.modal", function () {
                $("body").addClass("modal-open");
            });
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
//                            else location.reload();
                            else
                            {
                                layer.close(index);
                                $('#modal-create-body').modal('hide');
                                $("#modal-create-body").on("hidden.bs.modal", function () {
                                    $("body").addClass("modal-open");
                                });

                                var $keyword_id = $("#set-rank-bulk-submit").attr("data-keyword-id");
                                TableDatatablesAjax_inner.init($keyword_id);
                            }
                        },
                        'json'
                    );
                }
            });
        });




        // 显示【修改排名】
        $(".item-main-body").on('click', ".item-set-rank-show", function() {
            var that = $(this);
            $('input[name=detect-set-id]').val(that.attr('data-id'));
            $('input[name=detect-set-date]').val(that.attr('data-date'));
            $('.detect-set-keyword').html(that.attr('data-name'));
            $('.detect-set-id').html(that.attr('data-id'));
            $('.detect-set-date').html(that.attr('data-date'));
            $('.detect-set-original-rank').html(that.attr('data-rank'));
            $('input[name=detect-set-rank]').val('');

            $('#modal-set-body').modal({show: true,backdrop: 'static'});
            $('.modal-backdrop').each(function() {
                $(this).attr('id', 'id_' + Math.random());
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
            $('input[name=detect-set-rank]').val('');

            $('#modal-set-body').modal('hide');
            $("#modal-set-body").on("hidden.bs.modal", function () {
                $("body").addClass("modal-open");
            });
        });
        // 【修改排名】提交
        $("#modal-set-body").on('click', "#item-detect-set-submit", function() {
            var that = $(this);
//            layer.msg('确定"提交"么？', {
//                time: 0
//                ,btn: ['确定', '取消']
//                ,yes: function(index){
//                }
//            });
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
//                            else location.reload();
                    else
                    {
//                        layer.close(index);
                        $('#modal-set-body').modal('hide');
                        $("#modal-set-body").on("hidden.bs.modal", function () {
                            $("body").addClass("modal-open");
                        });

                        var $keyword_id = $("#set-rank-bulk-submit").attr("data-keyword-id");
                        TableDatatablesAjax_inner.init($keyword_id);

                        var $detect_id = $('input[name="detect-set-id"]').val();
                        var $rank = $('input[name="detect-set-rank"]').val();
                        var $html = $rank;
                        if(($rank > 0) && ($rank <= 10)) $html = '<samll class="text-red">'+$rank+'</samll>';

                        $('.item-set-rank-show[data-id='+$detect_id+']').html($html);
                    }
                },
                'json'
            );
        });

    });
</script>
@endsection
