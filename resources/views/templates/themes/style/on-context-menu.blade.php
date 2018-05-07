<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0062)http://www.17sucai.com/preview/847335/2018-03-11/yj/index.html -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>鼠标右键插件</title>

	<link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<style type="text/css">
.shade{
	width:100%;
	height: 100%;
	position: absolute;
	top: 0px;
	left: 0px;
	
}
.wrap-ms-right{
	list-style: none;
	position: absolute;
	top: 0;
	left: 0;
	z-index: 9999;
	padding: 5px 0;
	min-width: 80px;
	margin: 0;
	display: none;
	font-family: "微软雅黑";
	font-size: 14px;
	background-color: #fff;
	border: 1px solid rgba(0, 0, 0, .15);
	box-sizing: border-box;
	border-radius: 4px;
	-webkit-box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	-moz-box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	-ms-box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	-o-box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.ms-item{
	height: 30px;
	line-height: 30px;
	text-align: center;
	cursor: pointer;
}
.ms-item:hover{
	background-color: #343a40;
	color: #FFFFFF;
}
.m{width: 800px;
margin-left: auto;
margin-right: auto;
}
</style>
</head>
<body style=""><div class="shade"></div><div class="wrap-ms-right"><li class="ms-item" data-item="0"><i class="fa fa-plus" data-item="0"></i>&nbsp; 添加</li><li class="ms-item" data-item="1"><i class="fa fa-files-o" data-item="1"></i>&nbsp; 修改</li><li class="ms-item" data-item="2"><i class="fa fa-clipboard" data-item="2"></i>&nbsp; 粘贴</li><li class="ms-item" data-item="3"><i class="fa fa-trash" data-item="3"></i>&nbsp; 删除</li></div>

<div class="m">
	<h3>jQuery自定义鼠标右键</h3>
	<p>任意位置点击右键，查看效果</p>
</div>

<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="{{ asset('common/js/animate/mouseRight.js') }}"></script>

<script type="text/javascript">
$('body').mouseRight({menu: [{
	itemName: "添加",
	icon:"fa fa-plus",
	callback: function() {alert('我是添加')}
}, {
	itemName: "修改",
	icon:"fa fa-files-o",
	callback: function() {alert('我是修改')}
},{
	itemName: "粘贴",
	icon:"fa fa-clipboard",
	callback: function() {alert('我是粘贴')}
},{
	itemName: "删除",
	icon:"fa fa-trash",
	callback: function() {alert('我是删除')}
}]});
</script>


</body></html>