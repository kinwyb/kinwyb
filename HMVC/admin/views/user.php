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
    <div class="content-box">
      <div class="content-box-header">
        <h3>用户管理</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">用户列表</a></li>
          <li><a href="#tab2">添加新用户</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
          <table>
            <thead>
              <tr>
                <th>用户名</th>
                <th>上次登入IP</th>
                <th>用户等级</th>
                <th></th>
              </tr>
            </thead>
            <tbody  id="rowinfo">
              <?php
			  if(!empty($row))
			  	foreach($row as $value)
				{
					echo '<tr><td>',$value['username'],'</td><td>',$value['ip'],'</td><td>',$value['role'],'</td><td>';
					if($value['start'] ==1)
						echo '<a href="',$value['uid'],'" rel="tz"><img src="',base_url('views/images/ks.png'),'"></a>';
					else
						echo '<a href="',$value['uid'],'" rel="ks"><img src="',base_url('views/images/tz.png'),'"></a>';
					echo '&nbsp;&nbsp;&nbsp;<a href="',site_url('sql/edit_user/'.$value['uid']),'" rel="ed"><img src="',base_url('views/images/icons/pencil.png'),'"></a>&nbsp;&nbsp;&nbsp;<a href="',$value['uid'],'" rel="del"><img src="',base_url('views/images/icons/cross.png'),'"></a></td></tr>';
				}
			  ?>
            </tbody>
          </table>
        </div>
        <div class="tab-content" id="tab2">
          <form action="<?php echo site_url('sql/user_post/add');?>" method="post">
            <fieldset>
            <p>
              <label>用户名<span style="margin-left: 180px">用户等级</span></label>
              <input class="text-input small3-input" type="text" name="username" />
              <select name="role" class="small2-input">
	            <option value="1">评论管理</option>
	            <option value="2">文章管理</option>
	            <option value="4">系统管理</option>
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
              	echo '<tr><td>'.$value['name'].'</td><td><input type="checkbox" name="arcrole'.$value['id'].'[0]" />添加</td><td><input type="checkbox" name="arcrole'.$value['id'].'[1]" />修改</td><td><input type="checkbox" name="arcrole'.$value['id'].'[2]" />删除</td></tr>';
              ?>
              </tbody>
              </table>
            </p>
             <p>
              <label>管理权限设置</label>
              <table>
              <tbody>
              <?php 
              foreach($adminrole as $value)
              	echo '<tr><td>'.$value['menu_name'].'</td><td><input type="checkbox" name="role'.$value['menu_id'].'[0]" />添加</td><td><input type="checkbox" name="role'.$value['menu_id'].'[1]" />修改</td><td><input type="checkbox" name="role'.$value['menu_id'].'[2]" />删除</td></tr>';
              ?>
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
