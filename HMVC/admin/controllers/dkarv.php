<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dkarv extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 获取内容列表
	 */
	public function ajaxlist()
	{
		$page=$this->input->post("page",TRUE);
		$list=$this->input->post("list",TRUE);
		$addtable=$this->input->post("addtable",TRUE);
		if(empty($page))
			$page="1";
		$this->load->model("dkarv_model","dkarv");
		$this->load->model("page_model","page");
		$sql=array(
				"select"=>trim($this->dkarv->showcolumns($addtable).",arctype.name typename,addtable"),
				"order_by"=>"id",
				"join"=>array(
						"join1"=>array("arctype","typeid=arctype.id"),
				),
				"get"=>$addtable,
		);
		if(!empty($list))
			$sql['where']=array("typeid"=>$list);
		$result=$this->page->pagestart($sql,$page,20);
		echo json_encode($result);
	}
	
	/**
	 * 编辑
	 */
	public function edit()
	{
		$_POST['typeid']=$this->input->post("typeid",TRUE);
		if(!is_numeric($_POST['typeid']))
			$this->load->module('public/public_made/message',array('提交内容有误错误！'));
		$this->load->module('public/public_made/power',array(2,$_POST['typeid'],"wpower"));
		$this->load->model("dkarv_model","dkarv");
		$this->_arc_valcheck();
		$this->dkarv->edit_dkarv($_POST);
		$this->load->module('public/public_made/message',array('内容修改成功！'));
	}
	
	/**
	 * 添加
	 */
	public function add_dkarv()
	{
		$_POST['typeid']=$this->input->post("typeid",TRUE);
		if(!is_numeric($_POST['typeid']))
			$this->load->module('public/public_made/message',array('提交内容有误错误！'));
		$this->load->module('public/public_made/power',array(4,$_POST['typeid'],"wpower"));
		$this->load->model("dkarv_model","dkarv");
		$this->_arc_valcheck();
		$this->dkarv->add_dkarv($_POST);
		$this->load->module('public/public_made/message',array('内容添加成功！'));
	}
	
	/**
	 * 删除
	 * @param unknown $addtable
	 * @param unknown $id
	 */
	public function arcdel()
	{
		$this->load->database();
		$data=$this->input->post("data",TRUE);
		$addtable=$this->input->post("addtable",TRUE);
		$data=trim($data,",");
		$data=explode(",", $data);
		if(empty($data) || empty($addtable))
		{
			echo false;
			return ;
		}
		foreach ($data as $row)
		{
			$k=$this->db->where("id",$row)->get($addtable)->row_array();
			if(!$this->load->module('public/public_made/bopower',array(1,$k['typeid'],"wpower"),TRUE))
			{
				echo false;
				return ;
			}
			$this->db->delete($addtable,array("id"=>$row));
		}
		echo true;
	}
	

	/**
	 * 内容检查
	 */
	private function _arc_valcheck()
	{
		$this->input->post(NULL,TRUE);
		$valcheck=$this->input->post("valcheck",TRUE);
		unset($_POST['valcheck']);
		if(!empty($valcheck))
		{
			$valcheck=trim($valcheck,",");
			$valcheck=explode(",",$valcheck);
			foreach ($valcheck as $row)
			{
				$row=explode(":",$row);
				$this->form_validation->set_rules($row[1], $row[0], $row[2]);
			}
		}
		else
			return $_POST;
		if ($this->form_validation->run() == FALSE)
		{
			$data=$this->load->module('formck/formck_made/getviewinfo',array($_POST['addtable'], $_POST['typeid']),TRUE);
			$data['error']="1";
			$this->load->view($data['view'],$data);
		}
		else
		{
			$info=$this->load->module('formck/formck_made/getaddrow',array($_POST['addtable']),TRUE);
			if(!empty($info))
			{
				foreach ($info as $row)
				{
					$str="";
					if($row['type']=='checkbox' && !empty($_POST[$row['tablename']]))
					{
						foreach($_POST[$row['tablename']] as $r)
							$str.=$r.",";
						$_POST[$row['tablename']]=$str;
					}
					unset($str,$r);
				}
			}
			unset($info,$row);
			return $_POST;
		}
	}
	
	/**
	 * 文章内容编辑
	 * @param unknown $addtable
	 * @param unknown $id
	 */
	public function edit_show($addtable,$id)
	{
		$data=$this->load->module('formck/formck_made/getviewinfo',array($addtable),TRUE);
		$this->load->database();
		$data['arcrow']=$this->db->where("id",$id)->get($addtable)->row_array();
		$this->load->module('public/public_made/power',array(2,$data['arcrow']['typeid'],"wpower"));
		$data['id']=$id;
		$this->load->view($data['view']."edit",$data);
	}
}