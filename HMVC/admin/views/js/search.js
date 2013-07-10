// JavaScript Document
$(document).ready(function()
{
	getinfo(1);
	$("#chage").change(function(){
		var id=$(this).val();
		$.post("/admin/search/ajaxmodel",{id:id},function(data){
			if(data)
			{
				data=eval(data);
				str='';
				for(i=0;i<data.length;i++)
					str+='<p><label>'+data[i]['name']+'</label><input class="text-input large-input" type="text" id="large-input" name="'+data[i]['tablename']+'" /></p>';
				$("#tableinfo").html(str);
			}else
				$("#tableinfo").html("");
		});
	});
});

function getinfo(page)
{
	if(page==""||page==null)
		page=1;
	$.post("/admin/search/ajaxlist",{page:page},function(data){
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
			str+="<tr><td><input type='checkbox' value='"+row[i].id+"' id='check'/></td><td>"+row[i].addtable+"</td><td><a href='#' title='Delete' id='del'><img src='/admin/views/images/icons/cross.png' alt='Delete' /></a></td></tr>";
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
				$.post("/admin/search/del",{data:data},function(data){
					if(data)
						alert("删除成功");
					else
						alert("删除失败！");
				})
				getinfo(1);
			}
		})
}