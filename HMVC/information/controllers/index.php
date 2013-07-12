<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
	}
	
	public function Index()
	{
		$data['system']=$this->load->module('public/public_made/system',array(),TRUE);
		$this->load->model('index_model');
		$data['news']=$this->_news();
		print_r($data);
		$this->load->view('welcome_message',$data);
		$this->output->cache($data['system']['pagecache']);
		$this->output->enable_profiler(TRUE);
	}
	
	private function _news()
	{
		$news=$this->index_model->news(40);
		$row['hotarc']=array_slice($news,0,3);
		$len=count($news);
		$j=0;
		for($i=3;$i<$len;$i++)
		{
			if($j<7 AND !empty($news[$i]['litpic']))
			{
				$row['arc_img'][]=$news[$i];
				$j++;
			}
			else
				$row['arc'][]=$news[$i];
		}
		return $row;
	}
	
	private function _cepin($tid)
	{
		$row=$this->load->cache('chepin_'.$tid);
		if(!is_array($row))
		{
			$this->load->model('index_model');
			$row['shop']=$this->index_model->shop($tid,20);
			
		}
		return $row;
	}
	
}