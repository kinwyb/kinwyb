<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
<link rel="stylesheet" href="<?php echo base_url('views/css/reset.css');?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('views/css/style.css');?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('views/css/invalid.css');?>" type="text/css" media="screen" />
<script type="text/javascript" src="<?php base_url('views/js/jquery.js');?>"></script>
<script type="text/javascript" src="<?php base_url('views/js/simpla.jquery.configuration.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/js/timer/WdatePicker.js');?>"></script>
<script>var i=8;</script>
</head>
<body>
<div id="body-wrapper">
  <div id="sidebar">
    <?php 
	$this->load->module('public/info_made/sys_left');
    ?>
  </div>
  <div id="main-content">
    <div class="clear"></div>
    <div class="content-box">
      <div class="content-box-header">
        <h3>广告管理-编辑</h3>
        <div class="clear"></div>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab2">
          <form action="<?php echo site_url('myad/edit_post');?>" method="post">
          <input type="hidden" name="id" value="<?php echo $id ?>" />
          <input type="hidden" name="idname" value="<?php echo $idname ?>"/>
            <fieldset>
           <p>
              <label>名称描述</label>
              <input class="text-input small-input" type="text" name="adname" value="<?php echo $adname ?>" /><input type="checkbox" name="timeset"  value="1" <?php if($timeset){ echo 'checked=checked';} ?>/>限时
			</p>
            <p>
              <label>开始时间 <span style="margin-left:150px;">结束时间</span></label>
              <input class="small3-input Wdate" type="text" name="uptime" value="<?php echo date('Y-m-d H:i:s',$uptime);?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',skin:'twoer'})"/>
              <input class="small3-input Wdate" type="text" name="downtime" value="<?php echo date('Y-m-d H:i:s',$downtime);?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',skin:'twoer'})"/>
			</p>
            <p>
              <label>广告内容</label>
              <textarea name="body"><?php echo $body; ?></textarea>
			</p>
            <p>
              <label>过期内容</label>
              <textarea name="nobody"><?php echo $nobody; ?></textarea>
            </p>
            <p>
              <input class="button" type="submit" value="修改" />
            </p>
            </fieldset>
            <div class="clear"></div>
          </form>
        </div>
      </div>
    </div>
    <div class="clear"></div>
    <div id="footer"> <small>
      &#169; Copyright 2010 Your Company | Powered by <a href="#">admin templates</a> | <a href="#">Top</a>  消耗内存：{memory_usage} 消耗时间:{elapsed_time}</small> </div>
   </div>
</div>
</body>
</html>
