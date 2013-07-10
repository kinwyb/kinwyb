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
<script type="text/javascript">
var i=10;
$(document).ready(function(){
	$("#add").click(function(){
		var but=$(this);
		but.css("display","none");
		str='<tr><th><input class="check" type="checkbox" id="check" /></th><th><input type="text" id="name" size="25" /></th><th><input type="text" id="sp" size="3" /></th><th><input type="text" id="url" size="70" /><input type="button" value="添加" id="textadd" /></th></tr>';
		$("#rowinfo").append(str);
		$("#textadd").click(function(){
			var val=$("#name").val();
			var url=$("#url").val();
			var sp=$("#sp").val();
			$(this).parent("th").parent("tr").remove();
			$.post("<?php echo site_url('index/topadd');?>",{title:val,url:url,sp:sp},function(data){
				alert(data);
				location.reload();
			})
			but.css("display","");
		});
	});
	$("#editall").click(function(){
		if(confirm("确实要删除吗?"))
		{
			var data="";
			var type=$("#dropdown").val();
			if(type=="delete")
			{
				$("#check:checked").each(function(){
					data+=$(this).val()+",";
				});
				if(data=="" || data==null)
					alert("未选中任何栏目");
				else
					$.post("<?php echo site_url('index/topdel');?>",{data:data},function(data){
						alert(data);
						location.reload();
					});
			}
		}
		return false;
	});
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
    <div class="clear"></div>
    <div class="content-box">
      <div class="content-box-header">
        <h3>列表</h3>
        <div class="clear"></div>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab">
          <table>
            <thead>
              <tr>
                <th>
                  <input class="check-all" type="checkbox" />
                </th>
                <th>文本</th>
                <th>排序</th>
                <th>网址</th>
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
                    <a class="button" href="#" id="editall">确定</a> <a class="button" href="#" id="add">新增</a> </div>
                   
                  <div class="pagination">
                  </div>
                  <div class="clear"></div>
                </td>
              </tr>
            </tfoot>
            <tbody  id="rowinfo">
              <?php
			  if(!empty($row))
			  	foreach($row as $value)
					echo '<tr><th><input class="check" type="checkbox" value="'.$value['id'].'" id="check" /></th><th>'.$value['title'].'</th><th>'.$value['sp'].'</th><th>'.$value['url'].'</th></tr>';
			  ?>
            </tbody>
          </table>
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
