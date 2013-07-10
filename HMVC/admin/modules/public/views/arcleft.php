<script>
$(document).ready(function(){
	if(i!=0)
	{
		$('#main-nav').children("li").children("a").removeClass("current");
		$('#k_'+i).addClass("current");
		//alert($("#k_"+i).parents("li").text());
		$('#k_'+i).parent("li").parent("ul").parent("li").children("a").addClass("current");
		$('#k_'+i).parent("li").parent("ul").parent("li").children("ul").css("display","block");
	}
});
</script>
<div id="sidebar-wrapper">
      <!-- Sidebar with logo and menu -->
      <h1 id="sidebar-title"><a href="#">Simpla Admin</a></h1>
      <!-- Logo (221px wide) -->
      <a href="#"><img id="logo" src="<?php echo base_url('views/images/logo.png');?>" alt="Simpla Admin logo" /></a>
      <!-- Sidebar Profile links -->
      <div id="profile-links"> 你好,<?php echo $this->session->userdata('username');?><br />
        <br />
        <a href="/" target="_blank" title="View the Site" >网站首页</a> | <a href="<?php echo site_url('login/login_out');?>" title="Sign Out">退出</a> </div>
      <ul id="main-nav">
      <li> <a href="<?php echo site_url('index/feedback');?>" class="nav-top-item no-submenu" id="k_1000" >举报评论页</a></li>
        <!-- Accordion Menu -->
         <?php
		 if(!empty($left))
		 {
		 	echo '<li> <a href="#" class="nav-top-item " id="" >产品类目</a><ul>';
		 	foreach ($left as $key => $row)
		 		if($row['seotitle'] == '商品')
		 		{
		 			echo '<li> <a href="/admin/arclist/arclist_add/'.$row['addtable'].'/'.$row['id'].'"  id="k_'.$row['id'].'" >'.$row['name'].'</a>';
		 			unset($left[$key]);
		 		}
		 	echo '</ul></li>';
			foreach($left as $row)
			 {
			 	if(empty($row['row']))
				  echo '<li> <a href="/admin/arclist/arclist_add/'.$row['addtable'].'/'.$row['id'].'" class="nav-top-item no-submenu" id="k_'.$row['id'].'" >'.$row['name'].'</a></li>';
			 	else
			 	{
			 		echo '<li> <a href="/admin/arclist/arclist_add/'.$row['addtable'].'/'.$row['id'].'" class="nav-top-item " id="k_'.$row['id'].'" >'.$row['name'].'</a><ul>';
			 		foreach ($row['row'] as $value)
			 			echo '<li> <a href="/admin/arclist/arclist_add/'.$row['addtable'].'/'.$value['id'].'"  id="k_'.$value['id'].'" >'.$value['name'].'</a>';
			 		echo '</ul></li>';
			 	}
			 }
		 }
		 ?>
		 <li> <a href="<?php echo base_url('search/search_admin');?>" class="nav-top-item no-submenu" id="k_500" >搜索内容</a></li>
		 <?php 
		 if($this->session->userdata("userrole") >1)
		 	echo ' <li> <a href="/admin/index" class="nav-top-item no-submenu" >返回系统页</a></li>';
		 ?>
      </ul>
      <!-- End #main-nav -->
    </div>