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
<script type="text/javascript" src="<?php echo base_url('views/js/jquery.form.js');?>"></script>
<script charset="utf-8" src="<?php echo base_url('views/js/edit.js');?>"></script>
<script>
var i=6;
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
</script>
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
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
        	<div class="notification attention png_bg"> <a href="#" class="close"><img src="<?php echo base_url('views/images/icons/cross_grey_small.png');?>" title="Close this notification" alt="close" /></a>
            <div> 红色星号所标识的为必填栏目. </div>
          </div>
          <form action="/admin/arctype/edit_post" method="post">
            <fieldset>
            <input type="hidden" name="id" value="<?php echo $info['id'] ?>" />
            <p>
              <label>栏目名称 <span style="color:#F00">*</span> <span style="margin-left:225px;">seo标题</span></label>
              <input class="text-input small-input" type="text"  name="name" value="<?php echo $info['name'];?>" />
              <input class="text-input small-input" type="text"  name="seotitle" value="<?php echo $info['seotitle'];?>" /></p>
            <p>
              <label>栏目关键词<span style="margin-left:500px;">排序</span></label>
              <input class="text-input medium-input datepicker" type="text" name="keywords" value="<?php echo $info['keywords'] ?>"  />
              <input class="text-input small2-input" type="text" name="short" value="<?php echo $info['short'];?>"/>
              <p>
              <label>栏目描述</label>
              <input class="text-input large-input" type="text" id="large-input" name="description"  value="<?php echo $info['description'] ?>"  />
            </p>
            <div style=" margin:10px 0px;">
             <div style="float:left; width:40%;"> 
             	<label style="margin-top:15px;">缩略图</label>
              	<?php
              	if(!empty($info['litpict']))
					echo '<input class="text-input small-input" type="file" id="fileupload" name="imgFile" value="'.$info['litpict'].'"/>
              		<input class="text-input small-input" type="hidden" id="litpic" name="litpic" value="'.$info['litpict'].'" />';
			  	else
					echo '<input class="text-input small-input" type="file" id="fileupload" name="imgFile"/>
              		<input class="text-input small-input" type="hidden" id="litpic" name="litpict" />';
				?>
              </div>
              <div style="float:left; width:60%; height:115px;">
        		<?php
              	if(!empty($info['litpict']))
				{
			  ?>
        		<div id="showimg"><img src="<?php echo $info['litpict']; ?>" height="110" /></div>
                <div class="files"><a id='delimg' href="<?php echo $info['litpict']; ?>">删除</a></div>
                <? }else{ ?>
                <div id="showimg"></div>
                <div class="files"></div>
                <?php }?>
              </div>
            </div>
            <p>
              <label>父级栏目</label>
               <select name="reid" class="small-input">
              <option value="0">顶级</option>
              <?php foreach($arctype as $row)
			  {?>
                <option value="<?php echo $row['id'];?>" <?php if($info['topid'] == $row['id']){ echo 'selected="selected"';} ?>><?php echo $row['name'];?></option>
               <?php }?>
              </select>
            </p>
            <p>
              <label>栏目内容</label>
              <textarea id="content" name="content" cols="79" rows="15" style="width:100%;height:400px;visibility:hidden;"><?php echo $info['content']; ?></textarea>
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
