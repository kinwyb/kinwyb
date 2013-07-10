<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Modelview extends MY_controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 模型首页
	 */
	function models()
	{
		$this->load->view('model');
	}
	
	/**
	 * ajax获取模型列表
	 */
	function ajaxmodel()
	{
		$this->load->model("page_model","page");
		$page=$this->input->post("page",TRUE);
		if(empty($page) OR !is_numeric($page))
			$page="1";
		$sql=array(
				"order_by"=>"id",
				"get"=>"model",
		);
		$result=$this->page->pagestart($sql,$page,20);
		echo json_encode($result);
	}
	
	/**
	 * 删除模型
	 */
	function delete()
	{
		$this->load->module('public/public_made/power',array(1,2,"power"));
		$this->load->model('models_model','model');
		$data=$this->input->post("data",TRUE);
		$data=trim($data,",");
		$data=explode(",", $data);
		if(empty($data))
		{
			echo false;
			return ;
		}
		foreach ($data as $row)
			$this->model->delete($row);
		echo true;
	}
	
	/**
	 * 添加模型
	 */
	function modeladd()
	{
		$this->load->module('public/public_made/power',array(4,2,"power"));
		$this->form_validation->set_rules('name', '模型名称', 'required|max_length[40]|xss_clean');
		$this->form_validation->set_rules('addtable', '模型附加表', 'required|max_length[40]|alpha|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			$data['error']="1";
			$this->load->view('model',$data);
		}
		else
		{
			$this->load->model('models_model','model');
			$this->input->post(NULL,TRUE);
			$row=$this->model->modeladd($_POST);
			if(!empty($row))
				switch ($row)
				{
					case 1: $this->load->module('public/public_made/message',array("模型附加表已存在！","modelview/models"));
						break;
					case 2: $this->load->module('public/public_made/message',array("模型名称已存在！","modelview/models"));
						break;
				}
			else
				$this->load->module('public/public_made/message',array("新模型添加成功！","modelview/models"));
		}
	}
	
	/**
	 * 模型字段页
	 */
	function table($id)
	{
		$this->load->module('public/public_made/power',array(2,2,"power"));
		$this->load->model("models_model","model");
		$data['info']=$this->model->getmodel($id);
		foreach ($data['info'] as $key=>$row)
		{
			if(empty($row))
				unset($data['info'][$key]);
		}
		if(empty($data['info']))
			$this->load->module('public/public_made/message',array("数据模型不存在!",'modelview/models'));
		$data['kv']=$this->model->getkv();
		$data['ckv']=$this->model->getckv();
		$this->load->view('modeledit',$data);
	}
	
	/**
	 * 添加字段
	 */
	function table_add()
	{
		$this->load->module('public/public_made/power',array(4,2,"power"));
		$this->load->model('models_model','model');
		$this->form_validation->set_rules('name','字段名称', 'required|max_length[20]|xss_clean');
		$this->form_validation->set_rules('tablename','字段标记', 'required|max_length[20]|alpha|xss_clean');
		if($this->form_validation->run() == FALSE)
		{
			$id=$this->input->post("model_id",TRUE);
			$data['info']=$this->model->getmodel($id);
			$data['error']=1;
			$data['kv']=$this->model->getkv();
			$data['ckv']=$this->model->getckv();
			$this->load->view('modeledit',$data);
		}else
		{
			$this->input->post(NULL,TRUE);
			$result=$this->model->table_add($_POST);
			if(!empty($result))
				$this->load->module('public/public_made/message',array('字段标记重复！',"modelview/table/".$_POST['model_id']));
			else
				$this->load->module('public/public_made/message',array('字段添加成功！',"modelview/table/".$_POST['model_id']));
		}
	}
	
	/**
	 * 删除字段
	 */
	function table_del()
	{
		$this->load->module('public/public_made/power',array(1,2,"power"));
		$this->load->model('models_model','model');
		$id=$this->input->post('data');
		$model=$this->input->post('model');
		$this->model->table_del($id,$model);
		echo true;
	}
}