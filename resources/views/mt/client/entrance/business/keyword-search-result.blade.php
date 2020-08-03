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