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
      <h1 id="sidebar-title"><a href="#">Simpla Admin</a></h1>
      <a href="#"><img id="logo" src="<?php echo base_url('views/images/logo.png');?>" alt="Simpla Admin logo" /></a>
      <div id="profile-links"> 你好,<?php echo $this->session->userdata('username');?><br />
        <br />
        <a href="/" target="_blank"  title="View the Site" >网站首页</a> | <a href="<?php echo site_url('login/login_out')?>" title="Sign Out">退出</a> </div>
      <ul id="main-nav">
         <?php 
		 if(!empty($left) && is_array($left))
		 {
			 foreach($left as $row)
			 {
				 echo '<li> <a href="'.site_url($row['class_name'].'/'.$row['model_name']).'" class="nav-top-item no-submenu" id="k_'.$row['menu_id'].'" >'.$row['menu_name'].'</a></li>';
			 }
		 }
		 if($this->session->userdata("userrole") == 15)
		 	echo '<li> <a href="http://www.mp189.com/uc_server" class="nav-top-item no-submenu" target="_blank" >会员管理</a></li>';
		 ?>
		 <li> <a href="<?php echo site_url('index/feedback');?>" class="nav-top-item no-submenu" id="k_13" >内容列表页</a></li>
      </ul>
    </div>