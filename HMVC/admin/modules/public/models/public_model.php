<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Public_Public_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 获取操作列表
	 * @return array
	 */
	public function arctype()
	{
		$this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
		$menu=$this->cache->get('arctype');
		if(!is_array($menu))
		{
			!isset($this->db) AND $this->load->database();
			$row=$this->db->select("id,addtable,name,topid,seotitle")->get('arctype')->result_array();
			foreach($row as $value)
				$menu[$value['id']]=$value;
			$this->cache->save('arctype',$menu,0);
		}
		return $menu;
	}
	
	/**
	 * 获取操作列表
	 * @return array
	 */
	public function menu()
	{
		$this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
		$menu=$this->cache->get('menu');
		if(!is_array($menu))
		{
			!isset($this->db) AND $this->load->database();
			$menu=$this->db->get("menu")->result_array();
			$this->cache->save('menu',$menu,0);
		}
		return $menu;
	}
}