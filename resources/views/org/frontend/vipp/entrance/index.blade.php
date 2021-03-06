@extends('templates.themes.vipp.layout.layout')


{{--html.heat--}}
@section('head_title'){{$org->name or '首页'}}@endsection
@section('meta_author')@endsection
@section('meta_title')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection



{{--微信分享--}}
@section('wx_share_title'){{$org->name or '首页'}}@endsection
@section('wx_share_desc'){{$org->slogan or ''}}@endsection
@section('wx_share_imgUrl'){{config('common.host.'.env('APP_ENV').'.cdn').'/'.$org->logo}}@endsection

@section('wechat_share_website_name'){{$org->website_name or '0'}}@endsection
@section('wechat_share_sort',1)
@section('wechat_share_module',0)
@section('wechat_share_page_id',encode(0))



@section('root-link'){{ url(config('common.org.front.index').'/'.$org->website_name) }}@endsection
@section('info-logo-url'){{ config('common.host.'.env('APP_ENV').'.cdn').'/'.$org->logo }}@endsection
@section('info-short-name'){{$org->short or 'Home'}}@endsection



{{--header--}}
@section('component-header')
    @include('templates.themes.vipp.component.org.org-header')
@endsection



{{--footer--}}
@section('component-footer')
    @include('templates.themes.vipp.component.footer')
@endsection



{{--banner--}}
@section('banner-heading'){{$org->name or '首页'}}@endsection
@section('banner-heading-top') Welcome @endsection
@section('banner-heading-bottom'){{$org->slogan or ''}}@endsection
@section('banner-box-left','Our Service')
@section('banner-box-right','Contact Us')



{{--custom-content--}}
@section('custom-content')

    {{--banner--}}
    @include('templates.themes.vipp.component.banner')

    {{--自定义栏位--}}
    @if( count($org->modules) != 0 )
        @foreach($org->modules as $module)

            @include('templates.themes.vipp.module.index.module-'.$module->type.'-'.$module->style,['data'=>$module])
{{--            @include('templates.themes.vipp.module.index.module-'.$module->type.'-0',['data'=>$module])--}}

        @endforeach
    @endif

@endsection
