<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
<link rel="stylesheet" href="<?php echo base_url('views/css/reset.css');?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('views/css/style.css');?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('views/css/invalid.css');?>" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url('views/js/jquery.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/js/sql.js');?>"></script>
<script>var i=11;</script>
<style>
  #J_overlay {
    background: none repeat scroll 0 0 #000000;
    height: 100%;
    left: 0;
    opacity: 0.4;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 10000;
}
#J_overlay iframe {
    background: none repeat scroll 0 0 #000000;
    border: 0 none;
    height: 100%;
    left: 0;
    opacity: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: -1;
}
  </style>
</head>
<body>
<div id="body-wrapper">
  <div id="sidebar">
    <?php 
    $this->load->module('public/info_made/sys_left');
    ?>
  </div>
  <div id="main-content">
    <ul class="shortcut-buttons-set">
      <li><a class="shortcut-button" href="/admin/sql/backup?sd=<?php echo $num;?>" target="_blank"><span> <img src="<?php echo base_url('views/images/icons/pencil_48.png');?>" alt="icon" /><br />
      数据库备份</span></a></li>
      <li><a class="shortcut-button" href="<?php echo $num;?>" id="hy"><span> <img src="<?php echo base_url('views/images/icons/paper_content_pencil_48.png');?>" alt="icon" /><br />
      数据库还原</span></a></li>
      <li><a class="shortcut-button" href="<?php echo $num;?>" id="sq"><span> <img src="<?php echo base_url('views/images/icons/image_add_48.png');?>" alt="icon" /><br />
      执行sql语句</span></a></li>
      <li><a class="shortcut-button" href="<?php echo $num;?>" rel="yh" id="pst"><span> <img src="<?php echo base_url('views/images/icons/clock_48.png');?>" alt="icon" /><br />
      优化数据库 </span></a></li>
    </ul>
    <div class="clear"></div>

<div class="content-box"  style="display:none;">
      <div class="content-box-content">
        <div class="tab-content" id="tab1" style="display:none;">
          <table>
            <thead>
              <tr>
                <th>文件名</th>
                <th></th>
              </tr>
            </thead>
            <tbody  id="rowinfo">
            <?php
            if(!empty($file))
				foreach($file as $value)
					echo '<tr><td>'.$value.'</td><td><a href="/admin/sql/restore?file='.$value.'" target="_blank" id="hysj">还原</a> &nbsp; <a href="'.$value.'" id="scsj" >删除</a></td></tr>';
			?>
            </tbody>
          </table>
        </div>
        <div class="tab-content" id="tab2" style="display:none;">
        <form action="#" method="post">
        <fieldset>
          <p>
              <label>查询语句</label>
              <textarea class="text-input textarea wysiwyg" id="textarea" name="textfield" cols="79" rows="15"></textarea>
          </p>
          <p>
              <input class="button" type="button" value="运行" />
          </p>
          </fieldset>
          </form>
        </div>
        
      </div>
      <iframe src="#" id="commentsiframe" align="middle" frameborder="0" scrolling="no" width="100%"></iframe>
    </div>


    <div class="clear"></div>
    <div class="notification success png_bg" style="display:none"> <a href="#" class="close"><img src="<?php echo base_url('views/images/icons/cross_grey_small.png');?>" title="Close this notification" alt="close" /></a>
      <div id="true"> </div>
    </div>
    <div class="notification error png_bg" style="display:none"> <a href="#" class="close"><img src="<?php echo base_url('views/images/icons/cross_grey_small.png');?>" title="Close this notification" alt="close" /></a>
      <div id="error"></div>
    </div>
    <div id="footer"> <small>
      &#169; Copyright 2010 Your Company | Powered by <a href="#">admin templates</a> | <a href="#">Top</a>  消耗内存：{memory_usage} 消耗时间:{elapsed_time}</small> </div>
</div>
</div>
</body>
</html>
