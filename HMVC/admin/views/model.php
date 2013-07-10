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
<script type="text/javascript" src="<?php echo base_url('views/js/model.js');?>"></script>
<script>var i=2</script>
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
       <?php 
	  }
	  ?>
    <div class="clear"></div>
    <div class="content-box">
      <div class="content-box-header">
        <h3>模型</h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">模型列表</a></li>
          <li><a href="#tab2">添加新模型</a></li>
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
                <th>模型名称</th>
                <th>模型附加表</th>
                <th>模型类型</th>
                <th></th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <td colspan="6">
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
            <tbody  id="rowinfo">
              
            </tbody>
          </table>
        </div>
        <div class="tab-content" id="tab2">
          <form action="<?php echo site_url('modelview/modeladd');?>" method="post">
            <fieldset>
           <p>
              <label>模型名称</label>
              <input class="text-input small-input" type="text" name="name" />
            </p>
            <p>
              <label>模型附加表</label>
              <input class="text-input medium-input datepicker" type="text" name="addtable" />
              <span class="input-notification error png_bg" style="display:none;" id='egtst'>Error message</span> </p>
            <p>
              <label>模型类型</label>
              <select name="type" class="small-input">
                <option value="1">文章模型</option>
                <option value="2">产品模型</option>
                <option value="3">空白模型</option>
                <option value="4">测评模型</option>
                <option value="5">图集模型</option>
              </select>
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
