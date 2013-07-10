// JavaScript Document

$(function () {
	var showimg = $('#showimg');
	var files = $(".files");
	$("#fileupload").wrap("<form id='myupload' action='/admin/upload/imgup' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function(){
		$("#myupload").ajaxSubmit({
			dataType:  'json',
			success: function(data) {
				if(!data.error)
				{
					files.html("<a id='delimg' href='"+data.url+"'>删除</a>");
					showimg.html("<img src='"+data.url+"' height=110 />");
					$("#impress").val(data.url);
					$("#delimg").click(function(){
						var pic=$(this).attr("href");
						$.post("/admin/upload/delimg",{imagename:pic},function(data){
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

$(document).ready(function()
{
	getinfo(1);
	$("#editall").click(function(){
		var data="";
		var type=$("#dropdown").val();
		if(type=="delete")
		{
			$("#check:checked").each(function(){
				data+=$(this).val()+",";
			});
			if(data=="" || data==null)
				alert("未选中任何品牌");
			else
				$.post("/admin/delete/delpinpai",{data:data},function(data){
					if(data)
						alert("删除成功");
					else
						alert("删除失败！");
				});
		}
		getinfo(1);
		return false;
	});
	
	$("#form1").submit(function()
	{
		var name=$("#form1 #name").val();
		var impress=$("#form1 #impress").val();
		var short=$("#form1 #short").val();
		$.post("/admin/addinfo/pinpai",{name:name,impress:impress,short:short},function(data){
			if(data)
				alert("添加成功");
		});
		return false;
	});
	
});

function getinfo(page)
{
	if(page==""||page==null)
		page=1;
	$.post("/admin/index/ajaxpinpai",{page:page},function(data){
		var row=eval(data.row);
		var page=eval(data.page);
		var str="<a href='"+data.first+"' id='pageid'>首页</a><a href='"+data.uppage+"' id='pageid'>上一页</a>";
		for(i=0;i<page.length;i++)
			str+="<a href='"+page[i]+"' id='pageid'>"+page[i]+"</a>";
		str+="<a href='"+data.downpage+"' id='pageid'>下一页</a><a href='"+data.last+"' id='pageid'>末页</a>";
		$(".pagination").html(str);
		str="";
		for(i=0;i<row.length;i++)
			str+="<tr><td><input type='checkbox' value='"+row[i].id+"' id='check'/></td><td>"+row[i].name+"</td><td>"+row[i].impress+"</td><td>"+row[i].short+"</td><td><a href='#' id='edit'><img src='/images/admin/icons/pencil.png' alt='Edit' /></a> <a href='#' title='Delete' id='del'><img src='/images/admin/icons/cross.png' alt='Delete' /></a></td></tr>";
		$("#rowinfo").html(str);
		nextfun();
	},"json");
}

function nextfun()
{
	$("[id=edit]").click(function(){
		var data=$(this).parent().prev("td");
		var id=data.parent().find("#check").val();
		var value=data.text();
		var tg=document.getElementById("editnow");
		if(tg!=null)
			return;
		var string="<input type='text' value='"+value+"' style='width:40px;' id='editnow'/>";
		data.html(string);
		$("[id=editnow]").focus();
		$("[id=editnow]").blur(function(){
			var editn=$(this).val();
			if(editn!=value)
			{
				$.post("/admin/change/pinpai",{id:id,short:editn},function(date){
					if(date)
						data.html(editn);
					else
						data.html(value);
				});
			}else
				data.html(value);
		});
	});

		$("[id=pageid]").click(function(){
			var page=$(this).attr("href");
			getinfo(page);
			return false;
		});
		
		$("[id=del]").click(function(){
			data=$(this).parent().parent("tr").find("#check").val();
			$.post("/admin/delete/delpinpai",{data:data},function(data){
				if(data)
					alert("删除成功");
				else
					alert("删除失败！");
			})
			getinfo(1);
		})
}