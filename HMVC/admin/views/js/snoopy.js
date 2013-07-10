// JavaScript Document
$(document).ready(function()
{
	$("#caiji").click(function(){
		str='<div id="J_overlay"><iframe></iframe></div><div id="J_dialogBox" style="position:fixed; left:45%; top:50%; z-index:10000; color:red"><img src="/images/loading.gif" />&nbsp;耗时较久，请耐心等待</div>';
			$("body").append(str);
		url=$("#url").val();
		time=$("#time").val();
		forum=$("#forum").val();
		cookies=$("#cookies").val();
		agent=$("#agent").val();
		jh=$("#jh:checked").val();
		$.post("/admin/snoopy/get_list",{url:url,time:time,forum:forum,cookies:cookies,agent:agent,jh:jh },function(data){
				$("#J_overlay").remove();
				$("#J_dialogBox").remove();
				$("#peiz").addClass("closed-box");
				$("#view").show();
				if(data)
				{
					for(i=0;i<data.length;i++)
						$("#rowinfo").append('<tr><td><input id="check" type="checkbox" /></td><td id="tit"><a href="'+data[i].url+'" target="_blank">'+data[i].title+'</a></td><td id="zz"><a href="'+data[i].writerurl+'" target="_blank">'+data[i].writer+'</a></td><td id="tt">'+time+'</td><td><a href="'+data[i].url+'" id="look" >检测</a></td><tr>');
					$("[id=look]").click(function(){
						val=$(this).attr("href");
						$.post("/admin/snoopy/code_check",{val:val,cookies:cookies,agent:agent},function(data){
								if(data)
									alert("采集完整！");
								else
									alert("采集不完整！修改cookies重试");
						});
						return false;
					});
				}
				else
					$("#tab1").html("没有采集到任何内容");
		},"json");
	});
	
	$("#caijid").click(function(){
		if(confirm("确实要采集吗?"))
		{
			str='<div id="J_overlay"><iframe></iframe></div><div id="J_dialogBox" style="position:fixed; left:45%; top:50%; z-index:10000; color:red"><img src="/images/loading.gif" />&nbsp;采集中...</div>';
			$("body").append(str);
			cookies=$("#cookiesd").val();
			url=$("#urld").val();
			type=$("#typed").val();
			$.post("/admin/snoopy/caijid",{cookies:cookies,url:url,type:type},function(data){
				if(data)
					alert("采集成功");
				else
					alert("采集失败");
			});
			$("#J_overlay").remove();
			$("#J_dialogBox").remove();
		}
		return false;
	});
	
	
	$("#editall").click(function(){
		if(confirm("确实要采集吗?"))
		{
			str='<div id="J_overlay"><iframe></iframe></div><div id="J_dialogBox" style="position:fixed; left:45%; top:50%; z-index:10000; color:red"><img src="/images/loading.gif" />&nbsp;采集中...</div>';
			$("body").append(str);
			var data="";
			var title="";
			var writer="";
			var type=$("#dropdown").val();
			$("#check:checked").each(function(){
					data+=$(this).parent().next("[id=tit]").next("[id=zz]").next("[id=tt]").html()+",";
					title+=$(this).parent().next("[id=tit]").html()+"::";
					writer+=$(this).parent().next("[id=tit]").next("[id=zz]").html()+"::";
			});
			if(type=='option1')
				alert("未选中任何文章!");
			else
				$.post("/admin/snoopy/snoopy_add",{data:data,title:title,writer:writer,cookies:cookies,agent:agent,type:type},function(data){
					if(data)
						alert("采集成功");
					else
						alert("采集失败");
				});
			$("#J_overlay").remove();
			$("#J_dialogBox").remove();
		}
		return false;
	});

});