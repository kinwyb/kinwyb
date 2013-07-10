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
<script type="text/javascript" src="<?php echo base_url('views/js/jquery.form.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/js/arctype.js');?>"></script>
<script>var i=6;</script>
</head>
<body>
<div id="body-wrapper">
  <div id="sidebar">
    <?php
    $this->load->module('public/info_made/sys_left');
    ?>
  </div>
  <div id="main-content">
    <?php if(!empty($error))
	  {?>
      <div class="notification error png_bg"> <a href="#" class="close"><img src="<?php echo base_url('views/images/icons/cross_grey_small.png');?>" title="Close this notification" alt="close" /></a>
      <div> <?php echo validation_errors(); ?></div>
    </div>
    <?php }?>
    <div class="clear"></div>
    <div class="content-box">
      <div class="content-box-header">
        <h3>网站栏目</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">栏目列表</a></li>
          <li><a href="#tab2">添加新栏目</a></li>
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
                <th>ID</th>
                <th>栏目名称</th>
                <th>父级ID</th>
                <th>栏目数据表</th>
                <th>SEO名称</th>
                <th></th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="7">
                  <div class="bulk-actions align-left">
                    <select id="dropdown">
                      <option value="option1">请选择...</option>
                      <option value="delete">删除</option>
                    </select>
                    <a class="button" href="#" id="editall">确定</a> </div>
                  <div class="pagination"> 
                  </div>
                  <!-- End .pagination -->
                  <div class="clear"></div>
                </td>
              </tr>
            </tfoot>
            <tbody id="rowinfo">
            
            </tbody>
          </table>
        </div>
        <!-- End #tab1 -->
        <div class="tab-content" id="tab2">
        	<div class="notification attention png_bg"> <a href="#" class="close"><img src="<?php echo base_url('views/images/icons/cross_grey_small.png');?>" title="Close this notification" alt="close" /></a>
            <div> 红色星号所标识的为必填栏目. </div>
          </div>
          <form action="/admin/arctype/type_add" method="post">
            <fieldset>
             <p>
              <label>栏目名称 <span style="color:#F00">*</span> <span style="margin-left:225px;">SEO标题</span></label>
              <input class="text-input small-input" type="text"  name="name" />
              <input class="text-input small-input" type="text"  name="seotitle" /></p>
            <p>
              <label>栏目关键词<span style="margin-left:500px;">排序</span></label>
              <input class="text-input medium-input datepicker" type="text" name="keywords" />
              <input class="text-input small2-input" type="text" name="short" value="50"/></p>
            <p>
              <label>栏目描述</label>
              <input class="text-input large-input" type="text" id="large-input" name="description" />
            </p>
            <div style=" margin:10px 0px;">
             <div style="float:left; width:40%;"> 
             	<label style="margin-top:15px;">缩略图</label>
              	<input class="text-input small-input" type="file" id="fileupload" name="imgFile"/>
              	<input class="text-input small-input" type="hidden" id="litpic" name="litpict" />
              </div>
              <div style="float:left; width:60%; height:115px;">
        		<div id="showimg"></div>
                <div class="files"></div>
              </div>
            </div>
            <p>
              <label>父级栏目<span style="margin-left:230px;">栏目类型</span></label>
              <select name="reid" class="small-input">
              <option value="0">顶级</option>
              <?php foreach($arctype as $row)
			  {?>
                <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
               <?php }?>
              </select>
              <select name="addtable" class="small-input">
              <?php foreach($model as $row)
			  {?>
                <option value="<?php echo $row['addtable'];?>"><?php echo $row['name'];?></option>
               <?php }?>
              </select>
            </p>
            <p>
              <label>栏目内容</label>
              <textarea id="content" name="content" cols="79" rows="15" style="width:100%;height:400px;visibility:hidden;"></textarea>
            </p>
            <p>
              <input class="button" type="submit" value="提交" />
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
