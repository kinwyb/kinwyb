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
<script type="text/javascript" src="<?php echo base_url('views/js/chepin.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/js/timer/WdatePicker.js');?>"></script>
<script>var i=<?php echo $id; ?></script>
</head>
<body>
<div id="body-wrapper">
<input type="hidden" value="<?php echo $id; ?>" id="thisid" />
  <div id="sidebar">
    <?php 
    $this->load->module('public/info_made/arc_left');
    $shop=$this->load->module('formck/formck_made/getshop',array(),TRUE);
    $pinpai=$this->load->module('formck/formck_made/getpinpai',array(),TRUE);
    $arclist=$this->load->module('formck/formck_made/getlist',array($id),TRUE);
    $addrow=$this->load->module('formck/formck_made/getaddrow',array($addtable),TRUE);
    ?>
  </div>
  <div id="main-content">
  <?php 
  if(!empty($error))
	  {?>
      <div class="notification error png_bg"> <a href="#" class="close"><img src="<?php echo base_url('views/images/icons/cross_grey_small.png');?>" title="Close this notification" alt="close" /></a>
      <div> <?php echo validation_errors(); ?></div>
    </div>
    <?php }?>
    <div class="clear"></div>
    <div class="content-box">
      <div class="content-box-header">
        <h3><?php echo $addtable;?></h3>
        <ul class="content-box-tabs">
          <li><a href="#tab1" class="default-tab">列表</a></li>
          <li><a href="#tab2">添加新内容</a></li>
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
                <th>文章名称</th>
                <th>排序</th>
                <th>发布时间</th>
                <th>作者</th>
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
                      <option value="chged">移动</option>
                    </select>
                    <a class="button" href="#" id="editall">确定</a> </div>
                  <div class="pagination"></div>
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
          <form action="<?php echo site_url('arclist/arc_add'); ?>" method="post">
          	<input type="hidden" value="<?php echo $addtable; ?>" name="addtable" id="thistable" />
            <fieldset>
            <p>
              <label>标题 <span style="color:#F00">*</span> <span style="margin-left:260px;">排序</span></label>
              <input class="text-input small-input" type="text" id="small-input" name="title" /><input class="text-input small2-input" type="text" name="short" value="50"/>
              <input type="radio" name="flag"  value="1"/>一级推荐
              <input type="radio" name="flag"  value="2"/>二级推荐
           	  <input type="radio" name="flag"  value="3"/>三级推荐
            </p>
            <p>
              <label>作者 <span style="margin-left:280px;">发布时间</span></label>
              <input class="text-input small-input" type="text" id="small-input" name="writer" />
              <input class="small3-input Wdate" type="text" name="update" value="<?php echo date('Y-m-d H:i:s');?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',skin:'twoer'})"/>
            </p>
            <p>
               <label>所属栏目 <span style="margin-left:90px;">所属品牌</span></label>
                 <select name="typeid" class="small2-input">
                  <?php foreach($arclist as $row)
                  {?>
                    <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                   <?php }?>
                  </select>
                  <select name="pinpai" class="small2-input">
                  	<option value="0">无</option>
                  <?php foreach($pinpai as $row)
                  {?>
                    <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                   <?php }?>
                  </select>
                  </p>
                   <p>
               <label>所属商品栏目 <span style="margin-left:90px;display:none" id="name_shop_id">商品名称</span></label>
                 <select class="small2-input" id="type_shop_id" name="shop">
                 <option value="0">请选择</option>
                 <?php 
                 foreach($shop as $value)
                 	echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                 ?>
                  </select>
                  <select name="shopid" class="small2-input" style="display:none" id="shopid">
                  </select>
                  </p>
            <div style=" margin:10px 0px;">
             <div style="float:left; width:40%;"> 
             	<label style="margin-top:15px;">缩略图</label>
              	<input class="text-input small-input" type="file" id="fileupload" name="imgFile"/>
              	<input class="text-input small-input" type="hidden" id="litpic" name="litpic" />
              </div>
              <div style="float:left; width:60%; height:115px;">
        		<div id="showimg"></div>
                <div class="files"></div>
              </div>
            </div>
            <?php
				if(!empty($addrow) && is_array($addrow))
				{
					$valcheck="";
					foreach($addrow as $row)
					{
						if(!empty($row['valcheck']))
							$valcheck.=$row['name'].':'.$row['tablename'].':'.$row['valcheck'].",";
						echo '<p><label>'.$row['name'].'</label>';
						switch($row['type'])
						{
							case 'select': echo '<select name='.$row['tablename'].' class="small2-input">';
									if(!empty($row['value']))
									{
										$value=explode(",",$row['value']);
										foreach($value as $r)
											echo '<option value="'.$r.'">'.$r.'</option>';
									}
									echo '</select>';
								break;
								
							case 'checkbox':
									if(!empty($row['value']))
									{
										$value=explode(",",$row['value']);
										foreach($value as $r)
											echo '<input type="checkbox" name="'.$row['tablename'].'[]"  value="'.$r.'"/>'.$r;
									}
									break;
							case 'textarea' : echo '<textarea name="'.$row['tablename'].' class="text-input textarea"></textarea>';
									break;
							case 'text' : echo '<textarea name="'.$row['tablename'].'" id="content" cols="79" rows="15" style="width:100%;height:400px;visibility:hidden;"></textarea>';
									break;
								default : echo '<input type="text" name="'.$row['tablename'].'" class="text-input small-input"/>';
									break;
						}
						echo '<p>';
					}
					echo '<input type="hidden" name="valcheck" value="'.$valcheck.'" />';;
				}
			?>
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
      &#169; Copyright 2010 Your Company | Powered by <a href="#">admin templates</a> | <a href="#">Top</a> </small> 消耗内存：{memory_usage} 消耗时间:{elapsed_time}</div>
</div>
</div>
</body>
</html>