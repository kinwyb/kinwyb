<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 评论举报页
	 */
	public function feedback()
	{
		$this->load->database();
		$this->load->view("feedback");
	}
	
	/**
	 * 首页显示
	 */
	public function index()
	{
		$this->load->database();
		$this->load->view('index');
	}
	
	/**
	 * 系统设置
	 */
	public function system()
	{
		$this->load->database();
		$data['sys']=$this->db->get('system')->row();
		$this->load->view('system',$data);
	}
	
	/**
	 * 修改密码
	 */
	public function password()
	{
		$this->load->module('public/public_made/power',array(2,4,'power'));
		$this->load->view('password');
	}
	
	/**
	 * 清除缓存
	 */
	public function cache()
	{
		$this->load->module('public/public_made/power',array(2,4,'power'));
		$this->load->view('cache');
	}
	
	/**
	 * 修改密码
	 */
	public function pwd_chg()
	{
		$this->load->module('public/public_made/power',array(2,4,"power"));
		$this->load->model("index_model",'index');
		$this->form_validation->set_rules('pwd', '原始密码', 'required|min_length[8]|xss_clean');
		$this->form_validation->set_rules('npwd', '新密码', 'required|min_length[8]|alpha|xss_clean');
		$this->form_validation->set_rules('npwd2', '新密码重复', 'required|min_length[8]|alpha|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			$data['error']="1";
			$this->load->view('password',$data);
		}
		else
		{
			$pwd=$this->input->post("pwd",TRUE);
			$pwd=substr(md5($pwd), 5, 20);
			$data=$this->index->check_pwd($pwd);
			!$data && $this->load->module('public/public_made/message',array("原始密码错误"));
			$npwd=$this->input->post("npwd",TRUE);
			$npwd2=$this->input->post("npwd2",TRUE);
			if($npwd != $npwd2)
				$this->load->module('public/public_made/message',array("两次新密码不相同！"));
			$npwd=substr(md5($npwd), 5, 20);
			if($this->index->pwd_chg($npwd))
				$this->load->module('public/public_made/message',array("密码修改成功！",'index'));
			else
				$this->load->module('public/public_made/message',array("密码修改失败！",'index/password'));
		}
	}

	/**
	 * 清除缓存
	 */
	public function cache_post()
	{
		$outpage=$this->input->post("outpage",TRUE);
		$memcached=$this->input->post("memcached",TRUE);
		if($memcached)
		{
			$this->load->driver("cache",array('adapter' => $this->config->item('cache_type'), 'backup' => 'dummy'));
			$this->cache->clean();
		}
		if($outpage)
		{
			$base_dir = "../information/cache/";
			$outfile=array('.','..','.htaccess','index.html');
			$fso   = opendir($base_dir);
			$file = array();
			while($flist=readdir($fso)){
				if(!in_array($flist, $outfile))
					$file[]=$flist;
			}
			closedir($fso);
			foreach ($file as $value)
				@unlink ($base_dir.$value);
			unset($outfile,$file,$fso);
		}
		$this->load->module('public/public_made/message',array("缓存清除成功！"));
	}

	/**
	 * 修改水印设置
	 */
	public function syimg()
	{
		$this->load->module('public/public_made/power',array(2,3,"power"));
		$this->input->post(NULL,TRUE);
		$this->load->database();
		$this->db->update("system",$_POST);
		if($this->db->affected_rows())
			$this->load->module('public/public_made/message',array('修改成功!'));
		else
			$this->load->module('public/public_made/message',array('修改失败!'));
	}
	
	/**
	 * 后台系统缓设置
	 */
	public function syscache()
	{
		$this->load->module('public/public_made/power',array(2,3,"power"));
		$this->input->post(NULL,TRUE);
		if(!is_numeric($_POST['cachetime']) OR !is_numeric($_POST['pagecache']))
			$this->load->module('public/public_made/message',array('参数错误!'));
		$this->load->database();
		$this->db->update("system",$_POST);
		if($this->db->affected_rows())
			$this->load->module('public/public_made/message',array('修改成功!'));
		else
			$this->load->module('public/public_made/message',array('修改失败!'));
	}
	
	/**
	 * 修改系统参数
	 */
	public function system_post()
	{
		$this->load->module('public/public_made/power',array(2,3,"power"));
		$this->form_validation->set_rules('webname', '网站名称', 'required|max_length[40]|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			$data['error']="1";
			$this->load->database();
			$data['sys']=$this->db->get('system')->row();
			$this->load->view('system',$data);
		}
		else
		{
			$this->input->post(NULL,TRUE);
			$this->load->database();
			$this->db->update("system",$_POST);
			if($this->db->affected_rows())
				$this->load->module('public/public_made/message',array('修改成功!'));
			else
				$this->load->module('public/public_made/message',array('修改失败!'));
		}
	}

	/**
	 * 顶部导航
	 */
	public function toplist()
	{
		$this->load->model('index_model','index');
		$data['row']=$this->index->toplist();
		$this->load->view('toplist',$data);
	}

	/**
	 * 添加顶部导航
	 */
	public function topadd()
	{
		$this->load->module('public/public_made/power',array(4,10,"power"));
		$this->input->post(NULL,TRUE);
		if(!is_numeric($_POST['sp']))
		{
			echo "排序内容错误！请输入数字";
			exit();
		}
		$this->load->database();
		$this->db->insert("toplist",$_POST);
		echo "添加成功！";
	}

	/**
	 * 删除顶部导航
	 */
	public function topdel()
	{
		$this->load->module('public/public_made/power',array(1,10,"power"));
		$id=$this->input->post("data",TRUE);
		$id=explode(",", trim($id,","));
		$this->load->database();
		$this->db->where_in("id",$id)->delete("toplist");
		echo "删除成功！";
	}
	
	/**
	 * 列出移动的栏目
	 */
	public function chged($addtable)
	{
		$this->load->model('index_model','index');
		$data=$this->index->arctype($addtable);
		$id=array();
		foreach($data as $v)
			$id[]=$v['topid'];
		foreach($data as $key =>$v)
			if(in_array($v['id'],$id))
				unset($data[$key]);
		$id=array();
		foreach($data as $v)
			$id[]=$v;
		echo json_encode($id);
	}
}