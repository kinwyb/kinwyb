<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Search_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * 获取附加表
	 */
	public function model()
	{
		return $this->db->where("type","2")->get("model")->result_array();
	}
	
	/**
	 * 获取附加表栏目
	 * @param unknown $id
	 */
	public function modeltype($id)
	{
		return $this->db->where("model_id",$id)->get("modeltype")->result_array();
	}
	
	/**
	 * 获取addtbale
	 * @param unknown $id
	 */
	public function model_id($id)
	{
		return $this->db->select("addtable")->where("id",$id)->get("model")->row_array();
	}
	
	/**
	 * 添加
	 * @param unknown $addtable
	 * @param unknown $data
	 */
	public function add_post($addtable,$data,$name)
	{
		$info=array("addtable"=>$addtable,"view"=>$data,"name"=>$name);
		$this->db->insert("search",$info);
		return $this->db->affected_rows();
	}

	/**
	 * 删除
	 * @param unknown $id
	 */
	function del($id)
	{
		$this->db->delete("search",array("id",$id));
		return $this->db->affected_rows();
	}
	
}