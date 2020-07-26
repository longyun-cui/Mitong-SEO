@extends('mt.client.layout.layout')

@section('head_title','待选关键词  - 搜索引擎智能营销系统 - 米同科技')

@section('header','待选关键词')
@section('description','搜索引擎智能营销系统-米同科技')

@section('breadcrumb')
    <li><a href="{{url('/client')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
client.business.my-keyword-undo-list
@endsection


@section('custom-script')
<script>
    $(function() {
    });
</script>
@endsection
