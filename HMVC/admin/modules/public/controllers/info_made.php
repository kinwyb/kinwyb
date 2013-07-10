<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Public_Info_Made_module extends CI_Module
{
	/**
	 * 构造函数
	 * @return void
	 * @author
	 **/
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 系统内容左侧列表
	 */
	public function arc_left()
	{
		$a=array();
		$power="";
		$row=$this->session->userdata("username");
		empty($row) AND	$this->load->module('public/public_made/message',array('信息出错！请登入'));
		$row=$this->session->userdata("wpower");
		$row=explode(" ",$row);
		foreach($row as $value)
		{
			$value=explode(":", $value);
			$power[]=$value[0];
		}
		$row=$this->load->model('public_model');
		$row=$this->public_model->arctype();
		if($this->session->userdata("userrole") != 15)
		{
			$row=$this->db->select("id,addtable,name,topid,seotitle")->where_in("id",$power)->get('arctype')->result_array();
			foreach ($row as $key => $value)
			{
				if(in_array($key,$power))
				{
					if(!empty($value['topid']))
					{
						$row[$value['topid']]['row'][]=$value;
						unset($row[$key]);
					}
				}
				else
					unset($row[$key]);
			}
		}
		else
		{
			foreach ($row as $key => $value)
			{
				if(!empty($value['topid']))
				{
					$row[$value['topid']]['row'][]=$value;
					unset($row[$key]);
				}
			}
		}
		$this->load->view('arcleft',array('left'=>$row));
	}
	
	/**
	 * 获取系统左侧列表
	 */
	public function sys_left()
	{
		$row=$this->session->userdata("userrole");
		empty($row) AND	$this->load->module('public/public_made/message',array('信息出错！请登入'));
		$row=$this->load->model('public_model');
		$row=$this->public_model->menu();
		if($this->session->userdata("userrole") == 15)
		{
			$this->load->view('sysleft',array('left'=>$row));
			return;
		}
		if($this->session->userdata("userrole") < 2)
		{
			$this->load->view('sysleft',array('left'=>array()));
			return ;
		}
		$table=$this->session->userdata("power");
		$table=explode(" ", $table);
		$power="";
		foreach($table as $value)
		{
			$value=explode(':', $value);
			$power[]=$value[0];
		}
		foreach($row as $key => $value)
		if(!in_array($value['menu_id'],$power))
			unset($row[$key]);
		$this->load->view('sysleft',array('left'=>$row));
	}
}