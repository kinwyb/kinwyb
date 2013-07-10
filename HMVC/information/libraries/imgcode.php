<?php
class Imgcode
{
	private $width="75";
	private $height="25";
	private $codelength="4";
	private $codenum;
	private $img;
	/**产生随机码
	 * @return string
	 */
	function codenum()
	{
		$num=strtoupper(substr(md5(rand()),0,$this->codelength));
		$_SESSION['code']=$num;
		for($i=0;$i<$this->codelength;$i++)
		{
			$this->codenum[$i]=substr($num, $i,1);
		}
	}
	
	/**header()设置文件类型
	 */
	function header()
	{
		header('Content-Type:image/png');
	}
	
	/**img()图像创建
	 */
	function img()
	{
		//imagecreatetruecolor -- 新建一个真彩色图像
		$this->img=imagecreatetruecolor($this->width, $this->height);
	}
	
	/**doimg()图像处理
	 */
	function doimg()
	{
		//imagecolorallocate -- 为一幅图像分配颜色
		$_write=imagecolorallocate($this->img,255,255,255);
		$_black=imagecolorallocate($this->img,0,0,0);
		
		//imagefill -- 区域填充
		imagefill($this->img,0,0,$_write);
		
		//做验证码边框
		 //imagerectangle -- 画一个矩形
		//imagerectangle($this->img,0,0,$this->width-1,$this->height-1,$_black);
		
		//随机雪花
		for($i=0;$i<100;$i++)
		{
			$_color=imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
			//imagestring -- 水平地画一行字符串
			imagestring($this->img,1,mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$_color);
		}
		
		//随机画出6个线条
		//for循环得到6个随机颜色
		for($i=0;$i<6;$i++)
		{
			$_color=imagecolorallocate($this->img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			//imageline -- 在图形 im中，从 x1 , y1到 x2 , y2(左上方是 0,0)画一条线，线段的颜色为参数 col。
			imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$_color);
		}
		//图像中插入验证码
		for ($i=0;$i<$this->codelength;$i++)
		{
			$_rnd_color = imagecolorallocate($this->img,mt_rand(0,100),mt_rand(0,150),mt_rand(0,200));
			//floor取得小于指定数的最大整数值
   			$x = floor($this->width/$this->codelength)*$i+mt_rand(0,10);//分配每个验证码的宽度
   			$y = rand(0,$this->height-15);//取得每个验证码的高度，随机其高度的位子
			imagestring($this->img,5,$x,$y,$this->codenum[$i],$_rnd_color);
		}
	}
	
	/**outimg()输出图像
	 */
	function outimg()
	{
		//imagepng -- 以 PNG 格式将图像输出到浏览器或文件
		imagepng($this->img);
		//imagedestroy -- 销毁一图像.示范系统文件
		imagedestroy($this->img);
	}
	
	/**end()输出图像
	 */
	function end()
	{
		$this->header();
		$this->img();
		$this->codenum();
		$this->doimg();
		$this->outimg();
	}
	
	function __construct($data)
	{
		$this->width=$data['width'];
		$this->height=$data['height'];
		$this->codelength=$data['codelength'];
		$this->end();
	}
}