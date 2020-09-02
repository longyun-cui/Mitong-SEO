@foreach($items as $num => $item)
    <tr id="tr{{ $num }}">

        <td style="text-align:left">
            {{ $item['keyword'] or '' }}
        </td>
        <td class="text-center keyword_price price_pc">
            {{ $item['baidu'] or '' }}元/天
            <input type="hidden" name="keywords[{{ $num }}][data][baidu]" value="{{ $item['baidu'] or 0 }}" data-price="{{ $item['baidu'] or 0 }}">
        </td>
        <td class="text-center keyword_price price_yidong">
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
            <span style="color:red;font-size:16px;">{{ $item['rate'] or '' }}</span>
        </td>
        <td class="text-center">
            {{ $item['optimization_cycle'] or '' }}
        </td>

    </tr>
@endforeach