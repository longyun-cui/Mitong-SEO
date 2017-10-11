@extends('admin.layout.layout')

@section('title','编辑问卷')
@section('header','编辑问卷')
@section('description','编辑问卷')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-home"></i>首页</a></li>
    <li><a href="{{url('/admin/survey/list')}}"><i class="fa "></i>问卷列表</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title"></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-survey">
            <div class="box-body">
                {{csrf_field()}}
                <input type="hidden" name="operate" value="{{$operate_id or ''}}" readonly>
                <input type="hidden" name="id" value="{{$encode_id or encode(0)}}" readonly>

                {{--名称--}}
                <div class="form-group">
                    <label class="control-label col-md-2">名称</label>
                    <div class="col-md-8 ">
                        <div><input type="text" class="form-control" name="name" placeholder="请输入名称" value="{{$data->name or ''}}"></div>
                    </div>
                </div>
                {{--标题--}}
                <div class="form-group">
                    <label class="control-label col-md-2">标题</label>
                    <div class="col-md-8 ">
                        <div><input type="text" class="form-control" name="title" placeholder="请输入标题" value="{{$data->title or ''}}"></div>
                    </div>
                </div>
                {{--说明--}}
                <div class="form-group">
                    <label class="control-label col-md-2">描述</label>
                    <div class="col-md-8 ">
                        <div><input type="text" class="form-control" name="description" placeholder="描述" value="{{$data->description or ''}}"></div>
                    </div>
                </div>
                {{--内容--}}
                <div class="form-group">
                    <label class="control-label col-md-2">内容详情</label>
                    <div class="col-md-8 ">
                        <div>
                            @include('UEditor::head')
                            <!-- 加载编辑器的容器 -->
                            <script id="container" name="content" type="text/plain" style="width:100%;">{!! $data->content or '' !!}</script>
                            <!-- 实例化编辑器 -->
                            <script type="text/javascript">
                                var ue = UE.getEditor('container');
                                ue.ready(function() {
                                    ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                                });
                            </script>
                        </div>
                    </div>
                </div>

            </div>
            </form>

            <div class="box-footer">
                <div class="row" style="margin:16px 0;">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" class="btn btn-primary" id="edit-survey-submit"><i class="fa fa-check"></i> 提交</button>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-warning">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">问题管理</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove"><i class="fa fa-times"></i></button>
                </div>
                <input type="hidden" id="slide-page-marking" data-key="1000">
            </div>

            <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-page">
                <div class="box-body">
                    {{csrf_field()}}
                    <input type="hidden" name="operate" value="{{$operate or ''}}" readonly>
                    <input type="hidden" name="slide_id" value="{{$encode_id or encode(0)}}" readonly>
                    <div class="box-header with-border page-container">
                        {{--操作--}}
                        <div class="form-group">
                            <label class="control-label col-md-2">操作</label>
                            <div class="col-md-8">
                                <button type="button" class="btn btn-sm btn-success create-new-radio">添加单选题</button>
                                <button type="button" class="btn btn-sm btn-success create-new-checkbox">添加多选题</button>
                                <button type="button" class="btn btn-sm btn-success create-new-text">添加文本题</button>
                                <button type="button" class="btn btn-sm btn-danger delete-all-page">删除全部</button>
                            </div>
                        </div>
                    </div>

                    @foreach($data->questions as $v)
                        <div class="box-body question-container question-option" data-id="{{$v->encode_id or ''}}">
                            {{--标题--}}
                            <div class="form-group">
                                <input type="hidden" name="question[{{$v->order}}][id]" value="{{$v->id or ''}}">
                                <label class="control-label col-md-2">幻灯页名称</label>
                                <div class="col-md-8">
                                    <div><input type="text" class="form-control" name="question[{{$v->order}}][name]" placeholder="请输入幻灯页名称" value="{{$v->name or ''}}"></div>
                                </div>
                            </div>
                            {{--操作--}}
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-2">
                                    <a href="/admin/slide/page/edit/{{$v->encode_id or ''}}" target="_blank">
                                        <button type="button" class="btn btn-xs btn-primary edit-this-page">编辑内容</button>
                                    </a>
                                    <button type="button" class="btn btn-xs btn-success create-next-page">添加</button>
                                    @if(false)
                                        <button type="button" class="btn btn-xs btn-danger delete-this-page">删除</button>
                                    @endif
                                    <button type="button" class="btn btn-xs moveup-this-page">上移</button>
                                    <button type="button" class="btn btn-xs movedown-this-page">下移</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>

            <div class="box-footer">
                <div class="row" style="margin:16px 0;">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" class="btn btn-primary" id="edit-page-submit"><i class="fa fa-check"></i> 保存</button>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>
@endsection


@section('js')
<script>

$(function() {
    // 添加or修改调研问卷信息
    $("#edit-survey-submit").on('click', function() {
        var options = {
            url: "/admin/survey/edit",
            type: "post",
            dataType: "json",
            // target: "#div2",
            success: function (data) {
                if(!data.success) layer.msg(data.msg);
                else
                {
                    layer.msg(data.msg);
                    location.href = "/admin/survey/list";
                }
            }
        };
        $("#form-edit-survey").ajaxSubmit(options);
    });
});


function question_html(key)
{
    var html =
            '<div class="box-body page-container page-option">'+
            '<div class="form-group">'+
            '<input type="hidden" name="question['+key+'][id]">'+
            '<label class="control-label col-md-2">问题名称</label>'+
            '<div class="col-md-8">'+
            '<div><input type="text" class="form-control" name="page['+key+'][name]" placeholder="请输入名称"></div>'+
            '</div>'+
            '</div>'+
            '<div class="form-group">'+
            '<div class="col-md-8 col-md-offset-2">'+
//            '<button type="button" class="btn btn-xs btn-primary edit-this-page">编辑内容</button>'+
            '<button type="button" class="btn btn-xs btn-success create-next-page">添加</button>'+
            '<button type="button" class="btn btn-xs btn-danger delete-this-page">删除</button>'+
            '<button type="button" class="btn btn-xs moveup-this-page">上移</button>'+
            '<button type="button" class="btn btn-xs movedown-this-page">下移</button>'+
            '</div>'+
            '</div>'+
            '</div>';
    return html;
}

function option_html(key)
{
    var html =
            '<div class="box-body page-container page-option">'+
            '<div class="form-group">'+
            '<input type="hidden" name="option['+key+'][id]">'+
            '<label class="control-label col-md-2">问题名称</label>'+
            '<div class="col-md-8">'+
            '<div><input type="text" class="form-control" name="page['+key+'][name]" placeholder="请输入名称"></div>'+
            '</div>'+
            '</div>'+
            '<div class="form-group">'+
            '<div class="col-md-8 col-md-offset-2">'+
//                        '<button type="button" class="btn btn-xs btn-primary edit-this-page">编辑内容</button>'+
            '<button type="button" class="btn btn-xs btn-success create-next-page">添加</button>'+
            '<button type="button" class="btn btn-xs btn-danger delete-this-page">删除</button>'+
            '<button type="button" class="btn btn-xs moveup-this-page">上移</button>'+
            '<button type="button" class="btn btn-xs movedown-this-page">下移</button>'+
            '</div>'+
            '</div>'+
            '</div>';
    return html;
    ;

}

</script>
@endsection
