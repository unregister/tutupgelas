<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	public function index()
	{
		$data['title'] = "Dashboard";
		$data['module'] = "home";
		$data['page'] = "layout_dashboard";
		$this->load->view($this->layout,$data);	
	}
}