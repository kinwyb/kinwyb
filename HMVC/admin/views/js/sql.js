// JavaScript Document
$(document).ready(function(){
	$("[id=pst]").click(function(){
			var $type=$(this).attr("rel");
			var $sd=$(this).attr("href");
			str='<div id="J_overlay"><iframe></iframe></div><div id="J_dialogBox" style="position:fixed; left:45%; top:50%; z-index:10000; color:red"><img src="/images/loading.gif" />&nbsp;耗时较久，请耐心等待</div>';
			$("body").append(str);
			$.post('/admin/sql/optimize',{type:$type,sd:$sd},function(data){
					if(!data)
					{
						$("#error").html("操作失败！");
						$("#error").parent("div").css("display","block");
						$("#J_overlay").remove();
						$("#J_dialogBox").remove();
					}else
					{
						$("#true").html(data);
						$("#true").parent("div").css("display","block");
						$("#J_overlay").remove();
						$("#J_dialogBox").remove();
					}
			});
			return false;
	});
	$("#hy").click(function(){
		$(".content-box").css("display","block");
		$("#tab2").css("display","none");
		$("#tab1").css("display","block");
		return false;
	});
	
	$("#sq").click(function(){
		$(".content-box").css("display","block");
		$("#tab1").css("display","none");
		$("#tab2").css("display","block");
		
		$(".button").click(function(){
			var i=$("#textarea").val();
			$.post('/admin/sql/sql_run',{sql:i},function(data){
				sql=data.sql;
				str="";
				for(i=0;i<sql.length;i++)
				{
					str+="<br />---第 "+(i+1)+" 条记录---<br />";
					for(var k in sql[i])
						str+=k+' ： '+sql[i][k]+'<br />';
				}
				$("#true").html(str);
				$("#true").parent("div").css("display","block");
			},"json");
		});
		return false;
	});
	
	$("[id=scsj]").click(function(){
		var filename=$(this).attr("href");
		var t=$(this);
		if(confirm("确实要删除此备份?"))
		{
			$.get('/admin/sql/del?file='+filename,function(data){
				if(data)
				{
					alert('备份文件'+filename+'删除成功！');
					t.parent("td").parent("tr").remove();
				}else
					alert('备份文件'+filename+'删除失败！');
						
			});
		}
		return false;
	});
	
});