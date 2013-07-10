// JavaScript Document
$(document).ready(function()
{
	$("[id=del]").click(function(){
			if(confirm("确实要删除吗?"))
			{
				data=$(this).parent().parent("tr").find("#check").val();
				$.post("/admin/myad/delete",{data:data},function(data){
					if(data)
					{
							alert("删除成功");
							location.reload();
					}
					else
						alert("删除失败！");
				})
			}
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
					alert("未选中任何广告");
				else
					$.post("/admin/myad/delete",{data:data},function(data){
						if(data)
						{
							alert("删除成功");
							location.reload();
						}
						else
							alert("删除失败！");
					});
			}
		}
		return false;
	});

});