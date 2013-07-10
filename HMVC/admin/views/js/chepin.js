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
					$("#litpic").val(data.url);
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
		var addtable=$("#thistable").val();
		if(type=="delete")
		{
			if(confirm("确实要删除吗?"))
			{
				$("#check:checked").each(function(){
					data+=$(this).val()+",";
				});
				if(data=="" || data==null)
					alert("未选中任何文章");
				else
					$.post("/admin/arclist/arcdel",{data:data,addtable:addtable},function(data){
						if(data)
							alert("删除成功");
						else
							alert("删除失败！");
					});
			}
		}
		if(type=='chged')
		{
			id=$("#chged").val();
			if(confirm("确实要转移么吗?"))
			{
				$("#check:checked").each(function(){
					data+=$(this).val()+",";
				});
				if(data=="" || data==null)
					alert("未选中任何文章");
				else
					$.post("/admin/arclist/chged",{id:id,data:data,addtable:addtable},function(data){
						if(data)
							alert("移动成功");
						else
							alert("移动失败！");
					});
			}
		}
		getinfo(1);
		return false;
	});
	
	$("#dropdown").change(function(){
		var type=$("#dropdown").val();
		var addtable=$("#thistable").val();
		if(type=='chged')
		{
			$.get("/admin/index/chged/"+addtable,function(data){
				str='<select id="chged">';
				for(i=0;i<data.length;i++)
					str+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
				str+='</select>';
				$("#dropdown").after(str);
			},"json");
		}
		if(type=='delete')
			$("#chged").remove();
	}); 
});

function getinfo(page)
{
	var list=$("#thisid").val();
	if(page==""||page==null)
		page=1;
	$.post("/admin/arclist/ajaxlist",{page:page,list:list,addtable:'allcp'},function(data){
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
			str+="<tr><td><input type='checkbox' value='"+row[i].id+"' id='check'/></td><td><a href='/archives/allcp/"+row[i].id+"' target='_blank'>"+row[i].title+"</a> &nbsp;&nbsp;<img src='/admin/views/images/headtopic_"+row[i].flag+".gif' /></td><td>"+row[i].short+"</td><td>"+row[i].update+"</td><td>"+row[i].writer+"</td><td><a href='/admin/arclist/edit/"+row[i].addtable+"/"+row[i].id+"' id='edit'><img src='/admin/views/images/icons/pencil.png' alt='Edit' /></a> <a href='#' title='Delete' id='del'><img src='/admin/views/images/icons/cross.png' alt='Delete' /></a></td></tr>";
		$("#rowinfo").html(str);
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
				data=$(this).parent().parent("tr").find("#check").val();
				var addtable=$("#thistable").val();
				$.post("/admin/arclist/arcdel",{data:data,addtable:addtable},function(data){
					if(data)
						alert("删除成功");
					else
						alert("删除失败！");
				})
				getinfo(1);
			}
		})
}

$(document).ready(function(){
	$("#type_shop_id").change(function(){
		$("#name_shop_id").show();
		$("#shopid").show();
		val=$("#type_shop_id").val();
		$.post("/admin/arclist/shoplist",{type:val},function(data){
			str="";
			for(i=0;i<data.length;i++)
				str+='<option value='+data[i].id+'>'+data[i].title+'</option>';
		$("#shopid").html(str);
		},"json");
	});
});