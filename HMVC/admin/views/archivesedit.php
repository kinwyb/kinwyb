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
<script type="text/javascript" src="<?php echo base_url('views/js/timer/WdatePicker.js');?>"></script>
<script>var i=<?php echo $arcrow['typeid']; ?></script>
<script>
$(function () {
	var showimg = $('#showimg');
	var files = $(".files");
	$("#fileupload").wrap("<form id='myupload' action='<?php echo site_url('upload/imgup');?>' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function(){
		$("#myupload").ajaxSubmit({
			dataType:  'json',
			success: function(data) {
				if(!data.error)
				{
					files.html("<a id='delimg' href='"+data.url+"'>删除</a>");
					showimg.html("<img src='"+data.url+"' height=110 />");
					$("#litpic").val(data.url);
					$("#delimg").click(function(){
						var pic=$(this).attr("href");
						$.post("<?php echo site_url('upload/delimg');?>",{imagename:pic},function(data){
							if(data){
								files.html("删除成功.");
								showimg.html("");
							}else{
								alert("删除失败");
							}
						});
						return false;
					});
				}
			},
			error:function(xhr){
				alert("上传失败"+xhr.responseText);
			}
		});
	});
    $("#delimg").click(function(){
		var pic=$(this).attr("href");
		$.post("<?php echo site_url('upload/delimg');?>",{imagename:pic},function(data){
			if(data){
				files.html("删除成功.");
				$("#litpic").val("");
				showimg.html("");
			}else{
				alert("删除失败");
			}
		});
		return false;
	});
});
</script>
</head>
<body>
<div id="body-wrapper">
<input type="hidden" value="<?php echo $id; ?>" id="thisid" />
  <div id="sidebar">
    <?php 
    $this->load->module('public/info_made/arc_left');
    $pinpai=$this->load->module('formck/formck_made/getpinpai',array(),TRUE);
  	$arclist=$this->load->module('formck/formck_made/getlist',array($arcrow['typeid']),TRUE);
 	$shop=$this->load->module('formck/formck_made/getshop',array(),TRUE);
 	$addrow=$this->load->module('formck/formck_made/getaddrow',array($addtable),TRUE);
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
        <h3>内容修改</h3>
        <div class="clear"></div>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab2">
          <form action="<?php echo site_url('arclist/edit_post'); ?>" method="post">
            <input type="hidden" value="<?php echo $arcrow['id']; ?>" name="arcid" />
          	<input type="hidden" value="<?php echo $addtable; ?>" name="addtable" id="thistable" />
            <fieldset>
            <p>
              <label>标题 <span style="color:#F00">*</span> <span style="margin-left:260px;">排序</span></label>
              <input class="text-input small-input" type="text" value="<?php echo $arcrow['title']?>" name="title" /><input class="text-input small2-input" type="text" value="<?php echo $arcrow['short']?>" name="short" value="50"/>
              <input type="radio" name="flag"  value="1" <?php if($arcrow['flag'] == 1){?> checked="checked" <?php }?>/>一级推荐
              <input type="radio" name="flag"  value="2" <?php if($arcrow['flag'] == 2){?> checked="checked" <?php }?>/>二级推荐
           	  <input type="radio" name="flag"  value="3" <?php if($arcrow['flag'] == 3){?> checked="checked" <?php }?>/>三级推荐
            </p>
            <p>
              <label>作者 <span style="margin-left:280px;">发布时间</span></label>
              <input class="text-input small-input" type="text" value="<?php echo $arcrow['writer'];?>" name="writer" />
              <input class="small3-input Wdate" type="text" name="update" value="<?php echo date('Y-m-d H:i:s',$arcrow['update']);?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',skin:'twoer'})"/>
            </p>
            <p>
               <label>所属栏目 <span style="margin-left:90px;">所属品牌</span><span style="margin-left:90px;">所属商品</span></label>
                 <select name="typeid" class="small2-input">
                  <?php foreach($arclist as $row)
                  {?>
                    <option value="<?php echo $row['id'];?>" <?php if($row['id']==$arcrow['typeid']){ echo 'selected="selected"';}?>><?php echo $row['name'];?></option>
                   <?php }?>
                  </select>
                   <select name="pinpai" class="small2-input">
                  	<option value="0">无</option>
                  <?php foreach($pinpai as $row)
                  {?>
                    <option value="<?php echo $row['id'];?>" <?php if($row['id']==$arcrow['pinpai']){ echo 'selected="selected"';}?>><?php echo $row['name'];?></option>
                   <?php }?>
                  </select>
                  <select name="shop" class="small2-input">
                  	<option value="0">无</option>
                  <?php foreach($shop as $row)
                  {?>
                    <option value="<?php echo $row['id'];?>" <?php if($row['id']==$arcrow['shop']){ echo 'selected="selected"';}?>><?php echo $row['name'];?></option>
                   <?php }?>
                  </select></p>
            <div style=" margin:10px 0px;">
             <div style="float:left; width:40%;"> 
             	<label style="margin-top:15px;">缩略图</label>
              	<?php
              	if(!empty($arcrow['litpic']))
					echo '<input class="text-input small-input" type="file" id="fileupload" name="imgFile" value="'.$arcrow['litpic'].'"/>
              		<input class="text-input small-input" type="hidden" id="litpic" name="litpic" value="'. $arcrow['litpic'].'" />';
				else 
					echo '<input class="text-input small-input" type="file" id="fileupload" name="imgFile"/>
              		<input class="text-input small-input" type="hidden" id="litpic" name="litpic" />';
				?>
              </div>
              <div style="float:left; width:60%; height:115px;">
        		<?php
              	if(!empty($arcrow['litpic']))
				{
			  ?>
        		<div id="showimg"><img src="<?php echo $arcrow['litpic']; ?>" height="110" /></div>
                <div class="files"><a id='delimg' href="<?php echo $arcrow['litpic']; ?>">删除</a></div>
                <? }else{ ?>
                <div id="showimg"></div>
                <div class="files"></div>
                <?php }?>
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
										{
											if($r==$row['row'])
												echo '<option value="'.$r.'" selected="selected">'.$r.'</option>';
											else
												echo '<option value="'.$r.'">'.$r.'</option>';
										}
									}
									echo '</select>';
								break;
								
							case 'checkbox':
									if(!empty($row['value']))
									{
										$value=explode(",",$row['value']);
										$n=trim($row['row'],",");
										$n=explode(",",$n);
										foreach($value as $r)
										{
											if(in_array($r,$n))
												echo '<input type="checkbox" checked="checked" name="'.$row['tablename'].'[]"  value="'.$r.'"/>'.$r;
											else
												echo '<input type="checkbox" name="'.$row['tablename'].'[]"  value="'.$r.'"/>'.$r;
										}
										unset($n);
									}
									break;
							case 'textarea' : echo '<textarea name="'.$row['tablename'].' class="text-input textarea">'.$arcrow[$row['tablename']].'</textarea>';
									break;
							case 'text' : echo '<textarea name="'.$row['tablename'].'" id="content" cols="79" rows="15" style="width:100%;height:400px;visibility:hidden;">'.$arcrow[$row['tablename']].'</textarea>';
									break;
								default : echo '<input type="text" name="'.$row['tablename'].'" class="text-input small-input" value="'.$arcrow[$row['tablename']].'"/>';
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
