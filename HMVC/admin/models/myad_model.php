<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Myad_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * 广告列表
	 */
	function adlist($type)
	{
		return $this->db->select("id,adname,timeset,downtime")->where("idname",$type)->get("myad")->result_array();
	}
	
	/**
	 * 广告删除
	 * @param unknown $id
	 */
	function delete($id)
	{
		$this->db->delete("myad",array("id"=>$id));
	}
	
	/**
	 * 获取修改信息
	 * @param unknown $id
	 */
	function edit($id)
	{
		return $this->db->where("id",$id)->get("myad")->row_array();
	}
}