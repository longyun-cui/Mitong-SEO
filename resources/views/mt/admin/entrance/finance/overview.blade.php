@extends('mt.admin.layout.layout')

@section('head_title','财务概览  - 搜索引擎智能营销系统 - 米同科技')

@section('header','财务概览')
@section('description','搜索引擎智能营销系统-米同科技')

@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
    admin.finance.overview
@endsection


@section('custom-script')
<script>
    $(function() {
    });
</script>
@endsection
