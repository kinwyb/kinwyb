<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		//$this->load->module('public/public_made/system');
		$this->load->view('welcome_message');
	}
	
	function test()
	{
		$news=array('0'=>'120','1'=>'121','2'=>'122','3'=>'123','4'=>'124','5'=>'125','6'=>'126','7'=>'127','8'=>'128');
		$k=array_slice($news,0, 3);
		print_r($k);
		print_r($news);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */