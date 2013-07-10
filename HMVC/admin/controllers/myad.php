<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Myad extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 获取举报评论列表
	 */
	public function ajax_report($page=1)
	{
		if(!is_numeric($page))
			return ;
		$sql=array("get"=>"report");
		$this->load->model("page_model","page");
		$this->load->helper("text");
		$row=$this->page->pagestart($sql,$page,20);
		foreach($row['row'] as $key => $value)
			$row['row'][$key]['value']=character_limiter($value['value'], 20);
		echo json_encode($row);
	}
	
	/**
	 * 举报评论详细
	 * @param string $info
	 */
	function showreport($info)
	{
		$info=explode('_', $info);
		if(count($info) != 2)
		{
			echo "参数错误！";
			exit();
		}
		$this->load->database();
		if($info[0]=='fdk')
			$table='fdbk_back';
		elseif($info[0]=='feedback')
		$table='feedback';
		else
			$table=$info[0]."_feedback";
		$row=$this->db->where("id",$info[1])->get($table)->row_array();
		if($table == 'feedback')
			echo '标题：'.$row['bktitle'].'<br />优点：'.$row['good'].'<br />缺点：'.$row['bad'].'<br />总结：'.$row['alltxt'].'<br />评论人uid：'.$row['uid'];
		else
			echo '内容：'.$row['info'].'<br />评论人uid：'.$row['uid'];
	}
	
	/**
	 * 处理评论
	 * @param unknown $info
	 * @param unknown $type
	 */
	function postreport($info,$type="bad")
	{
		$this->load->database();
		if($type == 'good')
		{
			$this->db->delete("report",array("id"=>$info));
			echo '忽略成功！';
			exit();
		}
		$info=explode('_', $info);
		if(count($info) != 3)
		{
			echo "删除失败！";
			exit();
		}
		if($info[0]=='fdk')
		{
			$table='fdbk_back';
			unset($info[0]);
		}
		elseif($info[0]=='feedback')
		{
			$table='feedback';
			$info[0]="allshop";
			$rele='shopid';
		}
		else
			$table=$info[0]."_feedback";
		empty($rele) AND $rele='fid';
		$this->load->database();
		if($table=='feedback')
			$this->db->select("score");
		$row=$this->db->select($rele)->where("id",$info[1])->get($table)->row_array();
		if(!empty($info[0]))
		{
			if(!empty($row['score']))
			{
				$n=$this->db->select("feedback,score")->where("id",$row[$rele])->get("allshop")->row_array();
				$score=($n['score']*$n['feedback']-$row['score'])/($n['feedback']-1);
				$this->db->where("id",$row[$rele])->update("allshop",array("score"=>$score));
			}
			$this->db->set('feedback', 'feedback-1', FALSE)->where("id",$row[$rele])->update($info[0]);
		}
		$this->db->delete($table,array("id"=>$info[1]));
		$this->db->delete("report",array("id"=>$info[2]));
		echo '删除成功！';
	}
	
	/**
	 * 首页显示
	 */
	public function show($type)
	{
		$this->load->model('myad_model','myad');
		$data['row']=$this->myad->adlist($type);
		$data['adtype']=$type;
		$this->load->view('myad',$data);
	}
	
	/**
	 * 首页显示
	 */
	public function index()
	{
		$this->load->view('myadlist');
	}
	
	/**
	 * 添加广告
	 */
	public function add_ad()
	{
		$this->load->module('public/public_made/power',array(4,8,"power"));
		if(empty($_POST['timeset']))
			$_POST['timeset']=0;
		$_POST['uptime']=time($_POST['uptime']);
		$_POST['downtime']=time($_POST['downtime']);
		$this->load->database();
		$this->db->insert("myad",$_POST);
		if($this->db->affected_rows())
			$this->load->module('public/public_made/message',array('添加成功！'));
		else
			$this->load->module('public/public_made/message',array('添加失败！'));
	}
	
	/**
	 * 删除栏目
	 */
	public function delete()
	{
		$this->load->module('public/public_made/power',array(1,8,"power"));
		$this->load->model('myad_model','myad');
		$data=$this->input->post("data",TRUE);
		$data=trim($data,",");
		$data=explode(",", $data);
		if(empty($data))
		{
			echo false;
			return ;
		}
		foreach ($data as $row)
			$this->myad->delete($row);
		echo true;
	}
	
	/**
	 * 编辑信息
	 * @param int $id
	 */
	public function edit($id)
	{
		$this->load->module('public/public_made/power',array(2,8,"power"));
		if(!is_numeric($id))
			$this->load->module('public/public_made/message',array('参数错误！'));
		$this->load->model('myad_model','myad');
		$data=$this->myad->edit($id);
		$this->load->view("myadedit",$data);
	}
	
	/**
	 * 修改编辑
	 */
	public function edit_post()
	{
		$this->load->module('public/public_made/power',array(2,8,"power"));
		if(empty($_POST['timeset']))
			$_POST['timeset']=0;
		$_POST['uptime']=time($_POST['uptime']);
		$_POST['downtime']=time($_POST['downtime']);
		$id=$_POST['id'];
		$this->load->database();
		$this->db->where("id",$id)->update("myad",$_POST);
		if($this->db->affected_rows())
			$this->load->module('public/public_made/message',array("修改成功！","myad"));
		else
			$this->load->module('public/public_made/message',array("修改失败！","myad"));
	}
}