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
 var i=500;
 $(document).ready(function(){
	$("[id=del]").click(function(){
			if(confirm("确实要删除吗?"))
			{
				data=$(this).parent().parent("tr").find("#check").val();
				var addtable=$(this).attr("href");
				$.post("<?php echo site_url('arclist/arcdel');?>",{data:data,addtable:addtable},function(data){
					if(data)
						alert("删除成功");
					else
						alert("删除失败！");
				})
			}
	});	
});
</script>
</head>
<body>
<div id="body-wrapper">
  <div id="sidebar">
    <?php 
    $this->load->module('public/info_made/arc_left');
    ?>
  </div>
  <div id="main-content">
    <div class="content-box">
      <div class="content-box-header">
        <h3>搜索条件</h3>
      </div>
      <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
       		<form action="<?php echo site_url('search/search_admin/result');?>" method="post">
            <fieldset>
            <p>
              <label>搜索内容<span style="margin-left:250px;">搜索范围</span></label>
              <input class="text-input small-input" type="text" name="kwd" />
              <select name="type" class="small2-input">
                <option value="allarc">文章</option>
                <option value="allcp">测评</option>
                <option value="allimg">测评</option>
                <option value="allshop">商品</option>
              </select>
               <input class="button" type="submit" value="搜索" />
              </p>
            </fieldset>
            <div class="clear"></div>
          </form>
        </div>
      </div>

    </div>
    <div class="clear"></div>
    <?php
		if(!empty($result))
		{
			echo ' 
		<div class="content-box" id="content">
      <div class="content-box-header">
        <h3>搜索结果</h3>
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
                <th>标题</th>
                <th>栏目</th>
                <th>时间</th>
                <th>作者</th>
                <th></th>
              </tr>
            </thead>
            <tbody  id="rowinfo">';
			foreach($result as $value)
			{
				if($turl == 'product')
					$turl='/product/'.$value['addtable'];
				echo '<tr><td><input type="checkbox" value='.$value['id'].' id="check"/></td><td><a href="'.$turl.'/'.$value['id'].'" target="_blank">'.$value['title'].'</a></td><td>'.$value['arctypename'].'</td><td>'.date("Y-m-d H:i:s",$value['update']).'</td><td>'.$value['writer'].'</td><td><a href="/admin/'.$addtable.'/edit/'.$value['addtable'].'/'.$value['id'].'" id="edit"><img src="/admin/views/images/icons/pencil.png" alt="Edit" /></a> <a href="'.$value['addtable'].'" title="Delete" id="del"><img src="/admin/views/images/icons/cross.png" alt="Delete" /></a></td></tr>';
			}
			echo '
            </tbody>
          </table>
        </div>
      </div>
    </div>';
		}
	?>
   
    <div class="clear"></div>
    <div id="footer"> <small>
      &#169; Copyright 2010 Your Company | Powered by <a href="#">admin templates</a> | <a href="#">Top</a>  消耗内存：{memory_usage} 消耗时间:{elapsed_time}</small> </div>
  </div>
</div>
</body>
</html>
