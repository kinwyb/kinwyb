<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Arctype extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 栏目列表
	 */
	public function arclist()
	{
		$this->load->model('arctype_model','arctype');
		$data['model']=$this->arctype->model();
		$data['arctype']=$this->arctype->typelist();
		$this->load->view('arctype',$data);
	}
	
	/**
	 * ajax获取栏目列表
	 */
	public function ajaxarclist()
	{
		$this->load->model('page_model','page');
		$page=$this->input->post("page",TRUE);
		if(empty($page) OR !is_numeric($page)) 
			$page="1";
		$sql=array(
				"order_by"=>"id",
				"get"=>"arctype",
		);
		$result=$this->page->pagestart($sql,$page,20);
		echo json_encode($result);
	}
	
	/**
	 * 显示编辑页面
	 * @param int $id
	 */
	public function edit_show($id)
	{
		$this->load->module('public/public_made/power',array(2,6,'power'));
		if(!is_numeric($id))
			$this->load->module('public/public_made/message',array("URL错误！"));
		$this->load->model('arctype_model','arctype');
		$data['info']=$this->arctype->typeinfo($id);
		$data['arctype']=$this->arctype->typelist($data['info']['addtable']);
		$this->load->view('arctypeedit',$data);
	}
	
	/**
	 * 添加栏目
	 */
	public function type_add()
	{
		$this->load->module('public/public_made/power',array(4,6,"power"));
		$this->load->model("arctype_model","arctype");
		$this->form_validation->set_rules('name', '栏目名称', 'required|max_length[40]|xss_clean');
		$this->form_validation->set_rules('addtable', '栏目模型', 'required|max_length[40]|alpha|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			$data['error']="1";
			$data['model']=$this->arctype->model();
			$data['arctype']=$this->arctype->typelist();
			$this->load->view('arctype',$data);
		}
		else
		{
			$this->input->post(NULL,TRUE);
			if($this->arctype->add_type($_POST))
				$this->load->module('public/public_made/message',array('栏目添加成功！'));
			else
				$this->load->module('public/public_made/message',array('栏目添加失败！'));
		}
	}

	/**
	 * 删除栏目
	 */
	public function delete()
	{
		$this->load->module('public/public_made/power',array(1,6,'power'));
		$this->load->model('arctype_model','arctype');
		$data=$this->input->post("data",TRUE);
		$data=trim($data,",");
		$data=explode(",", $data);
		if(empty($data))
		{
			echo false;
			return ;
		}
		foreach ($data as $row)
			$this->arctype->delete($row);
		echo true;
	}

	/**
	 * 提交修改内容
	 */
	function edit_post()
	{
		$this->load->module('public/public_made/power',array(2,6,'power'));
		$this->load->model("arctype_model","arctype");
		$id=$this->input->post("id",TRUE);
		if(!is_numeric($id))
			$this->load->module('public/public_made/message','URL错误！');
		$this->form_validation->set_rules('name', '栏目名称', 'required|max_length[40]|xss_clean');
		if ($this->form_validation->run() == FALSE )
		{
			$data['error']="1";
			$data['info']=$this->arctype->typeinfo($id);
			$data['arctype']=$this->arctype->typelist($data['info']['addtable']);
			$this->load->view('arctypeedit',$data);
		}
		else
		{
			$this->input->post(NULL,TRUE);
			unset($_POST['id']);
			if($this->arctype->edit_type($_POST,$id))
				$this->load->module('public/public_made/message',array('栏目修改成功！','arctype/arclist'));
			else
				$this->load->module('public/public_made/message',array('栏目修改失败！'));
		}
	}
}