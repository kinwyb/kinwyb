<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Formck_Formck_Made_module extends CI_Module
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 页面输出参数
	 * @param unknown $addtable
	 * @param unknown $id
	 * @return string
	 */
	public function getviewinfo($addtable)
	{
		$data['addtable']=$addtable;
		$this->load->model('formck_mod');
		$view=$this->formck_mod->getview($addtable);
		if(empty($view))
			$this->load->module('public/public_made/message',array("你所请求的页面不存在","arclist/arcshow"));
		$view=$view['type'];
		switch($view)
		{
			case 1:$data['view']='archives';
			break;
			case 2:$data['view']='shoparc';
			break;
			case 4:$data['view']='chepin';
			break;
			case 5:$data['view']='imgsarv';
			break;
			default : $data['view']='dkarv';
		}
		unset($view);
		return $data;
	}
	
	/**
	 * 获取品牌
	 */
	public function getshop()
	{
		! $this->ts_key('cache') AND $this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
		$row=$this->cache->get('admin_getshop');
		if(empty($row) OR !is_array($row))
		{
			$this->load->model('formck_mod');
			$row=$this->formck_mod->getshop();
 			$this->cache->save('admin_getshop',$row,0);
		}
		return $row;
	}
	
	/**
	 * 获取品牌
	 */
	public function getpinpai()
	{
		! $this->ts_key('cache') AND $this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
		$row=$this->cache->get('admin_getpinpai');
		if(empty($row) OR !is_array($row))
		{
 			$this->load->model('formck_mod');
			$row=$this->formck_mod->getpinpai();
 			$this->cache->save('admin_getpinpai',$row,0);
		}
		return $row;
	}
	
	/**
	 * 获取栏目
	 */
	public function getlist($id)
	{
		! $this->ts_key('cache') AND $this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
 		$row=$this->cache->get('admin_getlist'.$id);
		if(empty($row) OR !is_array($row))
		{
			$this->load->model('formck_mod');
			$row=$this->formck_mod->getlist($id);
 			$this->cache->save('admin_getlist'.$id,$row,0);
		}
		return $row;
	}
	
	/**
	 * 获取附加表模型
	 * @param unknown $addtable
	 * @return unknown
	 */
	public function getaddrow($addtable)
	{
		! $this->ts_key('cache') AND $this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
		$row=$this->cache->get('modeltype'.$addtable);
		if(empty($row) OR !is_array($row))
		{
			$this->load->model('formck_mod');
			$row=$this->formck_mod->getaddrow($addtable);
 			$this->cache->save('modeltype'.$addtable,$row,0);
		}
		return $row;
	}
	
	/**
	 * 获取主表
	 * @param unknown $addtable
	 */
	public function table($addtable)
	{
		! $this->ts_key('cache') AND $this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
		$row=$this->cache->get('table_'.$addtable);
		if(empty($row) OR !is_array($row))
		{
			$this->load->model('formck_mod');
			$row=$this->formck_mod->table($addtable);
 			$this->cache->save('table_'.$addtable,$row,0);
		}
		return $row;
	}
}