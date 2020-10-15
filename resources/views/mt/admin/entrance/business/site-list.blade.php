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

                        <input type="text" class="form-control form-filter item-search-keyup" name="sitename" placeholder="站点名称" />
                        <input type="text" class="form-control form-filter item-search-keyup" name="website" placeholder="站点地址" />

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
                    <tr class="_none">
                        <td></td>
                        <td></td>
                        <td><input type="text" class="form-control form-filter item-search-keyup" name="sitename-" /></td>
                        <td><input type="text" class="form-control form-filter item-search-keyup" name="website-" /></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        {{--<td></td>--}}
                        <td></td>
                        <td></td>
                        <td>
                            <a href="javascript:void(0);" class="btn btn-xs filter-submit" id="filter-submit-">搜索</a>
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


{{--站点审核--}}
<div class="modal fade" id="modal-body">
    <div class="col-md-8 col-md-offset-2" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;">

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="box- box-info- form-container">

                    <div class="box-header with-border" style="margin:16px 0;">
                        <h3 class="box-title">站点审核</h3>
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
                                <label class="control-label col-md-2">站点ID</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="review-site-id"></span>
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
                            {{--FTP--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">FTP</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="review-ftp"></span>
                                </div>
                            </div>
                            {{--管理后台--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">管理后台</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <span class="review-managebackground"></span>
                                </div>
                            </div>
                            {{--审核意见--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">审核结果</label>
                                <div class="col-md-8 ">
                                    <select name="sitestatus" class="form-control form-filter">
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


{{--站点详情--}}
<div class="modal fade" id="modal-detail-body">
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


<div class="modal fade" id="modal-edit-body">
    <div class="col-md-8 col-md-offset-2" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;">

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="box- box-info- form-container">

                    <div class="box-header with-border" style="margin:16px 0;">
                        <h3 class="box-title">编辑站点</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <form action="" method="post" class="form-horizontal form-bordered" id="form-site-edit-modal">
                        <div class="box-body">

                            {{csrf_field()}}
                            <input type="hidden" name="operate" value="site-edit" readonly>
                            <input type="hidden" name="operate_id" value="0" class="site-edit-operate-id" readonly>

                            {{--类别--}}


                            {{--站点ID--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">站点ID</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <div><b class="site-edit-id"></b></div>
                                </div>
                            </div>
                            {{--站点名称--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">站点名称</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <input type="text" class="form-control form-filter site-edit-sitename" name="sitename" value="">
                                </div>
                            </div>
                            {{--站点地址--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">站点地址</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <input type="text" class="form-control form-filter site-edit-website" name="website" value="">
                                </div>
                            </div>
                            {{--FTP--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">FTP</label>
                                <div class="col-md-8 ">
                                    <textarea class="form-control site-edit-ftp" name="ftp" rows="3" cols="100%" placeholder="请准确FTP信息,以便优化师调整">{{ $data->ftp or '' }}</textarea>
                                </div>
                            </div>
                            {{--管理后台--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">管理后台</label>
                                <div class="col-md-8 ">
                                    <textarea class="form-control site-edit-managebackground" name="managebackground" rows="3" cols="100%" placeholder="请填写后台管理账号,以便优化师调整">{{ $data->managebackground or '' }}</textarea>
                                </div>
                            </div>


                        </div>
                    </form>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <button type="button" class="btn btn-success" id="item-site-edit-submit"><i class="fa fa-check"></i> 提交</button>
                                <button type="button" class="btn btn-default" id="item-site-edit-cancel">取消</button>
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
                            return '<span href="javascript:void(0);" class="item-site-detail-show text-blue _pointer" data-user-id="'+row.creator.id+'" data-username="'+row.creator.username+'" data-name="'+data+'" data-website="'+row.website+'" data-ftp="'+row.ftp+'" data-managebackground="'+row.managebackground+'">'+data+'</span>'
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
                        "title": "优化<br>关键词",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
//                            return row.keywords_count == null ? 0 : row.keywords_count;
                            if(row.keywords_count)
                            {
//                                if(row.keywords_count > 0) return '<span class="text-blue">'+row.keywords_count+'</span>';
                                if(row.keywords_count > 0) return '<a target="_blank" href="/admin/user/client?id='+row.creator.id+'">'+row.keywords_count+'</a>';
                                else row.keywords_count;
                            }
                            else return 0;

                            ;
                        }
                    },
                    {
                        "width": "64px",
                        "title": "今日<br>达标",
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
                        "title": "今日<br>消费",
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
                        "title": "累计<br>消费",
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

                            var $cooperation_html = '';
                            var $review_html = '<a class="btn btn-xs btn-default disabled">审核</a>';
                            var $edit_html = '<a class="btn btn-xs btn-default disabled">编辑</a>';
                            var $delete_html = '<a class="btn btn-xs btn-default disabled">删除</a>';
                            var $work_order_create_html = '<a class="btn btn-xs btn-default disabled">+工单</a>';
                            var $work_order_html = '<a class="btn btn-xs btn-default disabled">Ta的工单</a>';

                            if(row.work_order_count > 0)
                            {
                                $work_order_html = '<a class="btn btn-xs bg-navy item-work-order-link" data-id="'+data+'" >Ta的工单</a>';
                            }

                            if(row.status == 1)
                            {
                                if(row.sitestatus == '优化中')
                                {
                                    $cooperation_html = '<a class="btn btn-xs bg-navy item-stop-submit" data-id="'+data+'" >合作停</a>';
                                    $work_order_create_html = '<a class="btn btn-xs bg-navy item-work-order-create-link" data-id="'+data+'">+工单</a>';
                                }
                                else if(row.sitestatus == '合作停')
                                {
                                    $cooperation_html = '<a class="btn btn-xs bg-primary item-start-submit" data-id="'+data+'" >再合作</a>';
                                }

                                $review_html = '<a class="btn btn-xs bg-primary item-review-show" data-id="'+data+'" data-name="'+row.sitename+'" data-website="'+row.website+'" data-ftp="'+row.ftp+'" data-managebackground="'+row.managebackground+'">审核</a>';

                                $delete_html = '<a class="btn btn-xs bg-navy item-delete-submit" data-id="'+data+'" >删除</a>';
                            }

                            $edit_html = '<a class="btn btn-xs bg-navy item-edit-show" data-id="'+data+'" >编辑</a>';

                            var html =
//                                '<a class="btn btn-xs item-enable-submit" data-id="'+value+'">启用</a>'+
//                                '<a class="btn btn-xs item-disable-submit" data-id="'+value+'">禁用</a>'+
//                                '<a class="btn btn-xs item-download-qrcode-submit" data-id="'+value+'">下载二维码</a>'+
//                                '<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+
                                    {{--'<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+--}}
//                                '<a class="btn btn-xs item-edit-submit" data-id="'+value+'">编辑</a>'+
//                                '<a class="btn btn-xs bg-navy item-delete-submit" data-id="'+data+'" >删除</a>'+
                                $review_html+
                                $work_order_create_html+
                                $work_order_html+
//                                $cooperation_html+
                                $edit_html+
                                $delete_html+
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




        // 【审核】显示
        $("#item-main-body").on('click', ".item-review-show", function() {
            var that = $(this);
            $('input[name=id]').val(that.attr('data-id'));
            $('.review-site-id').html(that.attr('data-id'));
            $('.review-site-name').html(that.attr('data-name'));
            $('.review-website').html(that.attr('data-website'));
            $('.review-ftp').html(that.attr('data-ftp'));
            $('.review-managebackground').html(that.attr('data-managebackground'));
            $('#modal-body').modal('show');
        });
        // 【审核】取消
        $("#modal-body").on('click', "#item-review-cancel", function() {
            $('.review-user-id').html('');
            $('.review-user-name').html('');
            $('.review-website').html('');
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
                        url: "{{ url('/admin/business/site-review') }}",
                        type: "post",
                        dataType: "json",
                        // target: "#div2",
                        success: function (data) {
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg(data.msg);
//                                location.reload();
                                $("#item-review-cancel").click();
                                $('#datatable_ajax').DataTable().ajax.reload();
                            }
                        }
                    };
                    $("#form-edit-modal").ajaxSubmit(options);
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




        // 【站点详情】显示
        $("#item-main-body").on('click', ".item-site-detail-show", function() {
            var that = $(this);
            $('input[name=id]').val(that.attr('data-id'));
            $('.site-user-id').html(that.attr('data-user-id'));
            $('.site-username').html(that.attr('data-username'));
            $('.site-name').html(that.attr('data-name'));
            $('.site-website').html(that.attr('data-website'));
            $('.site-ftp').html(that.attr('data-ftp'));
            $('.site-managebackground').html(that.attr('data-managebackground'));
            $('#modal-detail-body').modal('show');
        });

        // 【修改站点】显示
        $("#item-main-body").on('click', ".item-edit-show", function() {
            var that = $(this);
            var $data = new Object();
            $.ajax({
                type:"post",
                dataType:'json',
                async:false,
                url: "{{ url('/admin/business/site-get') }}",
                data: {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate:"site-get",
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

            $('input[name=operate_id].site-edit-operate-id').val($data.id);
            $('.site-edit-id').html($data.id);
            $('.site-edit-sitename').val($data.sitename);
            $('.site-edit-website').val($data.website);
            $('.site-edit-ftp').val($data.ftp);
            $('.site-edit-managebackground').val($data.managebackground);
            $('#modal-edit-body').modal('show');
        });
        // 【修改密码】取消
        $("#modal-edit-body").on('click', "#item-site-edit-cancel", function() {
            $('input[name=operate_id].site-edit-operate-id').val('');
            $('.site-edit-id').html('');
            $('.site-edit-sitename').val('');
            $('.site-edit-website').val('');
            $('.site-edit-ftp').val('');
            $('.site-edit-managebackground').val('');
            $('#modal-edit-body').modal('hide');
        });
        // 【修改密码】提交
        $("#modal-edit-body").on('click', "#item-site-edit-submit", function() {
            var that = $(this);
            layer.msg('确定"提交"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){

                    var options = {
                        url: "{{ url('/admin/business/site-edit') }}",
                        type: "post",
                        dataType: "json",
                        // target: "#div2",
                        success: function (data) {
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg(data.msg);
                                $("#item-site-edit-cancel").click();
//                                location.reload();
                                $('#datatable_ajax').DataTable().ajax.reload();
                            }
                        }
                    };
                    $("#form-site-edit-modal").ajaxSubmit(options);
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
