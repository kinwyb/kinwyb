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
<script type="text/javascript" src="<?php echo base_url('views/js/user.js');?>"></script>
<script type="text/javascript">var i=12</script>
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
    <!-- End .clear -->
    <div class="content-box">
      <!-- Start Content Box -->
      <div class="content-box-header">
        <h3>修改用户</h3>
        <div class="clear"></div>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
         <form action="<?php echo site_url('sql/user_edit_post/'.$info['uid']);?>" method="post">
            <fieldset>
            <p>
              <label>用户名<span style="margin-left: 180px">用户等级</span></label>
              <input class="text-input small3-input" type="text" name="username" value="<?php echo $info['username']?>" />
              <select name="role" class="small2-input">
	            <option value="1" <?php if($info['role'] == 1){ echo 'selected="selected"';}?>>评论管理</option>
	            <option value="2" <?php if($info['role'] == 2){ echo 'selected="selected"';}?>>文章管理</option>
	            <option value="4" <?php if($info['role'] == 4){ echo 'selected="selected"';}?>>系统管理</option>
	          </select> 
            </p>
            <p>
              <label>密码<span style="margin-left: 200px">重复密码</span></label>
              <input class="text-input small3-input" type="password" name="password" />
              <input class="text-input small3-input" type="password" name="password2" /></p>
             <p>
              <label>栏目权限设置</label>
              <table>
              <tbody>
              <?php 
              foreach($arcrole as $value)
              {
              	echo '<tr><td>'.$value['name'].'</td><td><input type="checkbox" name="arcrole'.$value['id'].'[0]" ';
              	if(empty($wpower[$value['id']][0])) echo 'checked="checked"';
              	echo ' />添加</td><td><input type="checkbox" name="arcrole'.$value['id'].'[1]" ';
              	if(empty($wpower[$value['id']][1])) echo 'checked="checked"';
              	echo ' />修改</td><td><input type="checkbox" name="arcrole'.$value['id'].'[2]" ';
              	if(empty($wpower[$value['id']][2])) echo 'checked="checked"';
              	echo '/>删除</td></tr>';
			  }?>
              </tbody>
              </table>
            </p>
             <p>
              <label>管理权限设置</label>
              <table>
              <tbody>
              <?php 
              foreach($adminrole as $value)
              {
              	echo '<tr><td>'.$value['menu_name'].'</td><td><input type="checkbox" name="role'.$value['menu_id'].'[0]" ';
              	if(empty($power[$value['menu_id']][0])) echo 'checked="checked"';
              	echo ' />添加</td><td><input type="checkbox" name="role'.$value['menu_id'].'[1]" ';
              	if(empty($power[$value['menu_id']][1])) echo 'checked="checked"';
              	echo ' />修改</td><td><input type="checkbox" name="role'.$value['menu_id'].'[2]" ';
              	if(empty($power[$value['menu_id']][2])) echo 'checked="checked"';
              	echo ' />删除</td></tr>';
			  }?>
              </tbody>
              </table>
            </p>
            <p>
              <input class="button" type="submit" value="添加" />
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
