<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>名品渔具后台</title>
<link rel="stylesheet" href="<?php echo base_url('views/css/reset.css');?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('views/css/style.css');?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('views/css/invalid.css');?>" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url('views/js/jquery.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/js/simpla.jquery.configuration.js');?>"></script>
</head>
<body id="login">
<script type="text/javascript">
$ = jQuery;
function changeAuthCode() {
	var num = 	new Date().getTime();
	var rand = Math.round(Math.random() * 10000);
	num = num + rand;
	$('#ver_code').css('visibility','visible');
	if ($("#vdimgck")[0]) {
		$("#vdimgck")[0].src = "login/vcode?tag=" + num;
	}
	return false;	
}
</script>
<div id="login-wrapper" class="png_bg">
  <div id="login-top">
    <h1>后台登入</h1>
    <a href="#"><img id="logo" src="<?php echo base_url('views/images/logo.png');?>" alt="Simpla Admin logo" /></a> </div>
  <div id="login-content">
    <form action="<?php echo site_url('login/checkuser')?>" method="post">
      <div class="notification information png_bg">
        <div> 欢迎使用名品渔具管理后台 </div>
      </div>
      <p>
        <label>用户名：</label>
        <input class="text-input" type="text" name="username" style="float:left"  />
      </p>
      <div class="clear"></div>
      <p>
        <label>密 &nbsp;&nbsp;&nbsp;码：</label>
        <input class="text-input" type="password" name="password" style="float:left"  />
      </p>
      <div class="clear"></div>
      <p>
      <label>验证码：</label>
        <input type="text" class="text-input" name="vcode" style="width:90px; float:left; margin-right:5px; margin-bottom:10px;" /><img id="vdimgck"  onclick="changeAuthCode()" style="cursor: pointer;" alt="看不清？点击更换" src="login/vcode"/>
      </p>
      <div class="clear"></div>
      <p>
        <input class="button" type="submit" value="登入" style=" margin-top:0px;" />
      </p>
    </form>
  </div>
</div>
</body>
</html>
