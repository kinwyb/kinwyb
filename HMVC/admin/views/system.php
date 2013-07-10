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
<script>
	var i=3;
</script>
</head>
<body>
<div id="body-wrapper">
  <div id="sidebar">
    <?php $this->module('public/info_made/sys_left');?>
  </div>
  <div id="main-content">
    <div class="clear"></div>
    <?php if(!empty($error))
    {?>
    <div class="notification error png_bg"> <a href="#" class="close"><img src="<?php echo $image;?>/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
      <div> <?php echo validation_errors(); ?></div>
    </div>
    <?php }?>
    <div class="content-box">
      <div class="content-box-header">
        <h3>系统参数</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">网站设置</a></li>
          <li><a href="#tab2">水印设置</a></li>
          <li><a href="#tab3">其他设置</a></li>
        </ul>
<div class="clear"></div>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
          <form action="<?php echo site_url('index/system_post');?>" method="post">
            <fieldset>
            <p>
              <label>网站名称</label>
              <input class="text-input small-input" type="text" id="small-input" name="webname" value="<?php echo $sys->webname;?>" />
            </p>
            <p>
              <label>网站关键词</label>
              <input class="text-input medium-input datepicker" type="text" id="medium-input" name="keywords" value="<?php echo $sys->keywords;?>"/>
           </p>
            <p>
              <label>网站描述</label>
              <textarea class="text-input" name="description" cols="79" rows="15"><?php echo $sys->description;?></textarea>
            </p>
            <p>
              <input class="button" type="submit" value="修改" />
            </p>
            </fieldset>
            <div class="clear"></div>
          </form>
        </div>

        <div class="tab-content" id="tab2">
   		<div class="notification success png_bg"> <a href="#" class="close"><img src="<?php echo base_url('views/images/icons/cross_grey_small.png');?>" title="Close this notification" alt="close" /></a>
      		<div> 水印图片路径：/images/watermark.png (使用图片水印,服务器需开启GD图像库)<br />水印字体文件：/images/syimg.ttf <br />请提交修改后在查看水印效果</div>
      	</div>
    
          <form action="<?php echo site_url('index/syimg');?>" method="post">
            <fieldset>
            <p>
              <label>水印开关<span style="margin-left:90px;">水印类型</span></label>
              <input type="radio" name="syimg" <?php if($sys->syimg == 1) {echo 'checked="checked"';}?> value="1" />开<input type="radio" name="syimg" <?php if($sys->syimg == 0) {echo 'checked="checked"';}?> value="0" />关
              <input style="margin-left:70px;" type="radio" name="syimg_type" <?php if($sys->syimg_type == 1) {echo 'checked="checked"';}?> value="1" />图片<input type="radio" name="syimg_type" <?php if($sys->syimg_type == 0) {echo 'checked="checked"';}?> value="0" />文字
            </p>
            <p>
              <label>水印融合度<span style="margin-left:90px;">水印文本内容</span><span style="margin-left:230px;">水印方位</span></label>
              <input class="text-input small2-input" type="text" name="syimg_rh" value="<?php echo $sys->syimg_rh;?>" />
              <input class="text-input small-input" type="text" name="syimg_txt" value="<?php echo $sys->syimg_txt;?>" />
              <input class="text-input small2-input" type="text" name="syimg_fw" value="<?php echo $sys->syimg_fw;?>" /><a class="button" target="_blank" href="/admin/upload/syimg_test" >查看效果</a>
            </p>
            <p>
              <input class="button" type="submit" value="修改" /> 
            </p>
            </fieldset>
            <div class="clear"></div>
          </form>
        </div>
        
        <div class="tab-content" id="tab3">
          <form action="<?php echo site_url('index/syscache');?>" method="post">
            <fieldset>
            <p>
              <label>数据库缓存时间(秒)</label>
              <input class="text-input small2-input" type="text" id="small-input" name="cachetime" value="<?php echo $sys->cachetime;?>" />
            </p>
            <p>
              <label>页面缓存时间(分钟:可精确到秒n/60)</label>
              <input class="text-input small2-input" type="text" id="small-input" name="pagecache" value="<?php echo $sys->pagecache;?>" />
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
      &#169; Copyright 2010 Your Company | Powered by <a href="#">admin templates</a> | <a href="#">Top</a> </small>  消耗内存：{memory_usage} 消耗时间:{elapsed_time}</div>
  </div>
</div>
</body>
</html>
