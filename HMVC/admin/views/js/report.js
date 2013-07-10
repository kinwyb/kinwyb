// JavaScript Document
$(document).ready(function()
{
	$("#showd").hide();
	getinfo(1);
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
					$.post("/admin/delete/arctype",{data:data},function(data){
						if(data)
							alert("删除成功");
						else
							alert("删除失败！");
					});
			}
			getinfo(1);
		}
		return false;
	});
});

function getinfo(page)
{
	if(page==""||page==null)
		page=1;
	$.get("/admin/myad/ajax_report/"+page,function(data){
		var row=eval(data.row);
		var page=eval(data.page);
		var arctype=eval(data.arctype);
		var str="<a href='"+data.first+"' id='pageid'>首页</a><a href='"+data.uppage+"' id='pageid'>上一页</a>";
		for(i=0;i<page.length;i++)
			str+="<a href='"+page[i]+"' id='pageid'>"+page[i]+"</a>";
		str+="<a href='"+data.downpage+"' id='pageid'>下一页</a><a href='"+data.last+"' id='pageid'>末页</a>";
		$(".pagination").html(str);
		str="";
		for(i=0;i<row.length;i++)
			str+="<tr><td><input type='checkbox' value='"+row[i].id+"' id='check'/></td><td><a href='"+row[i].adtb+"_"+row[i].aid+"_"+row[i].id+"' id='dshow'>"+row[i].value+"</a></td><td><a href='"+row[i].adtb+"_"+row[i].aid+"' title='Delete' id='del'><img src='/admin/views/images/icons/cross.png' alt='Delete' /></a> <a href='"+row[i].id+"' id='good' ><img src='/admin/views/images/icons/tick_circle.png' title='通过' ></a></td></tr>";
		$("#rowinfo").html(str);
		$("[id=dshow]").click(function(){
			var id=$(this).attr("href");
			$.get("/admin/myad/showreport/"+id,function(data){
				$("[id=showd]").show();
				$("[id=infod]").html(data);
			});
			return false;
		});
		nextfun();
	},"json");
}

function nextfun()
{
		$("[id=pageid]").click(function(){
			var page=$(this).attr("href");
			getinfo(page);
			return false;
		});
		
		$("[id=del]").click(function(){
			if(confirm("确实要删除吗?"))
			{
				var id=$(this).attr("href");
				$.post("/admin/myad/postreport/"+id,function(data){
					alert(data);
				})
				$(this).parents("tr").remove();
				return false;
			}
			return false;
		});
		
		$("[id=good]").click(function(){
			if(confirm("确实要忽略吗?"))
			{
				var id=$(this).attr("href");
				$.get("/admin/myad/postreport/"+id+"/good",function(data){
					alert(data);
				})
				$(this).parents("tr").remove();
				return false;
			}
			return false;
		});
		
}