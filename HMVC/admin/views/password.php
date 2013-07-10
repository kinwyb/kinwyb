<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
<link rel="stylesheet" href="<?php echo base_url('views/css/reset.css'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('views/css/style.css'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('views/css/invalid.css'); ?>" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url('views/js/jquery.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/js/simpla.jquery.configuration.js'); ?>"></script>
<script>
var i=4
$(document).ready(function(){
	$("#medium-input").keyup(function()
	{
		val=$(this).val();
		val2=$("#small-input").val();
		if(val!=val2)
			$("#error").show();
		else
			$("#error").css("display","none");
	});
});
</script>
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
    <?php if(!empty($error))
	  {?>
      <div class="notification error png_bg"> <a href="#" class="close"><img src="<?php echo base_url('views/images/icons/cross_grey_small.png')?>" title="Close this notification" alt="close" /></a>
      <div> <?php echo validation_errors(); ?></div>
    </div>
    <?php }?>
    <div class="content-box">
      <div class="content-box-header">
        <h3>修改密码</h3>
<div class="clear"></div>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab2">
          <form action="<?php echo site_url('index/pwd_chg');?>" method="post">
            <fieldset>
            <p>
              <label>原密码</label>
              <input class="text-input small-input" type="password" name="pwd" /></p>
            <p>
              <label>新密码</label>
              <input class="text-input small-input" type="password" id="small-input" name="npwd" /></p>
            <p>
              <label>重复新密码</label>
              <input class="text-input small-input" type="password" id="medium-input" name="npwd2" />
              <span class="input-notification error png_bg" id="error" style="display:none">两次密码输入不正确</span> </p>
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
