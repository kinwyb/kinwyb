<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Page_model extends CI_model 
{
	private $nowpage; 				//当前页面
	private $uppage; 				//上一页
	private $downpage; 				//下一页
	private $pagearray; 			//分页数组
	private $row; 					//数据记录条数
	private $pagerow; 				//每页条数
	private $pageall=1; 			//总页数
	private $pro; 					//页码条数 
	private $out=array("where","like");
	private $get;
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * 分页开始方法
	 * @param unknown_type $sql SQL语句
	 * @param unknown_type $nowpage 当前页
	 * @param unknown_type $pagerow 每页条数
	 */
	public function pagestart($sql,$nowpage=1,$pagerow=10,$pro=5)
	{
		$this->pro=$pro;
		$nowpage>1?$this->nowpage=$nowpage:$this->nowpage=1;
		$this->pagerow=$pagerow;
		if(empty($sql['get']))
			return false;
		else
		{
			$this->get=$sql['get'];
			unset($sql['get']);
		}
		foreach($sql as $key => $value)
		{
			if($key =='join')
				foreach($sql[$key] as $value)
					call_user_func_array(array($this->db,$key),$value);
			elseif(is_array($value) AND !in_array($key,$this->out))
				call_user_func_array(array($this->db,$key),$value);
			else
				$this->db->$key($value);
		}
		$this->row=$this->db->get($this->get)->num_rows();
		$this->_page_num();
		$this->_pagenow($sql);
		$this->pagearray['allrow']=$this->row;
		return $this->pagearray;
	}
	
	/**
	 * _page_func()函数用于输出分页页数
	 * @param $rows数据条数
	 * @param $page_l每页数据条数
	 * @return $_page_num页数
	*/
	private function _page_num()
	{
		//数据页数
		/*ceil -- 进一法取整
		 float ceil ( float value )
		返回不小于 value 的下一个整数，value 如果有小数部分则进一位。
		*/
		if($this->row>0)
			$this->pageall=ceil($this->row/$this->pagerow);
		if($this->nowpage>$this->pageall)
			$this->nowpage=$this->pageall;
	}
	
	/**
	 * 分页主方法
	 */
	private function _pagenow($sql)
	{
		$start=($this->nowpage-1)*$this->pagerow;
		$this->db->limit($this->pagerow,$start);
		foreach($sql as $key => $value)
		{
			if($key =='join')
				foreach($sql[$key] as $value)
					call_user_func_array(array($this->db,$key),$value);
			elseif(is_array($value) AND !in_array($key,$this->out))
				call_user_func_array(array($this->db,$key),$value);
			else
				$this->db->$key($value);
		}
		$this->pagearray['row']=$this->db->get($this->get)->result_array();
		$i=$this->nowpage;
		if($i-2<1)//把当前页放在中间,当前页之前显示2条
			$i=1;
		else
			$i=$i-2;
		if(($i+$this->pro)>$this->pageall)
			$top=$this->pageall;
		else
			$top=$this->pro+$i;
		//循环出输出页数
		for(;$i<=$top;$i++)
			$this->pagearray['page'][]=$i;
		//获取上一页
		if(($this->nowpage-1)<1)
			$this->pagearray['uppage']=1;
		else
			$this->pagearray['uppage']=$this->nowpage-1;
		//获取下一页
		if(($this->nowpage+1)>$this->pageall)
			$this->pagearray['downpage']=$this->pageall;
		else
			$this->pagearray['downpage']=$this->nowpage+1;
		//获取首页
		$this->pagearray['first']=1;
		//获取末页
		$this->pagearray['last']=$this->pageall;
		$this->pagearray['nowpage']=$this->nowpage;
	}
}