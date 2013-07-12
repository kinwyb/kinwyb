<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Arclist extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 栏目内容列表页
	 * @param string $addtalble 附加表
	 */
	public function arclist_add($addtable,$id)
	{
		if(empty($id) || empty($addtable))
			$this->load->module('public/public_made/message',array("你所请求的页面不存在","arclist/arcshow"));
		$data=$this->load->module('formck/formck_made/getviewinfo',array($addtable),TRUE);
		$data['id']=$id;
		$this->load->view($data['view'],$data);
	}
	
	/**
	 * 获取内容列表
	 */
	public function ajaxlist()
	{
		$this->input->post(NULL,TRUE);
		if(empty($_POST['page']) OR !is_integer($_POST['page']))
			$_POST['page']="1";
		$this->load->model('page_model',"page");
		$sql=array(
				"select"=>$_POST['addtable'].".id,".$_POST['addtable'].".title,update,writer,".$_POST['addtable'].".short,flag,name,addtable",
				"order_by"=>"flag desc,update desc",
				"join"=>array("join1"=>array("arctype","typeid=arctype.id"),),
		);
		if(!empty($_POST['list']))
			$sql['where']=array("typeid"=>$_POST['list']);
		$sql['get']=$_POST['addtable'];
		$result=$this->page->pagestart($sql,$_POST['page'],20);
		foreach ($result['row'] as $key => $value)
		{
			$time=date("Y-m-d H:i:s",$value['update']);
			$result['row'][$key]['update']=$time;
		}
		echo json_encode($result);
	}
	
	/**
	 * 内容检查
	 */
	private function _arc_valcheck()
	{
		$this->form_validation->set_rules('title', '标题', 'required|max_length[40]|xss_clean');
		if(!empty($_POST['valcheck']))
		{
			$_POST['valcheck']=trim($_POST['valcheck'],",");
			$_POST['valcheck']=explode(",",$_POST['valcheck']);
			foreach ($_POST['valcheck'] as $row)
			{
				$row=explode(":",$row);
				$this->form_validation->set_rules($row[1], $row[0], $row[2]);
			}
		}
		unset($_POST['valcheck']);
		if ($this->form_validation->run() == FALSE)
		{
			$data=$this->load->module('formck/formck_made/getviewinfo',array($_POST['addtable'], $_POST['id']),TRUE);
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
			$_POST['update']=strtotime($_POST['update']);
			return $_POST;
		}
	}
	
	/**
	 * 文章添加
	 */
	public function arc_add()
	{
		$this->input->post(NULL,TRUE);
		if(!is_numeric($_POST['typeid']))
			$this->load->module('public/public_made/message',array("提交内容有误错误！"));
		$this->load->module('public/public_made/power',array(4,$_POST['typeid'],"wpower"));
		$this->load->model("arc_model","arc");
		$data=$this->_arc_valcheck();
		$this->arc->arc_add($data);
		$this->load->module('public/public_made/message',array("文章发表成功！"));
	}
	
	/**
	 * 文章内容编辑
	 * @param unknown $addtable
	 * @param unknown $id
	 */
	public function edit($addtable,$id)
	{
		$this->load->model("arc_model","arc");
		$data=$this->load->module('formck/formck_made/getviewinfo',array($addtable),TRUE);
		$data['arcrow']=$this->arc->getarcrow($addtable,$id);
		$this->load->module('public/public_made/power',array(2,$data['arcrow']['typeid'],"wpower"));
		$data['id']=$id;
		$this->load->view($data['view']."edit",$data);
	}
	
	/**
	 * 文章内容修改
	 */
	public function edit_post()
	{
		$this->input->post(NULL,TRUE);
		if(!is_numeric($_POST['typeid']))
			$this->load->module('public/public_made/message',array("提交内容有误错误！"));
		$this->load->module('public/public_made/power',array(2,$_POST['typeid'],"wpower"));
		$this->load->model("arc_model","arc");
		$data=$this->_arc_valcheck();
		$this->arc->arcupdate($data);
		$this->load->module('public/public_made/message',array("内容修改成功!","arclist/arclist_add/".$data['addtable']."/".$data['typeid']));
	}

	/**
	 * 删除内容
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
			$k=$this->db->where("id",$row)->get('allarc')->row_array();
			if(!$this->load->module('public/public_made/bopower',array(1,$k['typeid'],"wpower"),TRUE))
			{
				echo false;
				return ;
			}
			$this->db->delete('allarc',array("id"=>$row));
			$this->db->delete('allarc_feedback',array("fid"=>$row));
			$this->db->delete($addtable,array("aid"=>$row));
		}
		echo true;
	}
	
	/**
	 * 栏目修改
	 */
	public function chged()
	{
		$this->load->model('arc_model','arc');
		$data=$this->input->post("data",TRUE);
		$addtable=$this->input->post("addtable",TRUE);
		$id=$this->input->post("id",TRUE);
		$data=trim($data,",");
		$data=explode(",", $data);
		if(empty($data) || empty($addtable))
		{
			echo false;
			return ;
		}
		try {
			foreach ($data as $row)
				$this->arc->chged($id,$row,$addtable);
			echo true;
		} catch (Exception $e) {
			echo false;
		}
		
	}
	
	/**
	 * 内容列表
	 */
	public function shoplist()
	{
		$this->load->model("arc_model","arc");
		$typeid=$this->input->post("type",TRUE);
		$row=$this->arc->shoplist($typeid);
		echo json_encode($row);
	}
}