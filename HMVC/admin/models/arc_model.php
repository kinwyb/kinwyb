<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Arc_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		!isset($this->db) AND $this->load->database();
	}
	
	/**
	 * 添加文章模型内容
	 */
	public function arc_add($data)
	{
		$addtable=$data['addtable'];
		unset($data['addtable']);
		$arctable=$this->load->module('formck/formck_made/table',array($addtable),TRUE);
		$all=array();
		$this->load->config("tablelist");
		$type=$this->config->item($arctable,'tablelist');
		foreach($data as $key => $value)
		{
			if(in_array($key,$type))
			{
				$all[$key]=$value;
				unset($data[$key]);
			}
		}
		if(!empty($all['writer']))
		{
			$this->load->library("ucapi");
			$all['writer_uid']=$this->ucapi->uc_get_user($all['writer']);
			if(is_array($all['writer_uid']) AND is_numeric($all['writer_uid'][0]))
				$all['writer_uid']=$all['writer_uid'][0];
			else
				unset($all['writer_uid']);
		}
		if(!empty($all['litpic']))
			$all['litpic']=$this->load->module('public/public_made/thumb',array($all['litpic'],1),TRUE);
		if(!empty($data['body']))
		{
			$all['description']=mb_substr(strip_tags($data['body']),0,150);
			$all['description']=preg_replace('/[&n|&nb|&nbs|&nbsp]$/',"", $all['description']);
			$all['description']=preg_replace('/#page:(.*)?#/i', "", $all['description']);
			preg_match_all('/#page:(.*)?#/i',$data['body'],$page);
			if(!empty($page[1]))
			{
				$all['page']="";
				foreach($page[1] as $value)
					$all['page'].=$value.":#:";
				$all['page']=trim($all['page'],":#:");
			}
		}
		$this->db->insert($arctable,$all);
		$id=$this->db->insert_id();
		$data['aid']=$id;
		$this->db->insert($addtable,$data);
	}
	
	/**
	 * 获取内容信息
	 * @param unknown $addtable
	 * @param unknown $id
	 */
	public function getarcrow($addtable,$id)
	{
		$arctable=$this->load->module('formck/formck_made/table',array($addtable),TRUE);
		return $this->db->where("id",$id)->join($addtable,$arctable.'.id='.$addtable.'.aid','left')->get($arctable)->row_array();
	}
	
	/**
	 * 修改内容
	 * @param unknown $data
	 */
	function arcupdate($data)
	{
		$addtable=$data['addtable'];
		unset($data['addtable']);
		$arctable=$this->load->module('formck/formck_made/table',array($addtable),TRUE);
		$id=$data['arcid'];
		unset($data['arcid']);
		$all=array();
		$this->load->config("tablelist");
		$type=$this->config->item($arctable,'tablelist');
		foreach($data as $key => $value)
		{
			if(in_array($key,$type))
			{
				$all[$key]=$value;
				unset($data[$key]);
			}
		}
		if(!empty($all['writer']))
		{
			$this->load->library("ucapi");
			$all['writer_uid']=$this->ucapi->uc_get_user($all['writer']);
			if(is_array($all['writer_uid']) AND is_numeric($all['writer_uid'][0]))
				$all['writer_uid']=$all['writer_uid'][0];
			else
				unset($all['writer_uid']);
		}
		if(!empty($all['litpic']))
		{
			$all['litpic']=$this->load->module('public/public_made/thumb',array($all['litpic'],1),TRUE);
		}
		if(!empty($data['body']))
		{
			$all['description']=mb_substr(strip_tags($data['body']),0,150);
			$all['description']=preg_replace('/[&n|&nb|&nbs|&nbsp]$/',"", $all['description']);
			$all['description']=preg_replace('/#page:(.*)?#/i', "", $all['description']);
			preg_match_all('/#page:(.*)?#/i',$data['body'],$page);
			$all['page']="";
			if(!empty($page[1]))
			{
				foreach($page[1] as $value)
					$all['page'].=$value.":#:";
				$all['page']=trim($all['page'],":#:");
			}
		}
		$this->db->where("id",$id)->update($arctable,$all);
		$this->db->where("aid",$id)->update($addtable,$data);
	}
	
	/**
	 * 移动文章
	 * @param unknown $row
	 * @param unknown $addtable
	 */
	public function chged($id,$row,$addtable)
	{
		$addtable= ($addtable == 'archives'?'allarc':'allcp');
		$this->db->where("id",$row)->update($addtable,array("typeid"=>$id));
	}
	
	/**
	 * 获取内容列表
	 * @param unknown $typeid
	 */
	public function shoplist($typeid)
	{
		return $this->db->select("id,title")->where("typeid",$typeid)->get("allshop")->result_array();
	}
}