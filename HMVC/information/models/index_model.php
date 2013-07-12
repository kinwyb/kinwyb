<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function news($len)
	{
		return $this->db->select('allarc.id aid,arctype.id tid,typeid,name,title,allarc.litpic,page')->join('arctype','typeid=arctype.id')->order_by('flag desc,update desc,allarc.short desc')->limit($len)->get('allarc')->result_array();
	}
	
	public function shop($id,$len)
	{
		return $this->db->select('allshop.id aid,arctype.id tid,title,allshop.litpic,addtable')->where('typeid',$id)->join('arctype','typeid=arctype.id')->order_by('flag desc,update desc,allarc.short desc')->limit($len)->get('allshop')->result_array();
	}
	
}