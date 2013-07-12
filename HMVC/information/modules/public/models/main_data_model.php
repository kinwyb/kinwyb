<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Public_Main_data_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * 获取品牌
	 */
	public function getpinpai()
	{
		return $this->db->order_by('xaixu desc')->get('pinpai')->result_array();
	}
	
	/**
	 * 获取商品种类
	 */
	public function getshop()
	{
		return $this->db->where('model_id',2)->order_by('short desc')->get('arctype')->result_array();
	}
	
	/**
	 * 获取顶部连接
	 */
	public function toplist()
	{
		return $this->db->order_by('sp desc')->get('toplist')->result_array();
	}
	
	/**
	 * 获取评分及评分分布
	 * @param unknown $id
	 */
	public function score($id)
	{
		$sum=0.0;
		$n=array(1,2,3,4,5);
		$d=$this->db->select("score")->where("shopid",$id)->get("feedback")->result_array();
		foreach($d as $key=>$v)
			$d[$key]=$v['score'];
		$row=array_count_values($d);
		foreach($row as $key => $value)
			$sum+=$key*$value;
		foreach($n as $v)
			if(empty($row[$v]))
				$row[$v]=0;
		$d['sum']=count($d);
		empty($d['sum']) AND $d['sum']=1;
		$d['score']=floatval($sum/$d['sum']);
		$d['sorcss']=$d['score']/5*100;
		$d['row']=$row;
		return $d;
	}
	
	/**
	 * 获取搜索条件
	 * @param unknown $addtable
	 */
	public function modeltype($addtable)
	{
		return $this->db->select("modeltype.name,tablename,modeltype.type")->where("addtable",$addtable)->join("modeltype","model_id=model.id")->get('model')->result_array();
	}
	
	/**
	 * 获取网站配置
	 */
	public function system()
	{
		return $this->db->select("webname,keywords,description,pagecache,cachetime")->get('system')->row_array();
	}
	
	/**
	 * 获取分类品牌
	 * @param unknown $shopid
	 */
	public function shoppinpai($shopid)
	{
		return $this->db->select("pinpai.id,name,pinpai.litpic")->order_by("pinpai.xaixu desc")->where("allshop.typeid",$shopid)->join("pinpai","pinpai.id=allshop.pinpai")->group_by("pinpai.id")->get("allshop")->result_array();
	}
}
