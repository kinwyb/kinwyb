<?php
class Imgcode
{
	private $width="75";
	private $height="25";
	private $codelength="4";
	private $code;
	private $img;
	private $font;        //指定的字体
	private $fontsize = 20;    //指定字体大小
	private $fontcolor;      //指定字体颜色
	private $charset = 'aSbcdef6gPhkmHnRru2vp5xyzAB4DEtG9MNTUVWY378';
	/**产生随机码
	 * @return string
	 */
	function codenum()
	{
		$_len = strlen($this->charset)-1;
		for($i=0;$i<$this->codelength;$i++)
			$this->code.=$this->charset[mt_rand(0,$_len)];
		$_SESSION['code']=$this->code;
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
		
		//imagefill -- 区域填充
		imagefill($this->img,0,0,$_write);
        
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
        imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
        
		for ($i=0;$i<6;$i++) {  
            $color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));  
            imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);  
       	}  
       	for ($i=0;$i<100;$i++) {  
             $color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));  
              imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);  
       	} 
       	
       	$_x = $this->width / $this->codelength;
       	for ($i=0;$i<$this->codelength;$i++) {
       		$this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
       		imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.2,$this->fontcolor,$this->font,$this->code[$i]);
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
	
	function __construct($width="75",$height="25",$codelength="4")
	{
		$this->width=$width;
		$this->height=$height;
		$this->codelength=$codelength;
		$this->font='views/images/syimg.ttf';
		$this->end();
	}
}