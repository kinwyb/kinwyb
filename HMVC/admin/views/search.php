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
<script type="text/javascript" src="<?php echo base_url('views/js/search.js');?>"></script>
<script type="text/javascript">var i=7</script>
</head>
<body>
<div id="body-wrapper">
  <div id="sidebar">
  <?php 
  $this->load->module('public/info_made/sys_left');
  ?>
  </div>
  <div id="main-content">
    <div class="content-box">
      <div class="content-box-header">
        <h3>搜索</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">搜索列表</a></li>
          <li><a href="#tab2">添加新搜索</a></li>
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
                <th>附加表</th>
                <th></th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="3">
                  <div class="bulk-actions align-left">
                   <select id="dropdown">
                      <option value="option1">请选择...</option>
                      <option value="delete">删除</option>
                    </select>
                    <a class="button" href="#" id="editall">确定</a> </div>
                  <div class="pagination">
                  </div>
                  <div class="clear"></div>
                </td>
              </tr>
            </tfoot>
            <tbody  id="rowinfo">
              
            </tbody>
          </table>
        </div>
        <div class="tab-content" id="tab2">
          <form action="<?php echo site_url('search/add_post');?>" method="post">
            <fieldset>
             <p>
              <label>商品类目</label>
              <select name="chage" class="small-input" id="chage">
              <option>无</option>
              <?php
			  	foreach($model as $value)
					echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
			  ?>   
              </select>
            </p>
            <p>
              <label>价格</label>
              <input class="text-input large-input" type="text" id="large-input" name="price" />
            </p>
            <div id="tableinfo">
            </div>
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
