<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Snoopy extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 首页显示
	 */
	function snoopy_view()
	{
		$this->load->model('index_model','index');
		$data['val']=$this->index->arctype();
		$id=array();
		foreach($data['val'] as $v)
			$id[]=$v['topid'];
		foreach($data['val'] as $key =>$v)
		if(in_array($v['id'],$id))
			unset($data['val'][$key]);
		$this->load->view('snoopy',$data);
	}
	
	/**
	 * 添加采集到数据库
	 */
	function snoopy_add()
	{
		$this->load->module('public/public_made/power',array(4,9,"power"));
		$data=$this->input->post('data',TRUE);
		$title=$this->input->post('title',TRUE);
		$writer=$this->input->post('writer',TRUE);
		$agent=$this->input->post('writer',TRUE);
		$cookies=$this->input->post("cookies");
		$type=$this->input->post("type",TRUE);
		(empty($data) OR empty($title) OR empty($writer) ) AND exit();
		$data=array_filter(explode(",",$data));
		$title=array_filter(explode("::",$title));
		$writer=array_filter(explode("::",$writer));
		(count($data) != count($title)) AND exit();
		(count($data) != count($writer)) AND exit();
		$this->load->library("snoopy_lib");
		foreach($title as $key => $value)
		{
			$date['time'][]=$data[$key];
			preg_match('/<a.* href=\"(.*)?\" .*>(.*)<\/a>/',$value,$m);
			$date['url'][]=$m[1];
			$date['title'][]=$m[2];
			preg_match('/<a.* href=\".*home.php\?mod=space&uid=([0-9]*)\".*>(.*)<\/a>/',$writer[$key],$m);
			$date['wurl'][]=$m[1];
			$date['wname'][]=$m[2];
		}
		foreach($date['url'] as $key => $value)
			$date['val'][]=$this->get_info($value,$agent,$cookies);
		$this->load->model("index_model","index");
		$this->index->snoopy_add($date,$type);
		echo true;
	}
	
	/**
	 * 栏目列表  date("Y/m/d",time()-24*60*60)
	 */
	public function get_list()
	{
		!empty($_POST) AND $this->input->post(NULL,TRUE);
		empty($_POST['url']) AND $_POST['url']='http://www.mp189.com';
		empty($_POST['time']) AND $_POST['time']="2013-5-5";
		empty($_POST['forum']) AND $_POST['forum']="/^http:\/\/www.mp189.com\/forum-[0-9]*-1.html/i";
		$this->load->library("snoopy_lib");
		empty($_POST['agent']) AND $this->snoopy_lib->agent=$_POST['agent'];
		empty($_POST['cookies']) AND $this->snoopy_lib->rawheaders["COOKIE"]=$_POST['cookies'];
		empty($_POST['jh']) AND $_POST['jh']=0;
		$this->snoopy->expandlinks = true;
		$this->snoopy_lib->fetchlinks($_POST['url']); //获取所有内容
		$url=$this->snoopy_lib->results;
		$info=array();
		$return=array();
		foreach($url as $key => $value)
		if(!preg_match($_POST['forum'],$value))
			unset($url[$key]);
		$url=array_unique($url);
		foreach($url as $value)
			$info[]=$this->get_jinh($value,$_POST['jh']);
		foreach(array_filter($info) as $value)
		foreach($value as $v)
		if(!empty($v['title']) AND count($v['writer'])>1)
			$return[]=array(
					"writerurl"=>$_POST['url'].'/'.$v['writer'][1],
					"writer"=>$v['writer'][2],
					"url"=>$_POST['url'].'/'.$v['url'],
					"title"=>$v['title'],
			);
		unset($info);
		if(empty($return))
			echo 0;
		else
			echo json_encode($return);
	}
	
	/**
	 * 获取列表页
	 * @param unknown $ur
	 * @return Ambigous <multitype:, unknown>
	 */
	private function get_jinh($ur,$jh)
	{
		$this->snoopy_lib->results="";
		$this->snoopy_lib->fetch($ur); //获取所有内容
		$ur=$this->snoopy_lib->results;
		preg_match_all('/<tbody id=\"normalthread_[0-9]{6}\">.*?<\/tbody>/is',$ur,$ur);
		$i=0;
		$return=array();
		foreach($ur[0] as $k => $v)
		{
			$v=iconv("gbk", "utf-8", $v);
			if(!preg_match('/<img src=\"static\/image\/common\/digest_[1-3]{1}.gif\" .* \/>/', $v) AND $jh)
			{
				unset($ur[0][$k]);
				continue;
			}
			preg_match('/<td class=\"by\">.*?<\/td>/is',$v,$time);
			preg_match('/<span.*?>(.*)<\/span>/is',$time[0],$time);
			if(strtotime($time[1])<strtotime($_POST['time']))
				unset($ur[0][$k]);
			else
			{
				preg_match('/<td class=\"by\">.*?<\/td>/is',$v,$time);
				preg_match('/<a .*href=\"(.*)\" .*>(.*)<\/a>/',$time[0],$return[$i]['writer']);
				preg_match('/<th class=\"new\">.*<\/th>/is', $v,$time);
				preg_match('/ <a href=\"(.*\.html)?\" .*>(.*)?<\/a>/i',$time[0],$time);
				if(!empty($time))
				{
					$return[$i]['url']=$time[1];
					$return[$i]['title']=$time[2];
					$i++;
				}
			}
		}
		return $return;
	}
	
	/**
	 * 获取帖子内容
	 */
	private function get_info($v,$agent,$cookies)
	{
		$s=new Snoopy_lib();
		!empty($agent) AND $s->agent=$agent;
		!empty($cookies) AND $s->rawheaders["COOKIE"]=$cookies;
		$localhost='/index/sll';
		$s->fetch($v);
		$url=$s->results;
		$url=preg_replace('/<img .* file=\"forum.php\?mod=attachment&aid=(.*)&noupdate=yes\" .*>/i','<img src="'.$localhost.'/${1}" />' ,$url);
		$url=preg_replace('/<img .* src=\"forum.php\?mod=attachment&aid=(.*)&noupdate=yes\" .*>/i','<img src="'.$localhost.'/${1}" />' ,$url);
		//preg_match('/<td class=\"t_f\" id=\"postmessage_[0-9]*\">.*?<\/td>/is',$url,$url);
		preg_match('/<div class=\"t_fsz\">.*?<div id=\"comment/is',$url,$url);
		$url=preg_replace('/<div class=\"tip tip_4 aimg_tip\" id=\"aimg_[0-9]*_menu\" style=\"position: absolute; display: none\">.*?<\/ignore_js_op>/s',"</ignore_js_op>", $url);
		$url=preg_replace('/(<i class=\"pstatus\">.*<\/i><br \/>|<td class=\"t_f\" id=\"postmessage_[0-9]*\">|<\td>)/', "", $url);
		$url=preg_replace('/(^<div class=\"t_fsz\">|<\/div>$)/',"", $url);
		$url=str_replace("div", "span", $url);
		return iconv("gbk","utf-8",$url[0]);
	}
	
	/**
	 * 检测是否采集到图片
	 */
	public function code_check()
	{
		$val=$this->input->post("val",TRUE);
		$cookies=$_POST['cookies'];
		$agent=$this->input->post("agent",TRUE);
		if(empty($val))
			echo false;
		else
		{
			$this->load->library("snoopy_lib");
			!empty($agent) AND $this->snoopy_lib->agent=$agent;
			!empty($cookies) AND $this->snoopy_lib->rawheaders["COOKIE"]=$cookies;
			$this->snoopy_lib->fetch($val);
			$url=$this->snoopy_lib->results;
			preg_match('/<div class=\"t_fsz\">.*?<div id=\"comment/is',$url,$url);
			if(preg_match('/<img .* file=\"forum.php\?mod=attachment&aid=(.*)&noupdate=yes\" .*>/i',$url[0]) || preg_match('/<img .* src=\"forum.php\?mod=attachment&aid=(.*)&noupdate=yes\" .*>/i',$url[0]))
				echo true;
			else
				echo false;
		}
	}
	
	/**
	 * 指定URL采集
	 * @return boolean
	 */
	public function caijid()
	{
		$this->load->database();
		$this->load->library("snoopy_lib");
		if(empty($_POST['url']) OR empty($_POST['type']) OR !is_numeric($_POST['type']))
		{
			echo false;
			exit();
		}
		$_POST['type']=$this->input->post('type',NULL);
		$this->snoopy_lib->agent='Mozilla/5.0 (Windows NT 6.1; rv:20.0) Gecko/20100101 Firefox/20.0';
		!empty($_POST['cookies']) AND $this->snoopy_lib->rawheaders["COOKIE"]=$_POST['cookies'];
		$localhost='/index/sll';
		$this->snoopy_lib->fetch($_POST['url']);
		$url=$this->snoopy_lib->results;
		$url=iconv('gbk', 'utf-8', $url);
		$url=preg_replace('/<img .* file=\"forum.php\?mod=attachment&aid=(.*)&noupdate=yes\" .*>/i','<img src="'.$localhost.'/${1}" />' ,$url);
		$url=preg_replace('/<img .* src=\"forum.php\?mod=attachment&aid=(.*)&noupdate=yes\" .*>/i','<img src="'.$localhost.'/${1}" />' ,$url);
		preg_match('/<a href=\".*\" id=\"thread_subject\">(.*)<\/a>/',$url,$title);
		$data['title']=$title[1];
		preg_match('/<em id=\".*\">.*? (.*)<\/em>/',$url,$title);
		$data['time']=strtotime(trim($title[1]));
		preg_match('/<a href=\"home\.php\?mod=space&amp;uid=([0-9]*)\" target=\"_blank\" class=\"xw1\">(.*)<\/a>/',$url,$title);
		$data['wname']=$title[2];
		$data['wurl']=$title[1];
		preg_match('/<div class=\"t_fsz\">.*?<div id=\"comment/is',$url,$url);
		$url=preg_replace('/<div class=\"tip tip_4 aimg_tip\" id=\"aimg_[0-9]*_menu\" style=\"position: absolute; display: none\">.*?<\/ignore_js_op>/s',"</ignore_js_op>", $url);
		$url=preg_replace('/(<i class=\"pstatus\">.*<\/i><br \/>|<td class=\"t_f\" id=\"postmessage_[0-9]*\">|<\td>)/', "", $url);
		$url=preg_replace('/(^<div class=\"t_fsz\">|<\/div>$)/',"", $url);
		$url=str_replace("div", "span", $url);
		$r=$this->db->select("addtable")->where("id",$_POST['type'])->get("arctype")->row_array();
		$r['addtable']=='archives' AND $addtable='allarc';
		$r['addtable']=='chepin' AND $addtable='allcp';
		$this->db->insert($addtable,array("title"=>$data['title'],"typeid"=>$_POST['type'],"writer"=>$data['wname'],"update"=>$data['time'],"writer_uid"=>$data['wurl']));
		$id=$this->db->insert_id();
		$this->db->insert($r['addtable'],array("aid"=>$id,"body"=>$url[0]));
		echo true;
	}
}