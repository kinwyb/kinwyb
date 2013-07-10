<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Imgarc_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	/**
	 * 添加内容
	 * @param unknown $data
	 */
	public function add_imgsarv($data,$addtable)
	{
		$arctable=$this->load->module('formck/formck_made/table',array($addtable),TRUE);
		$all=array();
		$this->load->config('tablelist');
		$type=$this->config->item($arctable,'tablelist');
		foreach($data as $key => $value)
		{
			if(in_array($key,$type))
			{
				$all[$key]=$value;
				unset($data[$key]);
			}
		}
		if(!empty($all['litpic']))
			$all['litpic']=$this->load->module('public/public_made/thumb',array($all['litpic'],0),TRUE);
		$this->db->insert($arctable,$all);
		$id=$this->db->insert_id();
		$data['aid']=$id;
		$this->db->insert($addtable,$data);
		return $id;
	}
	
	/**
	 * 获取内容信息
	 * @param unknown $addtable
	 * @param unknown $id
	 */
	public function getimgs($addtable,$id)
	{
		return $this->db->where(array("aid"=>$id,"addtable"=>$addtable))->get('imgs')->result_array();
	}
	
	/**
	 * 获取内容信息
	 * @param unknown $addtable
	 * @param unknown $id
	 */
	public function getarc($addtable,$id)
	{
		$arctable=$this->load->module('formck/formck_made/table',array($addtable),TRUE);
		return $this->db->where("id",$id)->join($addtable,$arctable.'.id='.$addtable.'.aid','left')->get($arctable)->row_array();
	}
	
	/**
	 * 添加图片内容
	 */
	public function add_imgs($data)
	{
		foreach ($data as $value)
		{
			$value['thumb']=$this->load->module('public/public_made/thumb',array($value['imgurl'],0),TRUE);
			$this->db->insert('imgs',$value);
		}
	}
	
	/**
	 * 修改文章
	 */
	public function edit_imgsarv($data,$addtable,$id)
	{
		$arctable=$this->load->module('formck/formck_made/table',array($addtable),TRUE);
		$all=array();
		$this->load->config('tablelist');
		$type=$this->config->item($arctable,'tablelist');
		foreach($data as $key => $value)
		{
			if(in_array($key,$type))
			{
				$all[$key]=$value;
				unset($data[$key]);
			}
		}
		if(!empty($all['litpic']))
			$all['litpic']=$this->load->module('public/public_made/thumb',array($all['litpic'],0),TRUE);
		$this->db->where("id",$id)->update($arctable,$all);
		$this->db->where("aid",$id)->update($addtable,$data);
	}
	
	/**
	 * 修改图片
	 */
	public function edit_imgs($addtable,$data,$id)
	{
		$row=$this->db->select("id")->where(array("addtable"=>$addtable,"aid"=>$id))->get('imgs')->result_array();
		foreach ($row as $key => $value)
			$this->db->where("id",$value['id'])->update('imgs',$data[$key]);
	}
}