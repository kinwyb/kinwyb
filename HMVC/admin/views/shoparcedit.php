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
<script type="text/javascript" src="<?php echo base_url('views/js/timer/WdatePicker.js');?>"></script>
<script>var i=<?php echo $arcrow['typeid'];?></script>
<script>
$(document).ready(function(){
	//响应文件添加成功事件
	$("#inputfile").change(function(){
		//创建FormData对象
		var data = new FormData();
		//为FormData对象添加数据
		$.each($('#inputfile')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});
		$(".loading").show();	//显示加载图片
		//发送数据
		$.ajax({
			url:<?php echo site_url('upload/imgsup');?>,
			type:'POST',
			data:data,
			cache: false,
			contentType: false,		//不可缺参数
			processData: false,		//不可缺参数
			datatype:"json",
			success:function(data){
				//alert(data.instid);
				row=eval('(' +data+ ')');
				for(i=0;i<row.length;i++)
				{
					if(row[i]['error']==0)
					{
						$("#feedback").append("<div class='showimg' id='1'><div><img src='"+row[i]['url']+"' height='100' /></div><div style='width:240px;' id='2'>描述<input type='text' value='' name='imgkwdn[]'/><input type='hidden' value='"+row[i]['url']+"' name='imgurln[]' class=''/><br /><a href='"+row[i]['url']+"' id='delimg'>[删除]</a></div></div>")
					}else
					{
						$("#feedback").append("<div class='error'>上传错误"+row[i]['msg']+"</div>");
					}
				}
				$("[id=delimg]").click(function(){
					var pic=$(this).attr("href");
					var div=$(this).parent().parent();
						$.post("<?php echo site_url('upload/delimgs');?>",{imagename:pic},function(data){
							if(data){
								div.remove();
							}else{
								alert("删除失败");
							}
						});
					return false;
				});
				$(".loading").hide();
			},
			error:function(){
				alert('上传出错');
				$(".loading").hide();	//加载失败移除加载图片
			}
		});
	});
	
	$("[id=delimg]").click(function(){
					var pic=$(this).attr("href");
					var div=$(this).parent().parent();
						$.post("<?php echo site_url('upload/delimgs');?>",{imagename:pic},function(data){
							if(data){
								div.remove();
							}else{
								alert("删除失败");
							}
						});
					return false;
				});
});

</script>
<style>
.loading{display:none;background:url("<?php echo base_url('views/images/loading.gif')?>") no-repeat scroll 0 0 transparent;padding:8px;margin:18px 0 0 18px;}
.showimg{height:180px; width:240px; text-align:center; margin-top:10px; margin-bottom:5px; float:left;}
</style>
</head>
<body>
<div id="body-wrapper">
  <div id="sidebar">
    <?php 
    $this->load->module('public/info_made/arc_left');
    $pinpai=$this->load->module('formck/formck_made/getpinpai',array(),TRUE);
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
    <div class="content-box">
      <div class="content-box-header">
        <h3>商品</h3>
<div class="clear"></div>
<?php
	 $flag=explode(",",$arcrow['flag']);
?>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab2">
          <div class="notification attention png_bg"> <a href="#" class="close"><img src="<?php echo base_url('views/images/icons/cross_grey_small.png');?>" title="Close this notification" alt="close" /></a>
            <div> 红色星号的栏目为必填栏目. </div>
          </div>
          <form action="<?php echo site_url('imgarc/edit_imgs');?> method="post">
          <input type="hidden" value="<?php echo $arcrow['id']; ?>" name="arcid" />
          	<input type="hidden" value="<?php echo $addtable; ?>" name="addtable" id="thistable" />
             <fieldset>
            <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
            <p>
              <label>标题 <span style="color:#F00">*</span> <span style="margin-left:260px;">排序</span></label>
              <input class="text-input small-input" type="text" value="<?php echo $arcrow['title']?>" name="title" /><input class="text-input small2-input" type="text" value="<?php echo $arcrow['short']?>" name="short" value="50"/>
              <input type="radio" name="flag"  value="h" <?php if($arcrow['flag']==1){?> checked="checked" <?php }?>/>头条[h]
              <input type="radio" name="flag"  value="c" <?php if($arcrow['flag']==2){?> checked="checked" <?php }?>/>置顶[c]
           	  <input type="radio" name="flag"  value="p" <?php if($arcrow['flag']==3){?> checked="checked" <?php }?>/>推荐[p]
            </p>
            <p>
              <label>作者 <span style="margin-left:280px;">发布时间</span></label>
              <input class="text-input small-input" type="text" value="<?php echo $arcrow['writer'];?>" name="writer" />
              <input class="small3-input Wdate" type="text" name="update" value="<?php echo date('Y-m-d H:i:s',$arcrow['update']);?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd H:m:s',skin:'twoer'})"/>
            </p>
            <p>
               <label>所属品牌 <span style="margin-left:110px;">价格</span></label>
                   <select name="pinpai" class="small2-input">
                  	<option value="0">无</option>
                  <?php foreach($pinpai as $row)
                  {?>
                    <option value="<?php echo $row['id'];?>" <?php if($row['id']==$arcrow['pinpai']){ echo 'selected="selected"';}?>><?php echo $row['name'];?></option>
                   <?php }?>
                  </select><input class="text-input small2-input" type="text" id="small-input" name="price" value="<?php echo $arcrow['price']; ?>" /></p>
            <div style=" margin:10px 0px; width:100%;">
             	<div><input class="button" type="button" value="添加图片" onclick="getElementById('inputfile').click()"/><input type="file" multiple="multiple" id="inputfile" style="height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;"/><span class="loading"></span></div>
             	<div id="feedback" style="width:100%; height:auto;">
                <?php foreach($imgs as $value) 
				{?>
                	<div class='showimg' id='1'><div><img src='<?php echo $value['imgurl'] ?>' height='100' /></div><div style='width:240px;' id='2'>描述<input type='text' value='<?php echo $value['art'] ?>' name='imgkwd[]'/><input type='hidden' value='<?php echo $value['imgurl'] ?>' name='imgurl[]' class=''/><br /><a href='<?php echo $value['imgurl'] ?>' id='delimg'>[删除]</a></div></div>
                <?php }?>
                
                </div>
            </div>
            <div class="clear"></div>
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
