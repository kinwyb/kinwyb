<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Public_Public_Made_module extends CI_Module {

	/**
	 * 构造函数
	 *
	 * @return void
	 * @author
	 **/
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 获取品牌
	 * @param string $len 品牌数组长度
	 * @return array
	 */
	public function pinpai($len='')
	{
		! $this->ts_key('cache') AND $this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
		$row=$this->cache->get('pinpai');
		if(!is_array($row))
		{
			$this->load->model('main_data_model');
			$row=$this->main_data_model->getpinpai();
			$this->cache->save('pinpai',$row,0);
		}
		!empty($len) AND $row=array_slice($row,0,$len);
		return $row;
	}
	
	/**
	 * 获取商品类目
	 * @param string $len
	 * @return array
	 */
	public function shop($len='')
	{
		! $this->ts_key('cache') AND $this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
		$row=$this->cache->get('shop');
		if(!is_array($row))
		{
			$this->load->model('main_data_model');
			$row=$this->main_data_model->getshop();
			$this->cache->save('shop',$row,0);
		}
		!empty($len) AND $row=array_slice($row,0,$len);
		return $row;
	}
	
	/**
	 * 获取顶部连接
	 * @return array
	 */
	public function toplist()
	{
		! $this->ts_key('cache') AND $this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
		$row=$this->cache->get('toplist');
		if(!is_array($row))
		{
			$this->load->model('main_data_model');
			$row=$this->main_data_model->toplist();
			$this->cache->save('toplist',$row,0);
		}
		return $row;
	}
	
	/**
	 * 获取评分
	 * @param unknown $id
	 * @return Ambigous <number, multitype:>
	 */
	public function score($id,$new=FALSE)
	{
		! $this->ts_key('cache') AND $this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
		$row=$this->cache->get('score_'.$id);
		if(!is_array($row) || $new)
		{
			$this->load->model('main_data_model');
			$row=$this->main_data_model->score($id);
			$this->cache->save('score_'.$id,$row,0);
		}
		return $row;
	}
	
	/**
	 * 获取附加表数据
	 * @param unknown $addtable
	 * @param unknown $len
	 * @return unknown
	 */
	public function modeltype($addtable,$len='')
	{
		! $this->ts_key('cache') AND $this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
		$row=$this->cache->get('modeltype_'.$addtable);
		if(!is_array($row))
		{
			$this->load->model('main_data_model');
			$row=$this->main_data_model->modeltype($addtable);
			$this->cache->save('modeltype_'.$addtable,$row,0);
		}
		!empty($len) AND $row=array_slice($row,0,$len);
		return $row;
	}
	
	/**
	 * 获取系统配置信息
	 * 有问题！！！
	 */
	public function system()
	{
		! $this->ts_key('cache') AND $this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
		$row=$this->cache->get('system');
		if(!is_array($row))
		{
			$this->load->model('main_data_model');
			$row=$this->main_data_model->system();
			$this->cache->save('system',$row,0);
		}
		return $row;
	}
}