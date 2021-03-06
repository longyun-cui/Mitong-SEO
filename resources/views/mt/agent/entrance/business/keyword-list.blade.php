@extends('mt.agent.layout.layout')

@section('head_title','关键词列表 - 搜索引擎智能营销系统 - 米同科技')

@section('header','关键词列表')
@section('description','搜索引擎智能营销系统-米同科技')


@section('breadcrumb')
    <li><a href="{{url('/agent')}}"><i class="fa fa-dashboard"></i>首页</a></li>
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
                        优化关键词 <span class="text-red font-20px">{{ $data['keyword_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        检测 <span class="text-red font-20px">{{ $data['keyword_detect_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        达标 <span class="text-red font-20px">{{ $data['keyword_standard_count'] or 0 }}</span> 个
                    </span>

                    <span style="margin-right:12px;">
                        上次率 <span class="text-red font-20px">{{ $data['keyword_standard_rate'] or 0 }}</span>
                    </span>

                    <span style="margin-right:12px;">
                        达标消费 <span class="text-red font-20px">{{ $data['keyword_standard_fund_sum'] or 0 }}</span> 元
                    </span>

                    <span style="margin-right:12px;">
                        <a class="text-green font-16px" href="/agent/business/download/keyword-today"><button>下载今日关键词</button></a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


{{--关键词列表--}}
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info datatable-body">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">内容列表</h3>
                <div class="caption pull-right">
                    <i class="icon-pin font-blue"></i>
                    <span class="caption-subject font-blue sbold uppercase"></span>
                </div>
                <div class="pull-right _none">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="box-body datatable-body item-main-body" id="item-main-body">


                <div class="row col-md-12 datatable-search-row">
                    <div class="input-group">

                        <input type="text" class="form-control form-filter item-search-keyup" name="keyword" placeholder="关键词" />
                        <input type="text" class="form-control form-filter item-search-keyup" name="website" placeholder="站点" />

                        <select class="form-control form-filter" name="searchengine" style="width:96px;">
                            <option value ="0">搜索引擎</option>
                            <option value ="baidu">百度PC</option>
                            <option value ="baidu_mobile">百度移动</option>
                            <option value ="sougou">搜狗</option>
                            <option value ="360">360</option>
                            <option value ="shenma">神马</option>
                        </select>

                        <select class="form-control form-filter" name="keywordstatus" style="width:80px;">
                            <option value ="默认">默认</option>
                            <option value ="全部">全部</option>
                            <option value ="优化中">优化中</option>
                            <option value ="待审核">待审核</option>
                            <option value ="合作停">合作停</option>
                            <option value ="已删除">已删除</option>
                        </select>

                        <button type="button" class="form-control btn btn-flat btn-success filter-submit" id="filter-submit">
                            <i class="fa fa-search"></i> 搜索
                        </button>
                        <button type="button" class="form-control btn btn-flat btn-default filter-cancel">
                            <i class="fa fa-circle-o-notch"></i> 重置
                        </button>

                    </div>
                </div>


                <!-- datatable start -->
                <table class='table table-striped table-bordered' id='datatable_ajax'>
                    <thead>
                    <tr role='row' class='heading'>
                        <th>序号</th>
                        {{--<th>ID</th>--}}
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
                        {{--<td></td>--}}
                        <td></td>
                        <td><input type="text" class="form-control form-filter item-search-keyup" name="keyword" /></td>
                        <td><input type="text" class="form-control form-filter item-search-keyup" name="website" /></td>
                        <td>
                            <select name="searchengine-" class="form-control form-filter" style="width:64px;">
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
                            <select name="keywordstatus-" class="form-control form-filter" style="width:64px;">
                                <option value ="0">全部</option>
                                <option value ="优化中">优化中</option>
                                <option value ="待审核">待审核</option>
                                <option value ="合作停">合作停</option>
                                <option value ="已删除">已删除</option>
                            </select>
                        </td>
                        <td>
                            <a href="javascript:void(0);" class="btn btn-xs filter-submit" id="filter-submit-">搜索</a>
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

            <div class="box-footer _none">
                <div class="row" style="margin:16px 0;">
                    <div class="col-md-offset-0 col-md-4 col-sm-8 col-xs-12">
                        {{--<button type="button" class="btn btn-primary"><i class="fa fa-check"></i> 提交</button>--}}
                        {{--<button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>--}}
                        <div class="input-group">
                            <span class="input-group-addon"><input type="checkbox" id="check-review-all"></span>
                            <select name="bulk-review-keyword-status" class="form-control form-filter">
                                <option value ="0">请选择</option>
                                <option value ="待审核">待审核</option>
                                <option value ="优化中">优化中</option>
                                <option value ="合作停">合作停</option>
                                <option value ="被拒绝">被拒绝</option>
                            </select>
                            <span class="input-group-addon btn btn-default" id="review-keyword-bulk-submit"><i class="fa fa-check"></i> 批量审核</span>
                            <span class="input-group-addon btn btn-default" id="delete-keyword-bulk-submit"><i class="fa fa-trash-o"></i> 批量删除</span>
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


{{--关键词审核--}}
<div class="modal fade" id="modal-body">
    <div class="col-md-8 col-md-offset-2" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;">

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="box- box-info- form-container">

                    <div class="box-header with-border" style="margin:16px 0;">
                        <h3 class="box-title">关键词审核</h3>
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
                                        <option value ="待审核">待审核</option>
                                        <option value ="优化中">优化中</option>
                                        <option value ="合作停">合作停</option>
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


{{--关键词排名详情--}}
<div class="modal fade" id="modal-data-detect-body">
    <div class="col-md-8 col-md-offset-2" id="edit-ctn-" style="background:#fff;">
        <div class="box box-info- form-container datatable-body" id="item-content-body">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">内容列表</h3>
                <div class="caption">
                    <i class="icon-pin font-blue"></i>
                    <span class="caption-subject font-blue sbold uppercase"></span>
                </div>
            </div>

            <div class="box-body datatable-body item-main-body" id="item-main-body">
                <!-- datatable start -->
                <table class='table table-striped table-bordered' id='datatable_ajax_inner'>
                    <thead>
                    <tr role='row' class='heading'>
                        <th>选择</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
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


{{--添加排名记录--}}
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


{{--修改排名记录--}}
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
                "aLengthMenu": [[20, 50, 200], ["20", "50", "200"]],
                "bAutoWidth": false,
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    'url': "{{ url('/agent/business/keyword-list') }}",
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.keyword = $('input[name="keyword"]').val();
                        d.website = $('input[name="website"]').val();
                        d.searchengine = $('select[name="searchengine"]').val();
                        d.keywordstatus = $('select[name="keywordstatus"]').val();
//                        d.length = $('select[name="length"]').val();
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
//                    {
//                        "width": "32px",
//                        "title": "ID",
//                        "data": "id",
//                        'orderable': true,
//                        render: function(data, type, row, meta) {
//                            return data;
//                        }
//                    },
                    {
                        "className": "text-left",
                        "width": "88px",
                        "title": "客户",
                        "data": "createuserid",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return row.creator == null ? '未知' : '<a target="_blank" href="/agent/user/client?id='+row.creator.id+'">'+row.creator.username+'</a>';
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
                        "width": "96px",
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
                        "width": "72px",
                        "title": "创建<br>时间",
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
                        "width": "40px",
                        "title": "价格",
                        "data": "price",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            return '<span class="text-blue">'+parseInt(data)+'</span>';
                        }
                    },
                    {
                        "width": "40px",
                        "title": "初始<br>排名",
                        "data": "initialranking",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "width": "64px",
                        "title": "最新<br>排名",
                        "data": "latestranking",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            var $gif = '';
                            if(data < row.initialranking)
                            {
                                $gif = '<img src="/seo/img/up.gif" style="vertical-align:middle;float:right;">';
                            }
                            else if(data > row.initialranking)
                            {
                                $gif = '<img src="/seo/img/down.gif" style="vertical-align:middle;float:right;">';
                            }
                            if((data > 0) && (data <= 10)) return '<samll class="text-red">'+data+'</samll>'+$gif;
                            else return data+$gif;
                        }
                    },
                    {
                        "width": "40px",
                        "title": "最新<br>消费",
                        "data": "latestconsumption",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            if(parseInt(data) > 0) return '<span class="text-blue">'+parseInt(data)+'</span>';
                            else return parseInt(data);
                        }
                    },
                    {
                        "width": "40px",
                        "title": "达标<br>天数",
                        "data": "standarddays",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            if(parseInt(data) > 0) return '<span class="text-blue">'+parseInt(data).toLocaleString()+'</span>';
                            else return parseInt(data);
                        }
                    },
                    {
                        "width": "40px",
                        "title": "累计<br>消费",
                        "data": "totalconsumption",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            if(parseInt(data) > 0) return '<span class="text-blue">'+parseInt(data).toLocaleString()+'</span>';
                            else return parseInt(data);
                        }
                    },
                    {
                        "width": "72px",
                        "title": "检测<br>时间",
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
                            var $cooperation_html = '';
                            var $review_html = '<a class="btn btn-xs btn-default disabled">审核</a>';
                            var $delete_html = '<a class="btn btn-xs btn-default disabled">删除</a>';

                            if(row.status == 1)
                            {
                                if(row.keywordstatus == '优化中')
                                {
                                    $cooperation_html = '<a class="btn btn-xs bg-navy item-stop-submit" data-id="'+data+'" >合作停</a>';
                                }
                                else if(row.keywordstatus == '合作停')
                                {
                                    $cooperation_html = '<a class="btn btn-xs bg-primary item-start-submit" data-id="'+data+'" >再合作</a>';
                                }

                                $review_html = '<a class="btn btn-xs bg-primary item-review-show" data-id="'+data+'" data-name="'+row.sitename+'" data-website="'+row.website+'" data-keyword="'+row.keyword+'" data-price="'+row.price+'">审核</a>';
                                $delete_html = '<a class="btn btn-xs bg-navy item-delete-submit" data-id="'+data+'" >删除</a>';
                            }

                            var html =
                                    {{--'<a class="btn btn-xs item-enable-submit" data-id="'+data+'">启用</a>'+--}}
                                    {{--'<a class="btn btn-xs item-disable-submit" data-id="'+data+'">禁用</a>'+--}}
                                    {{--'<a class="btn btn-xs item-download-qrcode-submit" data-id="'+data+'">下载二维码</a>'+--}}
                                    {{--'<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+--}}
                                    {{--'<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+--}}
                                    {{--'<a class="btn btn-xs item-edit-submit" data-id="'+value+'">编辑</a>'+--}}
//                                    $cooperation_html+
//                                    $review_html+
//                                    $delete_html+
//                                    '<a class="btn btn-xs bg-primary item-data-detail-link" data-id="'+data+'" >数据详情</a>'+
                                    '<a class="btn btn-xs bg-primary item-data-detail-show" data-id="'+data+'" data-keyword="'+row.keyword+'">数据详情</a>'+
                                    '<a class="btn btn-xs bg-olive item-download-link" data-id="'+data+'" >下载</a>'+
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
                    'url': "/agent/business/keyword-detect-record?id="+$id,
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

        // 【搜索】
        $(".item-main-body").on('click', ".filter-submit", function() {
            $('#datatable_ajax').DataTable().ajax.reload();
        });
        // 【重置】
        $(".item-main-body").on('click', ".filter-cancel", function() {
            $('textarea.form-filter, input.form-filter, select.form-filter').each(function () {
                $(this).val("");
            });

//                $('select.form-filter').selectpicker('refresh');
            $('select.form-filter option').attr("selected",false);
            $('select.form-filter').find('option:eq(0)').attr('selected', true);

            $('#datatable_ajax').DataTable().ajax.reload();
        });
        // 【查询】回车
        $(".item-main-body").on('keyup', ".item-search-keyup", function(event) {
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
            window.location.href = "/item/edit?id="+that.attr('data-id');
        });





        // 【批量审核】全选or反选
        $(".datatable-body").on('click', '#check-review-all', function () {
            $('input[name="bulk-keyword-id"]').prop('checked',this.checked);//checked为true时为默认显示的状态
        });

        // 【批量审核】
        $(".datatable-body").on('click', '#review-keyword-bulk-submit', function() {
            var $checked = [];
            $('input[name="bulk-keyword-id"]:checked').each(function() {
                $checked.push($(this).val());
            });

            layer.msg('确定"批量审核"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){

                    $.post(
                        "{{ url('/agent/business/keyword-review-bulk') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "keyword-review-bulk",
                            bulk_keyword_id: $checked,
                            bulk_keyword_status:$('select[name="bulk-review-keyword-status"]').val()
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                $('#datatable_ajax').DataTable().ajax.reload();
                            }
                        },
                        'json'
                    );

                }
            });

        });

        // 【批量审核】
        $(".datatable-body").on('click', '#delete-keyword-bulk-submit', function() {
            var $checked = [];
            $('input[name="bulk-keyword-id"]:checked').each(function() {
                $checked.push($(this).val());
            });

            layer.msg('确定"批量删除"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){

                    $.post(
                        "{{ url('/agent/business/keyword-delete-bulk') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "keyword-delete-bulk",
                            bulk_keyword_id: $checked
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                $('#datatable_ajax').DataTable().ajax.reload();
                            }
                        },
                        'json'
                    );

                }
            });

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
                        url: "{{ url('/agent/business/keyword-review') }}",
                        type: "post",
                        dataType: "json",
                        // target: "#div2",
                        success: function (data) {

                            layer.close(index);
                            $("#item-review-cancel").click();

                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg(data.msg);
//                                location.reload();
                                $('#datatable_ajax').DataTable().ajax.reload();
                            }
                        }
                    };
                    $("#form-edit-modal").ajaxSubmit(options);
                }
            });
        });




        // 【数据详情】
        $(".item-main-body").on('click', ".item-data-detail-link", function() {
            var that = $(this);
            window.open("/agent/business/keyword-detect-record?id="+that.attr('data-id'));
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
            layer.msg('确定要"删除"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/agent/business/keyword-delete') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"delete-keyword",
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
//                                location.reload();
                                $('#datatable_ajax').DataTable().ajax.reload();
                            }
                        },
                        'json'
                    );
                }
            });
        });


        // 【合作停】
        $(".item-main-body").on('click', ".item-stop-submit", function() {
            var that = $(this);
            layer.msg('确定要"合作停"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/agent/business/keyword-stop') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"stop-keyword",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
//                                location.reload();
                                $('#datatable_ajax').DataTable().ajax.reload();
                            }
                        },
                        'json'
                    );
                }
            });
        });
        // 【再合作】
        $(".item-main-body").on('click', ".item-start-submit", function() {
            var that = $(this);
            layer.msg('确定要"再合作"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/agent/business/keyword-start') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"start-keyword",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
//                                location.reload();
                                $('#datatable_ajax').DataTable().ajax.reload();
                            }
                        },
                        'json'
                    );
                }
            });
        });




        // 【下载】
        $(".item-main-body").on('click', ".item-download-link", function() {
            var that = $(this);
            layer.msg('确定要"下载"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    layer.close(index);
                    window.open("/agent/business/download/keyword-detect?id="+that.attr('data-id'));
                }
            });
        });
        // 【下载】
        $(".item-main-body").on('click', ".item-download-submit", function() {
            var that = $(this);
            layer.msg('确定要"下载"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/agent/business/download/keyword-detect') }}",
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
                            else
                            {
//                                location.reload();
                                $('#datatable_ajax').DataTable().ajax.reload();
                            }
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
                            else
                            {
//                                location.reload();
                                $('#datatable_ajax').DataTable().ajax.reload();
                            }
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
                "{{ url('/agent/business/keyword-detect-set-rank-bulk') }}",
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
                        "{{ url('/agent/business/keyword-detect-create-rank') }}",
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
                "{{ url('/agent/business/keyword-detect-set-rank') }}",
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
//                    else location.reload();
                    else
                    {
//                        layer.close(index);
                        $('#modal-set-body').modal('hide');
                        $("#modal-set-body").on("hidden.bs.modal", function () {
                            $("body").addClass("modal-open");
                        });

                        var $keyword_id = $("#set-rank-bulk-submit").attr("data-keyword-id");
                        TableDatatablesAjax_inner.init($keyword_id);
                    }
                },
                'json'
            );
        });

    });
</script>
@endsection
