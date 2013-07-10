<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dkarv_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 获取模型表的列名
	 * @param unknown $table
	 * @return string
	 */
	public function showcolumns($table)
	{
		$row=$this->db->query("describe ".$this->db->dbprefix($table))->result_array();
		$i=0;
		$d="";
		foreach ($row as $value)
		{
			$d.=$table.'.'.$value['Field'].",";
			if($i-4 == 0)
				break;
			$i++;
		}
		unset($row);
		unset($i);
		return $d;
	}
	
	/**
	 * 添加内容
	 * @param unknown $data
	 */
	public function add_dkarv($data)
	{
		$addtable=$data['addtable'];
		unset($data['addtable']);
		$this->db->insert($addtable,$data);
	}
	
	/**
	 * 添加内容
	 * @param unknown $data
	 */
	public function edit_dkarv($data)
	{
		$addtable=$data['addtable'];
		$id=$data['arcid'];
		unset($data['addtable'],$data['arcid']);
		$this->db->where('id',$id)->update($addtable,$data);
	}
	
}