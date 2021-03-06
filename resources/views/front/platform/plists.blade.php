@if(!empty($lists))
    @foreach($lists as $tv)
<tr>
    <td width="180" class="bond-number" title="{!! $tv->title or '' !!}">{!! $tv->title or '' !!}</td>
    <td width="160"><span>  ￥{!! tmoney_format($tv->total) !!}   </span></td>
    <td width="180">
        <span class="rate"><em>{!! $tv->ratio + $tv->mratio !!}</em>%</span>
    </td>
    <td width="160">
        {!! $tv->term or 0 !!} {!! $tv->term_unit == 0 ? '天' : ($tv->term_unit == 1 ? '个月' : '年')!!}
    </td>
    <td width="180" class="text-ellipsis" title="{!! $tv->repay or '' !!}">{!! $tv->repay or '' !!}</td>
    <td width="160">
        <div class="bond-progress">
            <span><em>{!! $tv->proccess !!}</em>%</span>
            <i class="progressbar" style="background-position: {!! $tv->proccess * -54  !!}px 0px;"></i>
        </div>
    </td>
    <td width="110"><a rel="invest_layer" class="btn btn-blue" flag="0" data-isskip="0" data-inversurl="{!! $tv->url or '' !!}" data-bid="{!! $tv->id or 0 !!}" href="javascript:void(0);" data-en-name="{!! $tv->corp->ename or '' !!}" data-invest-id="{!! $tv->id or 0 !!}">去投资</a></td>
</tr>
@endforeach
@endif