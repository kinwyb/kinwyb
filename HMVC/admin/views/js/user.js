$(document).ready(function(){
	$("#rowinfo a").click(function(){
		var id=$(this).attr("href");
		var type=$(this).attr("rel");
		if(type!='ed')
		{
			$.post('/admin/sql/user_post',{id:id,type:type},function(){
				location.reload();
			});
			return false;
		}
	});
})