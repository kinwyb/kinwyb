<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Arctype_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * 获取模型列表
	 */
	public function model()
	{
		return $this->db->select("addtable,name")->get('model')->result_array();
	}
	
	/**
	 * 列出栏目列表
	 * @param string $tab
	 */
	public function typelist($tab="")
	{
		if(!empty($tab))
			$this->db->where("addtable",$tab);
		return $this->db->select("id,name")->where("topid",0)->get('arctype')->result_array();
	}
	
	/**
	 * 返回栏目详细
	 * @param unknown $id
	 */
	public function typeinfo($id)
	{
		return $this->db->where("id",$id)->get("arctype")->row_array();
	}
	
	/**
	 * 添加栏目
	 */
	public function add_type($data)
	{
		$row=$this->db->select('type')->where('addtable',$data['addtable'])->get('model')->row_array();
		$data['model_id']=$row['type'];
		if(!empty($data['reid']))
		{
			$row=$this->db->select('topid')->where('id',$data['reid'])->get('arctype')->row_array();
			if(empty($row['topid']))
				$data['topid']=$data['reid'];
			else
				$data['topid']=$row['topid'];
		}
		$this->db->insert('arctype',$data);
		return $this->db->affected_rows();
	}
	
	/**
	 * 删除栏目
	 */
	public function delete($row)
	{
		$row1=$this->db->select("model.addtable,type")->where("arctype.id",$row)->or_where("arctype.topid",$row)->join("model","model.addtable=arctype.addtable")->get('arctype')->row_array();
		$this->db->delete('arctype',array("id"=>$row));
		if(!empty($row1))
		{
			switch ($row1['type'])
			{
				case 1:case 3: $addtable="allarc";
				break;
				case 2:$addtable="allshop";
				break;
				case 4:$addtable="allcp";
				break;
				default:$addtable="allarc";
			}
			$row2=$this->db->select("id")->where("id",$row)->or_where("topid",$row)->get("arctype")->result_array();
			$row="";
			if(!empty($row2))
			foreach ($row2 as $value)
				$row=$value['id'].",";
			$row=trim($row,",");
			$row2=$this->db->select("id")->where_in("typeid",$row)->get($addtable)->result_array();
			$this->db->where_in("typeid",$row)->delete($addtable);
			$row="";
			if(!empty($row2))
			foreach ($row2 as $value)
				$row=$value['id'].",";
			$row=trim($row,",");
			$this->db->where_in("aid",$row)->delete($row1['addtable']);
		}
		unset($row,$row1,$row2,$addtable);
	}

	/**
	 * 修改栏目内容
	 * @param unknown $data
	 * @param unknown $id
	 */
	public function edit_type($data,$id)
	{
		if(!empty($data['reid']))
		{
			$row=$this->db->select('topid')->where('id',$data['reid'])->get('arctype')->row_array();
			if(empty($row['topid']))
				$data['topid']=$data['reid'];
			else
				$data['topid']=$row['topid'];
		}
		$this->db->where('id',$id)->update('arctype',$data);
		return $this->db->affected_rows();
	}
}