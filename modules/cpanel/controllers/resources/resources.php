<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();	
		check_user('resources.manager');
	}
	
	public function index()
	{
		
		
		$data['page'] 	= "layout_tree_resources";
		$data['module'] = "cpanel";
		$data['title'] = "Resources manager";
		$this->load->view($this->layout,$data);	
	}
	
	
}