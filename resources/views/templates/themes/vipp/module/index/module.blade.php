<div class="row full wrapper-module-container">
    <div class="col-md-14">
        <div class="row full block-in">

            <div class="module-header-container">
                <span class="menu-title"><b>{{ $data->title }}</b></span>
            </div>

            <div class="module-block-container">
                <div class="row full block-{{$data->column}}-column block-article" style="font-size:0;">

                    @foreach($data->menus as $m)
                    <li class="item-block" role="button">
                        <div class="item-block-top">
                            <b>{{ $m->title }}</b>
                        </div>
                        <div class="item-block-middle">
                            @foreach($m->items as $v)
                                <a target="_blank" href="{{url(config('common.org.front.prefix').'/item/'.encode($v->id))}}">
                                    <div class="row article-row">
                                        <span class="article-section article-icon"><i class="fa fa-clone"></i></span>
                                        <span class="article-section article-title multi-ellipsis-1 z-index-9">{{$v->title or ''}}</span>
                                        <span class="article-section article-time">{{$v->updated_at->format("Y-m-d")}}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="item-block-bottom">
                            <a href="{{ url(config('common.org.front.prefix').'/menu/'.encode($m->id)) }}">更多</a>
                        </div>
                    </li>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</div>
