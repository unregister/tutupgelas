<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	public function index()
	{
		$url_redirect = null;
		if( $this->input->get('redirect') ){
			$url_redirect = '?redirect='.$this->input->get('redirect');
		}

		$data['url_redirect'] = $url_redirect;
		$layout = "../../themes/admin/layout_login.php";
		$this->load->view( $layout,$data );
	}
}