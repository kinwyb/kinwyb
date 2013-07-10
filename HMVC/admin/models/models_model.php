<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Models_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * 删除模型
	 */
	public function delete($row)
	{
		$this->load->dbforge();
		$row=$this->db->where('id',$row)->get('model')->row_array();
		$this->dbforge->drop_table($row['addtable']);
		$this->db->delete("model",array('id'=>$row['id']));
	}
	
	/**
	 * 模型添加
	 */
	public function modeladd($data)
	{
		$row=$this->db->where("addtable",$data['addtable'])->get('model')->row_array();
		if(!empty($row))
			return 1;
		$row=$this->db->where("name",$data['name'])->get('model')->row_array();
		if(!empty($row))
			return 2;
		$this->load->dbforge();
		$this->config->load('addtable');
		$table=$this->config->item($data['type'],'addtable');
		$this->dbforge->add_field($table);
		if(in_array('aid',$table))
			$this->dbforge->add_key('aid');
		else
			$this->dbforge->add_key('typeid');
		$this->dbforge->create_table($data['addtable'],TRUE);
		$this->db->insert("model",$data);
	}

	/**
	 *  获取默认字段列表
	 */
	public function getmodel($id)
	{
		$row=$this->db->where("model_id",$id)->get('modeltype')->result_array();
		$row['table']=$this->db->where('id',$id)->get('model')->row_array();
		return $row;
	}
	
	/**
	 * 获取字段类型列表
	 */
	public function getkv()
	{
		return $this->db->get('fieldtypes')->result_array();
	}
	
	/**
	 * 获取字段类型列表
	 */
	public function getckv()
	{
		return $this->db->get('valcheck')->result_array();
	}

	/**
	 * 修改模型字段
	 */
	public function table_add($data)
	{
		$this->load->dbforge();
		$varchar=array('textarea','select','checkbox','radio','datetime','input');
		$row=$this->db->where(array('model_id'=>$data['model_id'],'tablename'=>$data['tablename']))->get('modeltype')->row_array();
		if(!empty($row))
			return 1;
		if(in_array($data['type'], $varchar))
			$type='varchar';
		else
			$type=$data['type'];
		$fields = array(
				$data['tablename'] => array(
						'type' => $type,
				)
		);
		if(empty($data['notnull']))
			$fields[$data['tablename']]['null']=TRUE;
		if($data['type'] != "text" && $data['type'] != "float" )
		{
			if(empty($data['tablelong']))
				$data['tablelong']=10;
			$fields[$data['tablename']]['constraint']=$data['tablelong'];
		}
		$this->dbforge->add_column($data['table'], $fields);
		unset($data['notnull']);
		unset($data['tablelong']);
		unset($data['table']);
		$this->db->insert('modeltype',$data);
	}

	/**
	 * 删除字段
	 */
	public function table_del($id,$model)
	{
		$this->load->dbforge();
		$row=$this->db->select('addtable')->where("id",$model)->get('model')->row_array();
		$row1=$this->db->select('tablename')->where("id",$id)->get('modeltype')->row_array();
		$this->dbforge->drop_column($row['addtable'], $row1['tablename']);
		$this->db->delete($this->db->dbprefix('modeltype'),array("id"=>$id));
	}
}
