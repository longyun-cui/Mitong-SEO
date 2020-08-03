<div id="search">

    <div class="panel tuijian_box" style="background-color: #f2f2f2; margin-bottom: 0;">
        <div class="panel-heading">
            <h3 class="panel-title pull-left" style="line-height: 1.5">
                <i class="iconfont"></i>
                系统为您推荐的词
            </h3>
        </div>
        <div class="panel-body" id="recommend">

            <!-- 加载进度 begin -->
            <!-- <div class="progress">
              <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="sr-only">45% Complete</span></div>
            </div> -->
            <div class="progress" style="margin: 0px auto; text-align: center; padding: 0px 20px 20px; display: none;">
                <img class="progress-img" alt="" src="/seo/img/laoding.gif">
                <div class="progress-text mt10">系统正在为你查询相关推荐的关键词</div>

            </div>
            <!-- 加载进度 end -->

            <div id="show_recommend"><div class="alert alert-warning" role="alert">未能获取到相关推荐关键词！</div></div>
        </div>
    </div>

    <div class="panel-heading" style="padding-bottom: 20px;padding-top: 20px;">

        <h3 class="panel-title pull-left" style="line-height: 1.5">
            <i class="iconfont"></i>关键词清单</h3>
    </div>

    <form name="form2" id="form2" action="/Manage/Keyword/doAdd" method="post" class="table-responsive">
        <input type="hidden" name="keywords" value="{{ $keywords or '' }}">
        <table id="tablelist" class="list" style="width: 100%;">
            <tbody>
            <tr style="background-color: #e5e6e7;font-weight: bold;">
                <td>关键词</td>
                <td class="text-center key_pc"><img alt="" src="/seo/img/baidu.png" style="width: 100px;"></td>
                <td class="text-center key_yidong"><img alt="" src="/seo/img/baidu_mobile.png" style="width: 100px;"></td>
                <td class="text-center key_360"><img alt="" src="/seo/img/360.png" style="width: 100px;"></td>
                <td class="text-center key_sougou"><img alt="" src="/seo/img/sougou.png" style="width: 100px;"></td>
                <td class="text-center key_shenma"><img alt="" src="/seo/img/shenma.png" style="width: 100px;"></td>
                <td class="text-center" width="100px">难度指数</td>
                <td class="text-center" width="60px">优化周期</td>

            </tr>
            @foreach($items as $num => $item)
            <tr id="tr">
                <td>
                    {{ $item['keyword'] or '' }}
                </td>
                <td class="text-center keyword_price price_pc ">
                    {{ $item['baidu'] or '' }}元/天
                    <input type="hidden" value="42">
                </td>
                <td class="text-center keyword_price price_yidong ">
                    {{ $item['baidu_mobile'] or '' }}元/天
                    <input type="hidden" value="45">
                </td>
                <td class="text-center keyword_price price_360 ">
                    {{ $item['sougou'] or '' }}元/天
                    <input type="hidden" value="14">
                </td>
                <td class="text-center keyword_price price_sougou ">
                    {{ $item['360'] or '' }}元/天
                    <input type="hidden" value="13">
                </td>
                <td class="text-center keyword_price price_shenma ">
                    {{ $item['shenma'] or '' }}元/天
                    <input type="hidden" value="10">
                </td>
                <td class="text-center">
                    <span style="color:red;font-size:18px;">{{ $item['rate'] or '' }}</span>
                </td>
                <td class="text-center">
                    {{ $item['optimization_cycle'] or '' }}
                </td>

            </tr>
            @endforeach
            </tbody>
        </table>



    </form>


</div>