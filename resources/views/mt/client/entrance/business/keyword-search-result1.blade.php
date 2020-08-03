<div id="search">

    <div class="box box-success _none">

        <div class="box-header with-border" style="margin:16px 0;">
            <h3 class="box-title">系统为您推荐的词</h3>
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

        <div class="box-body datatable-body" id="recommend">

            <!-- 加载进度 begin -->
            <!-- <div class="progress">
              <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="sr-only">45% Complete</span></div>
            </div> -->

            <div class="progress" style="margin: 0px auto; text-align: center; padding: 0px 20px 20px; display: none;">
                <img class="progress-img" alt="" src="/seo/img/laoding.gif">
                <div class="progress-text mt10">系统正在为你查询相关推荐的关键词</div>

            </div>
            <!-- 加载进度 end -->
            <div id="show_recommend">
                <div class="alert alert-warning" role="alert">未能获取到相关推荐关键词！</div>
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


    <input type="hidden" name="keywords" value="">

    <div class="box box-success">

        <div class="box-header with-border" style="margin:16px 0;">
            <h3 class="box-title">关键词清单</h3>
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
            <table class='table table-striped table-bordered list' id='tablelist'>
                <thead>
                <tr role='row' class='heading'>
                    <td class="text-center" style="text-align:center">选择</td>
                    <td class="text-left" style="text-align:left">关键词</td>
                    <td class="text-center key_pc"><img alt="" src="/seo/img/baidu.png" style="width: 100px;"></td>
                    <td class="text-center key_yidong"><img alt="" src="/seo/img/baidu_mobile.png" style="width: 100px;"></td>
                    <td class="text-center key_360"><img alt="" src="/seo/img/360.png" style="width: 100px;"></td>
                    <td class="text-center key_sougou"><img alt="" src="/seo/img/sougou.png" style="width: 100px;"></td>
                    <td class="text-center key_shenma"><img alt="" src="/seo/img/shenma.png" style="width: 100px;"></td>
                    <td class="text-center" width="140px">操作</td>
                    <td class="text-center" width="100px">难度指数</td>
                    <td class="text-center" width="60px">优化周期</td>
                </tr>
                </thead>
                <tbody>
                <form action="" method="post" class="form-horizontal form-bordered" id="form-keyword">
                    {{csrf_field()}}
                @foreach($items as $num => $item)
                    <tr id="tr{{ $num }}">

                        <input type="hidden" name="keywords[{{ $num }}][keyword]" value="{{ $item['keyword'] or '' }}">

                        <td style="text-align:center">
                            <input type="checkbox" name="check[{{ $num }}]" checked=""
                               value="{{ $item['keyword'] or '' }}::11000::{{ $item['baidu'] or 0 }},{{ $item['baidu_mobile'] or 0 }},{{ $item['360'] or '' }},{{ $item['sougou'] or 0 }},{{ $item['shenma'] or 0 }}" class="keyword"
                            >
                        </td>
                        <td style="text-align:left">
                            {{ $item['keyword'] or '' }}
                        </td>
                        <td class="text-center keyword_price price_pc checked">
                            {{ $item['baidu'] or '' }}元/天
                            <input type="hidden" name="keywords[{{ $num }}][data][baidu]" value="{{ $item['baidu'] or 0 }}" data-price="{{ $item['baidu'] or 0 }}">
                        </td>
                        <td class="text-center keyword_price price_yidong checked">
                            {{ $item['baidu_mobile'] or '' }}元/天
                            <input type="hidden" name="keywords[{{ $num }}][data][baidu_mobile]" value="{{ $item['baidu_mobile'] or 0 }}" data-price="{{ $item['baidu_mobile'] or 0 }}">
                        </td>
                        <td class="text-center keyword_price price_360 ">
                            {{ $item['360'] or '' }}元/天
                            <input type="hidden" name="search_engine[]" value="360">
                            <input type="hidden" name="keywords[{{ $num }}][data][360]" value="0" data-price="{{ $item['360'] or 0 }}">
                        </td>
                        <td class="text-center keyword_price price_sougou ">
                            {{ $item['sougou'] or '' }}元/天
                            <input type="hidden" name="keywords[{{ $num }}][data][sougou]" value="0" data-price="{{ $item['sougou'] or 0 }}">
                        </td>
                        <td class="text-center keyword_price price_shenma ">
                            {{ $item['shenma'] or '' }}元/天
                            <input type="hidden" name="keywords[{{ $num }}][data][shenma]" value="0" data-price="{{ $item['shenma'] or 0 }}">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-xs bg-black keyword-add-undo" type="">加入清单</button>
                            <a mark="0" onclick="rm(this)" class="btn btn-xs btn-danger">移出</a>
                        </td>
                        <td class="text-center">
                            <span style="color:red;font-size:16px;">{{ $item['rate'] or '' }}</span>
                        </td>
                        <td class="text-center">
                            {{ $item['optimization_cycle'] or '' }}
                        </td>

                    </tr>
                @endforeach
                </form>
                </tbody>
            </table>
            <!-- datatable end -->
        </div>

        <div class="box-footer">
            <div class="row" style="margin:16px 0;">
                <div class="col-md-offset-0 col-md-9">

                    <label for="select_all " class="pull-left" style="line-height: 30px; padding-right: 20px; color: #5e87b1;">
                        <input id="select_all" name="select_all" type="checkbox" style=" vertical-align: text-bottom;margin-bottom: 2px;">
                        <label for="select_all">全选/反选</label>
                    </label>

                    <a class="btn bg-black keyword-all-add-undo">加入清单</a>

                    <button type="button" onclick="" class="btn btn-primary _none"><i class="fa fa-check"></i> 提交</button>
                    <button type="button" onclick="history.go(-1);" class="btn btn-default _none">返回</button>
                </div>

                <p class="tip" style="color: red">提示:点击关键词和搜索引擎名称可以批量选择</p>
            </div>
        </div>

        <script type="text/javascript">
            $(function() {
                $("#select_all").click(function() {
                    $('input[name="check[]"]').prop("checked",this.checked);
                });
            });
        </script>

    </div>


</div>


