<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
<link rel="stylesheet" href="<?php echo base_url('views/css/reset.css');?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('views/css/style.css');?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('views/css/invalid.css');?>" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url('views/js/jquery.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/js/simpla.jquery.configuration.js');?>"></script>
<script type="text/javascript"> var i=1</script>
</head>
<body>
<div id="body-wrapper">
  <div id="sidebar">
  <?php $this->load->module('public/info_made/sys_left');?>
  </div>
  <div id="main-content">
    <h2>名品渔具后台 </h2>
    <div class="clear" style="height:40px;"></div>
    <div class="content-box column-left">
      <div class="content-box-header">
        <h3>文章管理</h3>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab">
          <h4>介绍</h4>
          <p> &nbsp;&nbsp;&nbsp;&nbsp;添加、删除、修改 本网站上发布文章</p>
        </div>
      </div>
    </div>
    <div class="content-box column-right">
      <div class="content-box-header">
        <h3>采集管理</h3>
      </div>
      <!-- End .content-box-header -->
      <div class="content-box-content">
        <div class="tab-content default-tab">
          <h4>介绍</h4>
          <p> 采集使用模拟用户浏览，如需要登入才能采集图片，这需设置COOKIES才能完整采集</p>
        </div>
      </div>
    </div>
    <div class="clear"></div>
    
    <div class="content-box column-left">
      <div class="content-box-header">
        <h3>图片介绍</h3>
      </div>
      <!-- End .content-box-header -->
      <div class="content-box-content">
        <div class="tab-content default-tab">
          <h4>介绍</h4>
          <p>图集主要内容为图片展示</p>
        </div>
      </div>
    </div>
<div class="content-box column-right">
      <div class="content-box-header">
        <h3>评论留言</h3>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab">
          <h4>板块简介</h4>
          <p>内容为用户举报的评论！</p>
        </div>
      </div>
    </div>

    <div id="footer" style="float:left; width:100%"> <small>
      &#169; Copyright 2010 Your Company | Powered by <a href="#">admin templates</a> | <a href="#">Top</a>  消耗内存：{memory_usage} 消耗时间:{elapsed_time}</small> </div>
  </div>
</div>
</body>
</html>
