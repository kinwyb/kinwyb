<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * 验证密码
	 */
	public function check_pwd($pwd)
	{
	
		$row=$this->db->select("password")->where("username",$_SESSION['username'])->get('admin')->row_array();
		if($row['password'] != $pwd)
			return false;
		else
			return true;
	}
	
	/**
	 * 修改密码
	 * @param unknown $pwd
	 */
	public function pwd_chg($pwd)
	{
		$this->db->where("username",$_SESSION['username'])->update('admin',array("password"=>$pwd));
		return $this->db->affected_rows();
	}
	
	/**
	 * 导航列表
	 */
	public function toplist()
	{
		return $this->db->order_by("sp")->get("toplist")->result_array();
	}
	
	/**
	 * 文章类目录
	 */
	public function arctype($addatble='')
	{
		if(empty($addatble))
			$this->db->where_in("addtable",array("chepin","archives"));
		else
			$this->db->where("addtable",$addatble);
		return $this->db->select("id,name,topid")->get("arctype")->result_array();
	}
	
	/**
	 * 添加采集
	 * @param unknown $data
	 * @param unknown $type
	 */
	public function snoopy_add($data,$type)
	{
		$r=$this->db->select("addtable")->where("id",$type)->get("arctype")->row_array();
		$r['addtable']=='archives' AND $addtable='allarc';
		$r['addtable']=='chepin' AND $addtable='allcp';
		foreach($data['val'] as $key => $value)
		{
			$this->db->insert($addtable,array("title"=>$data['title'][$key],"typeid"=>$type,"writer"=>$data['wname'][$key],"update"=>strtotime($data['time'][$key]),"writer_uid"=>$data['wurl'][$key]));
			$id=$this->db->insert_id();
			$this->db->insert($r['addtable'],array("aid"=>$id,"body"=>$value));
		}
	}
	
	/**
	 * 用户列表
	 */
	function user_list()
	{
		return $this->db->where("uid !=",1)->get("admin")->result_array();
	}
	
	/**
	 * 权限设置栏目列表
	 */
	function arcrole()
	{
		$row=$this->db->where("topid !=",0)->get("arctype")->result_array();
		foreach($row as $value)
			$id[]=$value['topid'];
		return $this->db->where_not_in("id",$id)->get("arctype")->result_array();
	}
	
	/**
	 * 管理权限列表
	 */
	function adminrole()
	{
		return $this->db->where_not_in("menu_id",array("11","12","13"))->get("menu")->result_array();
	}
	
	/**
	 * 获取用户信息
	 * @param unknown $id
	 */
	function user_info($id)
	{
		return $this->db->where("uid",$id)->get("admin")->row_array();
	}
}