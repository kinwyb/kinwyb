<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Formck_Formck_Mod extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		!isset($this->db) AND $this->load->database();
	}
	
	/**
	 * 获取栏目类别
	 */
	public function getshop()
	{
		return $this->db->select("id,name")->where("model_id",2)->get("arctype")->result_array();
	}
	
	/**
	 * 获取品牌列表
	 * @param string $id 栏目的ID
	 */
	function getpinpai()
	{
		return $this->db->select("id,name")->get('pinpai')->result_array();
	}
	
	/**
	 * 获取栏目列表
	 * @param string $id 栏目的ID
	 */
	function getlist($id)
	{
		$row2=$this->db->select("id,name,topid")->where("id",$id)->get("arctype")->row_array();
		$row1=$this->db->select('id,name')->where('topid',$row2['typeid'])->get('arctype')->result_array();
		unset($row1['typeid']);
		$row1[]=$row2;
		if($this->session->userdata("userrole") != 15)
		{
			$row2=$this->session->userdata("wpower");
			$row2=explode(" ", $row2);
			foreach($row2 as $value)
				$ids[]=$value[0];
			foreach($row1 as $key => $value)
				if(!in_array($value['id'],$row2)) 
					unset($row1[$key]);
		}
		return $row1;
	}
	
	/**
	 * 获取模型数据库自定义字段
	 * @param unknown $addtable
	 */
	public function getaddrow($addtable)
	{
		return $this->db->select('modeltype.*')->where("model.addtable",$addtable)->join('model','model.id=modeltype.model_id')->get('modeltype')->result_array();
	}
	
	/**
	 * 获取类型列表
	 * @param string $addtable 附加表
	 */
	public function getview($addtable)
	{
		return $this->db->select("type")->where("addtable",$addtable)->get('model')->row_array();
	}
	
	/**
	 * 获取主表信息
	 * @param unknown $addtable
	 * @return string
	 */
	public function table($addtable)
	{
		$row=$this->db->select("model_id")->where("addtable",$addtable)->get('arctype')->row();
		switch ($row->model_id)
		{
			case 1: $row='allarc';
				break;
			case 2: $row='allshop';
				break;
			case 4: $row='allcp';
				break;
			case 5: $row='allimg';
				break;
			default: $row='';
		}
		return $row;
	}
}