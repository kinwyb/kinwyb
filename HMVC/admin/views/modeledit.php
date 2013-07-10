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
<script charset="utf-8" src="<?php echo base_url('views/js/kindeditor/kindeditor-min.js');?>"></script>
<script charset="utf-8" src="<?php echo base_url('views/js/kindeditor/lang/zh_CN.js');?>"></script>
<script charset="utf-8" src="<?php echo base_url('views/js/edit.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/js/edittable.js');?>"></script>
<script>var i=2;</script>
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
        <h3><?php 
		$addtable=$info['table'];
		echo $addtable['addtable'];
		unset($info['table']);
		?>模型字段管理</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">字段列表</a></li>
          <li><a href="#tab2">添加字段</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>字段名称</th>
                <th>字段类型</th>
                <th></th>
              </tr>
            </thead>
            <tfoot>
            </tfoot>
            <tbody  id="rowinfo">
            <?php 
			$this->config->load('addtable');
			$table=$this->config->item($addtable['type'],'addtable');
			$name=array("aid"=>'文章ID',"typeid"=>'类别ID');
			if(!empty($table) && is_array($table))
			{
				foreach($table as $key=>$row)
				{
					
					echo '<tr><td><input type="checkbox"  id="check"/></td><td>'.$name[$key].'</td><td>'.$row['type'].'</td><td></td></tr>';
				}
			}
			unset($name);
			  if(!empty($info) && is_array($info))
			  {
				  foreach($info as $row)
				  {
					  echo '<tr><td><input type="checkbox" value="'.$row['id'].'" id="check"/></td><td>'.$row['name'].'</td><td>'.$row['type'].'</td><td><a href="#" title="Delete" id="del"><img src="/admin/views/images/icons/cross.png" alt="Delete" /></a></td></tr>';
				  }
			  }
			  ?>
            </tbody>
          </table>
        </div>
        <!-- End #tab1 -->
        <div class="tab-content" id="tab2">
          <form action="<?php echo site_url('modelview/table_add');?>" method="post">
          	<input type="hidden" value="<?php echo $addtable['addtable']; ?>" name="table" />
            <input type="hidden" value="<?php echo $addtable['id']; ?>" name="model_id" id="model_id" />
            <fieldset>
            <p>
              <label>字段名称</label>
              <input class="text-input small-input" type="text" name="name" /></p>
            <p>
              <label>字段标记</label>
              <input class="text-input small-input" type="text" name="tablename" /><input type="checkbox" name="notnull" />必填</p>
            <p>
              <label>字段大小</label>
              <input class="text-input small-input" type="text" name="tablelong" /></p>
            <p>
              <label>字段类型</label>
              <select name="type" class="small-input" id="typesel">
                <?php 
				if(!empty($kv) && is_array($kv))
					foreach($kv as $row)
						echo '<option value="'.$row['k'].'">'.$row['v'].'</option>';
				?>
              </select>
            </p>
            <p>
              <label>验证类型</label>
              <select name="valcheck" class="small-input" id="typesel">
              <option value="">不验证</option>
                <?php 
				if(!empty($ckv) && is_array($ckv))
					foreach($ckv as $row)
						echo '<option value="'.$row['k'].'">'.$row['v'].'</option>';
				?>
              </select>
            </p>
            <p id="typeselect">
              <label>枚举列表</label>
              <input class="text-input small-input" type="text" name="value" /> 各选项之间用 , 分隔开(eg:男,女)
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
