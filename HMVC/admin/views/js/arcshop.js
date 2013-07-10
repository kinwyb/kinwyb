// JavaScript Document

$(document).ready(function(){
	//响应文件添加成功事件
	$("#inputfile").change(function(){
		//创建FormData对象
		var data = new FormData();
		//为FormData对象添加数据
		$.each($('#inputfile')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});
		$(".loading").show();	//显示加载图片
		//发送数据
		$.ajax({
			url:'http://gz.localhost/admin/upload/imgsup',
			type:'POST',
			data:data,
			cache: false,
			contentType: false,		//不可缺参数
			processData: false,		//不可缺参数
			datatype:"json",
			success:function(data){
				//alert(data.instid);
				row=eval('(' +data+ ')');
				for(i=0;i<row.length;i++)
				{
					if(row[i]['error']==0)
					{
						$("#feedback").append("<div class='showimg' id='1'><div><img src='"+row[i]['url']+"' height='100' /></div><div style='width:240px;' id='2'>描述<input type='text' value='' name='imgkwd[]'/><input type='hidden' value='"+row[i]['url']+"' name='imgurl[]' class=''/><br /><a href='"+row[i]['url']+"' id='delimg'>[删除]</a></div></div>")
					}else
					{
						$("#feedback").append("<div class='error'>上传错误"+row[i]['msg']+"</div>");
					}
				}
				$("[id=delimg]").click(function(){
					var pic=$(this).attr("href");
					var div=$(this).parent().parent();
						$.post("/admin/upload/delimgs",{imagename:pic},function(data){
							if(data){
								div.remove();
							}else{
								alert("删除失败");
							}
						});
					return false;
				});
				$(".loading").hide();
			},
			error:function(){
				alert('上传出错');
				$(".loading").hide();	//加载失败移除加载图片
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
				alert("未选中任何商品");
			else
				$.post("/admin/delete/arcshop",{data:data},function(data){
					if(data)
						alert("删除成功");
					else
						alert("删除失败！");
				});
		}
		getinfo(1);
		return false;
	});

});


$(document).ready(function(){
	$("#arcselect").change(function(){
		selectdiv($(this).val());
	});
});
function getinfo(page)
{
	var list=$("#arclist").val();
	if(page==""||page==null)
		page=1;
	$.post("/admin/arcshop/ajaxarcshop",{page:page,list:list},function(data){
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
			str+="<tr><td><input type='checkbox' value='"+row[i].id+"' id='check'/></td><td>"+row[i].title+"</td><td>"+row[i].pinpai+"</td><td>"+row[i].name+"</td><td>"+row[i].update+"</td><td><a href='/admin/arcshop/change/"+row[i].id+"' id='edit'><img src='/images/admin/icons/pencil.png' alt='Edit' /></a> <a href='#' title='Delete' id='del'><img src='/images/admin/icons/cross.png' alt='Delete' /></a></td></tr>";
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
			data=$(this).parent().parent("tr").find("#check").val();
			$.post("/admin/delete/arcshop",{data:data},function(data){
				if(data)
					alert("删除成功");
				else
					alert("删除失败！");
			})
			getinfo(1);
		})
}