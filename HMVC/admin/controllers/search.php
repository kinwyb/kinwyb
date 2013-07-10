<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Search extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 首页
	 */
	public function index()
	{
		$this->load->model("search_model","search");
		$data['model']=$this->search->model();
		$this->load->view("search",$data);
	}
	
	/**
	 * ajax获取列表
	 */
	public function ajaxlist()
	{
		$this->load->model('page_model','page');
		$page=$this->input->post("page",TRUE);
		if(empty($page))
			$page="1";
		$sql=array(
				"select"=>"distinct(addtable),id",
				"order_by"=>"id",
				"get"=>"search",
		);
		$result=$this->page->pagestart($sql,$page,20);
		echo json_encode($result);
	}

	/**
	 * ajax获取附加表内容
	 */
	public function ajaxmodel()
	{
		$id=$this->input->post("id",TRUE);
		if(!is_numeric($id))
			return FALSE;
		$this->load->model("search_model","search");
		$result=$this->search->modeltype($id);
		$i=0;
		$row=array();
		foreach ($result as $value)
		if($value['type'] != 'text' AND $i<=6)
		{
			$row[]=$value;
			$i++;
		}
		elseif($i>=6)
			break;
		echo json_encode($row);
	}

	/**
	 * 添加内容
	 */
	public function add_post()
	{
		$this->load->module('public/public_made/power',array(4,7,"power"));
		$this->load->model("search_model","search");
		$this->input->post(NULL,TRUE);
		$row=$this->search->model_id($_POST['chage']);
		unset($_POST['chage']);
		$string="";
		foreach($_POST as $key => $value)
		if(!empty($value))
			$string.="$key||$value::";
		$string=trim($string,"::");
		if($this->search->add_post($row['addtable'],$string,$_POST['name']))
			$this->load->module('public/public_made/message',array('添加成功！'));
		else
			$this->load->module('public/public_made/message',array('添加失败！'));
	}
	
	/**
	 * 删除
	 */
	public function del()
	{
		$this->load->module('public/public_made/power',array(1,7,"power"));
		$this->load->model("search_model","search");
		$data=$this->input->post("data",TRUE);
		if($this->search->del($data))
			echo TRUE;
		else
			echo FALSE;
	}
	
	/**
	 * 后台搜索
	 */
	public function search_admin($result="")
	{
		if(empty($result))
			$this->load->view("adminsearch");
		else
		{
			$this->load->database();
			$this->input->post(NULL,TRUE);
			empty($_POST['kwd']) AND $this->load->module('public/public_made/message',array('搜索内容不能为空！'));
			switch ($_POST['type'])
			{
				case 'allcp':$data['addtable']='chepin';
				$data['turl']='/archives/allcp';
				break;
				case 'allshop':$data['addtable']='shoparc';
				$data['turl']='product';
				break;
				case 'allimg':$data['addtable']='imgsarv';
				$data['turl']='/image/image_show';
				break;
				default:$data['addtable']='arclist';
				$data['turl']='/archives/allarc';
			}
			if(is_numeric($_POST['kwd']))
				$data['result']=$this->db->select($_POST['type'].'.id,title,update,writer,arctype.name arctypename,addtable')->where($_POST['type'].".id",$_POST['kwd'])->join("arctype",$_POST['type'].".typeid=arctype.id")->get($_POST['type'])->result_array();
			else
				$data['result']=$this->db->select($_POST['type'].'.id,title,update,writer,arctype.name arctypename,addtable')->like($_POST['type'].".title",$_POST['kwd'])->join("arctype",$_POST['type'].".typeid=arctype.id")->get($_POST['type'])->result_array();
			$this->load->view("adminsearch",$data);
		}
	}
	
}