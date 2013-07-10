<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Upload extends MY_Controller
{
	private $system=array();
	private $sy="0";
	private $order="";
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->system=$this->db->select("syimg,syimg_rh,syimg_txt,syimg_fw,syimg_type")->get("system")->row_array();
		$this->sy=$this->system["syimg"];
		$this->system=$this->syimg_config();
		$this->load->library('image_lib');
		$this->load->library("upload");
	}
	
	/**
	 * 编辑器图片上传
	 */
	public function imgup()
	{
		$updata=array(
				"upload_path"=>"../uploads/arcimg/",
				"allowed_types"=>'gif|jpg|png|jpeg|bmp',
				"max_size"=>"2046",
				"max_filename"=>"60",
				"encrypt_name"=>"TRUE"
		);
		$this->upload->initialize($updata);
		if ( ! $this->upload->do_upload("imgFile"))
		{
			$error = array('error' => $this->upload->display_errors());
			print_r($error);
		}
		else
		{
			$data = $this->upload->data();
			!empty($this->sy) AND $this->syimg($data['dir_file_name']);
			$this->db->insert("upload",array("url"=>$data['dir_file_name'],"time"=>time()));
			echo json_encode(array('error'=>0,'url'=>$data['dir_file_name']));
		}
	}
	
	/**
	 * 附件浏览查看
	 */
	public function file_list_json()
	{
		$result=$this->_file_list();
		//输出JSON字符串
		header('Content-type: application/json; charset=UTF-8');
		echo json_encode($result);
	}
	
	/**
	 * 便利附件
	 * @return multitype:number Ambigous <string, unknown> Ambigous <string, mixed> string Ambigous <multitype:, string>
	 */
	private function _file_list()
	{
		$this->input->get(NULL,TRUE);
	
		$root_path = dirname(__FILE__) . '/../../uploads/';
		$root_url = '/uploads/';
	
		//图片扩展名
		$ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
	
		//目录名
		$dir_name = empty($_GET['dir']) ? '' : trim($_GET['dir']);
		if (!in_array($dir_name, array('', 'imgsarv', 'flash', 'arcimg', 'file'))) {
			echo "Invalid Directory name.";
			exit;
		}
		if ($dir_name !== '') {
			$root_path .= $dir_name . "/";
			$root_url .= $dir_name . "/";
			if (!file_exists($root_path)) {
				mkdir($root_path);
			}
		}
	
		//根据path参数，设置各路径和URL
		if (empty($_GET['path'])) {
			$current_path = realpath($root_path) . '/';
			$current_url = $root_url;
			$current_dir_path = '';
			$moveup_dir_path = '';
		} else {
			$current_path = realpath($root_path) . '/' . $_GET['path'];
			$current_url = $root_url . $_GET['path'];
			$current_dir_path = $_GET['path'];
			$moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
		}
		//echo realpath($root_path);
		//排序形式，name or size or type
		$this->order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);
	
		//不允许使用..移动到上一级目录
		if (preg_match('/\.\./', $current_path)) {
			echo 'Access is not allowed.';
			exit;
		}
		//最后一个字符不是/
		if (!preg_match('/\/$/', $current_path)) {
			echo 'Parameter is not valid.';
			exit;
		}
		//目录不存在或不是目录
		if (!file_exists($current_path) || !is_dir($current_path)) {
			echo 'Directory does not exist.';
			exit;
		}
	
		//遍历目录取得文件信息
		$file_list = array();
		if ($handle = opendir($current_path)) {
			$i = 0;
			while (false !== ($filename = readdir($handle))) {
				if ($filename{0} == '.') continue;
				$file = $current_path . $filename;
				if (is_dir($file)) {
					$file_list[$i]['is_dir'] = true; //是否文件夹
					$file_list[$i]['has_file'] = (count(scandir($file)) > 2); //文件夹是否包含文件
					$file_list[$i]['filesize'] = 0; //文件大小
					$file_list[$i]['is_photo'] = false; //是否图片
					$file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
				} else {
					$file_list[$i]['is_dir'] = false;
					$file_list[$i]['has_file'] = false;
					$file_list[$i]['filesize'] = filesize($file);
					$file_list[$i]['dir_path'] = '';
					$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					$file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
					$file_list[$i]['filetype'] = $file_ext;
				}
				$file_list[$i]['filename'] = $filename; //文件名，包含扩展名
				$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
				$i++;
			}
			closedir($handle);
		}
		usort($file_list, array("upload","cmp_func"));
	
		$result = array();
		//相对于根目录的上一级目录
		$result['moveup_dir_path'] = $moveup_dir_path;
		//相对于根目录的当前目录
		$result['current_dir_path'] = $current_dir_path;
		//当前目录的URL
		$result['current_url'] = $current_url;
		//文件数
		$result['total_count'] = count($file_list);
		//文件列表数组
		$result['file_list'] = $file_list;
		return $result;
	}
	
	/**
	 * 自定义文件排序数组
	 * @param unknown $a
	 * @param unknown $b
	 * @return number
	 */
	private function cmp_func($a, $b) {
		if ($a['is_dir'] && !$b['is_dir']) {
			return -1;
		} else if (!$a['is_dir'] && $b['is_dir']) {
			return 1;
		} else {
			if ($this->order == 'size') {
				if ($a['filesize'] > $b['filesize']) {
					return 1;
				} else if ($a['filesize'] < $b['filesize']) {
					return -1;
				} else {
					return 0;
				}
			} else if ($this->order == 'type') {
				return strcmp($a['filetype'], $b['filetype']);
			} else {
				return strcmp($a['filename'], $b['filename']);
			}
		}
	}
	
	/**
	 * 删除图片
	 */
	public function delimg()
	{
		$imagename=$this->input->post("imagename",TRUE);
		$this->db->select("url");
		$this->db->where("url",$imagename);
		$result=$this->db->get("upload");
		if($result->num_rows()>0)
		{
			$this->db->delete("upload",array("url"=>$imagename));
			unlink('..'.$imagename);
			echo true;
		}
		else
			echo false;
	}
	
	/**
	 * 删除图片集
	 */
	public function delimgs()
	{
		$imagename=$this->input->post("imagename",TRUE);
		$this->db->select("url");
		$this->db->where("url",$imagename);
		$result=$this->db->get("upload");
		if($result->num_rows()>0)
		{
			$thumb=$this->db->select("thumb")->where("imgurl",$imagename)->get("imgs")->row_array();
			$this->db->delete("imgs",array("imgurl"=>$imagename));
			$this->db->delete("upload",array("url"=>$imagename));
			unlink('..'.$thumb['thumb']);
			unlink('..'.$imagename);
			echo true;
		}
		else
			echo false;
	}
	
	/**
	 * 多图上传
	 */
	public function imgsup()
	{
		$row=array();
		$updata=array(
				"upload_path"=>"../uploads/imgsarv/",
				"allowed_types"=>'gif|jpg|png|jpeg|bmp',
				"max_size"=>"2046",
				"max_filename"=>"60",
				"encrypt_name"=>"TRUE"
		);
		$this->upload->initialize($updata);
		foreach($_FILES as $key => $value)
		{
			if ( ! $this->upload->do_upload($key))
			{
				$error = array('error' =>1,'msg'=> $this->upload->display_errors());
				$row[]=$error;
			}
			else
			{
				$data = $this->upload->data();
				!empty($this->sy) AND $this->syimg($data['dir_file_name']);
				$this->db->insert("upload",array("url"=>$data['dir_file_name'],"time"=>time()));
				$row[]=array('error'=>0,'url'=>$data['dir_file_name']);
			}
		}
		echo json_encode($row);
	}
	
	/**
	 * 获取水印设置配置
	 * @return string
	 */
	private function syimg_config()
	{
		if($this->system["syimg_type"]==1)
		{
			$config['wm_type'] = 'overlay';
			$config['wm_overlay_path']='../uploads/watermark.png';
			$config['wm_opacity']=$this->system['syimg_rh'];
			$config['wm_vrt_offset']='5';
			$config['wm_hor_offset']='5';
		}else
		{
			$config['wm_text'] = $this->system["syimg_txt"];
			$config['wm_type'] = 'text';
			$config['wm_font_size'] = '14';
			$config['wm_font_color'] = 'ff0000';
			$config['wm_font_path']='../uploads/syimg.ttf';
		}
		if(in_array($this->system['syimg_fw'],array("3","6","9")))
			$config['wm_hor_alignment'] = 'right';
		if(in_array($this->system['syimg_fw'],array("2","5","8")))
			$config['wm_hor_alignment'] = 'center';
		if(in_array($this->system['syimg_fw'],array("1","4","7")))
			$config['wm_hor_alignment'] = 'left';
		if(in_array($this->system['syimg_fw'],array("1","2","3")))
			$config['wm_vrt_alignment'] = 'top';
		if(in_array($this->system['syimg_fw'],array("4","5","6")))
			$config['wm_vrt_alignment'] = 'middle';
		if(in_array($this->system['syimg_fw'],array("7","8","9")))
			$config['wm_vrt_alignment'] = 'bottom';
	
		return $config;
	}
	
	/**
	 * 添加水印
	 * @param unknown $img
	 */
	private function syimg($img)
	{
		$image_size=getimagesize('..'.$img);
		if($image_size[0]>200 && $image_size[1]>200)
		{
			$this->system['source_image']='..'.$img;
			$this->image_lib->initialize($this->system);
			$this->image_lib->watermark();
		}
	}
	
	/**
	 * 水印设置
	 */
	function syimg_test()
	{
		$config=$this->system;
		$config['dynamic_output']=TRUE;
		$config['source_image']="../uploads/sytest.jpg";
		$this->image_lib->initialize($config);
		$this->image_lib->watermark();
	}
}