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
<script type="text/javascript"> var i=<?php echo $arcrow['typeid']; ?></script>
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
					showimg.html("<img src='"+data.url+"' height=60 />");
					$("#litpic").val(data.url);
					$("#delimg").click(function(){
						var pic=$(this).attr("href");
						$.post("/admin/upload/delimg",{imagename:pic},function(data){
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
  <div id="sidebar">
    <?php 
    $this->load->module('public/info_made/arc_left');
    $arclist=$this->load->module('formck/formck_made/getlist',array($id),TRUE);
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
        <h3><?php echo $addtable; ?></h3>
<div class="clear"></div>
      </div>
      <!-- End .content-box-header -->
      <div class="content-box-content">
        <!-- End #tab1 -->
        <div class="tab-content default-tab" id="tab2">
          <form action="<?php echo site_url('dkarv/edit'); ?>" method="post">
          <input type="hidden" value="<?php echo $arcrow['id']; ?>" name="arcid" />
          <input type="hidden" value="<?php echo $addtable; ?>" name="addtable" id="thistable" />
            <fieldset>
            <?php
				if(!empty($addrow) && is_array($addrow))
				{
					$valcheck="";
					foreach($addrow as $row)
					{
						if(!empty($row['valcheck']))
							$valcheck.=$row['name'].':'.$row['tablename'].':'.$row['valcheck'].",";
						if($row['tablename']=='litpic')
						{
							echo ' <div style=" margin:10px 0px;">
             <div style="float:left; width:40%;"> 
             	<label style="margin-top:15px;">'.$row['name'].'</label>
              	<input class="text-input small-input" type="file" id="fileupload" name="imgFile"/>
              	<input class="text-input small-input" type="hidden" id="litpic" name="litpic" value="'.$arcrow[$row['tablename']].'" />
              </div>
              <div style="float:left; width:60%; height:115px;">
        		<div id="showimg"><img src="'.$arcrow[$row['tablename']].'" height="110" /></div>
                <div class="files"><a id="delimg" href="'.$arcrow[$row['tablename']].'">删除</a></div>
              </div>
            </div>';
							continue;
						}
						echo '<p><label>'.$row['name'].'</label>';
						switch($row['type'])
						{
							case 'select': echo '<select name='.$row['tablename'].' class="small2-input">';
									if(!empty($row['value']))
									{
										$value=explode(",",$row['value']);
										foreach($value as $r)
										{
											if($r==$arcrow[$row['tablename']])
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
               <label>所属栏目</label>
                 <select name="typeid" class="small2-input">
                  <?php foreach($arclist as $row)
                  {?>
                    <option value="<?php echo $row['id'];?>" <?php if($row['id']==$arcrow['typeid']){ echo 'selected="selected"';} ?>><?php echo $row['name'];?></option>
                   <?php }?>
                  </select></p>
            <p>
              <input class="button" type="submit" value="修改" />
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
