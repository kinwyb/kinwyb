// JavaScript Document
$(document).ready(function()
{
	$("#typeselect").css("display","none");
	$("#typesel").change(function(){
		var type=$(this).val();
		switch(type)
		{
			case 'select':$("#typeselect").css("display","block");
				break;
			case 'radio':$("#typeselect").css("display","block");
				break;
			case 'checkbox':$("#typeselect").css("display","block");
				break;
		}
	});
	
	$("[id=del]").click(function(){
			if(confirm("确实要删除吗?"))
			{
				model=$("#model_id").val();
				data1=$(this).parent().parent("tr");
				data=data1.find("#check").val();
				$.post("/admin/modelview/table_del",{data:data,model:model},function(data){
					if(data)
					{
						data1.remove();
						alert("删除成功");
					}
					else
						alert("删除失败！");
				})
			}
	});
});