<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Imgarc extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 内容检查
	 */
	private function _arc_valcheck()
	{
		$this->input->post(NULL,TRUE);
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
			$data=$this->load->module('formck/formck_made/getviewinfo',array($_POST['addtable'], $_POST['typeid']));
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
	 * 图集类内容添加
	 */
	public function add_imgsarv()
	{
		$_POST['typeid']=$this->input->post("typeid",TRUE);
		if(!is_numeric($_POST['typeid']))
			$this->load->module('public/public_made/message',array("提交内容有误错误！"));
		$this->load->module('public/public_made/power',array(4,$_POST['typeid'],"wpower"));
		$this->load->model("imgarc_model","imgarc");
		$data=$this->_arc_valcheck();
		$addtable=$data['addtable'];
		unset($data['addtable']);
		if(!empty($data['imgurl']))
		{
			$data['litpic']=$data['imgurl'][0];
			$data['imgs']=count($data['imgurl']);
			$imgurl=$data['imgurl'];
			$imgart=$data['imgkwd'];
			unset($data['imgurl'],$data['imgkwd']);
		}
		$data=$this->imgarc->add_imgsarv($data,$addtable);
		! @$imgurl AND $this->load->module('public/public_made/message',array("内容添加成功！"));
		$insert=array();
		foreach ($imgurl as $key => $value)
		{
			$insert[]=array(
					'aid'=>$data,
					'imgurl'=>$value,
					'art'=>$imgart[$key],
					'addtable'=>$addtable,
			);
		}
		$this->imgarc->add_imgs($insert);
		$this->load->module('public/public_made/message',array("内容添加成功！"));
	}
	
	/**
	 * 内容编辑
	 * @param unknown $addtable
	 * @param unknown $id
	 */
	public function edit($addtable,$id)
	{
		$this->load->model("imgarc_model","imgarc");
		$info=$this->imgarc->getarc($addtable,$id);
		$this->load->module('public/public_made/power',array(2,$info['typeid'],"wpower"));
		$data=$this->load->module('formck/formck_made/getviewinfo',array($addtable, $info['typeid']),TRUE);
		$data['imgs']=$this->imgarc->getimgs($addtable,$id);
		$data['arcrow']=$info;
		unset($info);
		$this->load->view($data['view']."edit",$data);
	}
	
	/**
	 * 修改内容
	 */
	public function edit_imgs()
	{
		$_POST['typeid']=$this->input->post("typeid",TRUE);
		if(!is_numeric($_POST['typeid']))
			$this->load->module('public/public_made/message',array("提交内容有误错误！"));
		$this->load->module('public/public_made/power',array(2,$_POST['typeid'],"wpower"));
		$this->load->model("imgarc_model","imgarc");
		$data=$this->_arc_valcheck();
		$addtable=$data['addtable'];
		$data['imgs']="";
		unset($data['addtable']);
		if(!empty($data['imgurl']) && count($data['imgurl'])>0)
		{
			$data['litpic']=$data['imgurl'][0];
			$data['imgs']=count($data['imgurl']);
			$imgurl=$data['imgurl'];
			$imgart=$data['imgkwd'];
			unset($data['imgurl'],$data['imgkwd']);
		}
		if(!empty($data['imgurln']) && count($data['imgurln'])>0)
		{
			if(isset($data['litpic']))
				$data['litpic']=$data['imgurln'][0];
			$data['imgs']+=count($data['imgurln']);
			$imgurln=$data['imgurln'];
			$imgartn=$data['imgkwdn'];
			unset($data['imgurln'],$data['imgkwdn']);
		}
		$id=$data['arcid'];
		unset($data['arcid']);
		if(empty($data['imgs']))
			unset($data['imgs']);
		$this->imgarc->edit_imgsarv($data,$addtable,$id);
		if(empty($imgurl) && empty($imgurln))
			$this->load->module('public/public_made/message',array("内容修改成功！"));
		if(!empty($imgurl))
		{
			$insert=array();
			foreach ($imgurl as $key => $value)
			{
				$insert[]=array(
						'imgurl'=>$value,
						'art'=>$imgart[$key],
				);
			}
			$this->imgarc->edit_imgs($addtable,$insert,$id);
		}
		if(!empty($imgurln))
		{
			$insert=array();
			foreach ($imgurln as $key => $value)
			{
				$insert[]=array(
						'aid'=>$id,
						'imgurl'=>$value,
						'art'=>$imgartn[$key],
						'addtable'=>$addtable,
				);
			}
			$this->imgarc->add_imgs($insert);
		}
		$this->load->module('public/public_made/message',array("内容修改成功！"));
	}
	
	/**
	 * 删除图集
	 */
	public function imgs_del()
	{
		$this->load->database();
		$data=$this->input->post("data",TRUE);
		$addtable=$this->input->post("addtable",TRUE);
		$arctable=$this->load->module('formck/formck_made/table',array($addtable),TRUE);
		$data=trim($data,",");
		$data=explode(",",$data);
		if(empty($data))
		{
			echo false;
			return ;
		}
		foreach ($data as $row)
		{
			$k=$this->db->where("id",$row)->get('allshop')->row_array();
			if(!$this->load->module('public/public_made/bopower',array(1,$k['typeid'],'wpower'),TRUE))
			{
				echo false;
				return ;
			}
			$this->db->delete($arctable,array('id'=>$row));
			if($arctable == 'allshop')
			{
				$this->db->join("feedback","feedback.id=fdbk_back.fid")->where("feedback.fid",$row)->delete("fdbk_back");
				$this->db->delete("feedback",array("fid"=>$row));
			}
			else
			{
				$this->db->delete('minpin_allimg_feedback',array('fid'=>$row));
			}
			$this->db->delete($addtable,array('aid'=>$row));
			$this->db->where("addtable",$addtable)->delete('imgs',array('aid'=>$row));
		}
		echo true;
	}
}