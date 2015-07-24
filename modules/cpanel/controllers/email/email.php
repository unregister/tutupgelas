<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		check_user('email.manager');	
	}
	
	public function index()
	{
		$this->crud->set_table('sys_email_template');
		$this->crud->set_subject('Data email template');
		
		$this->crud->required_fields('template_name','subject','content','email_from');
		$this->crud->unique_fields('template_name');
		$this->crud->columns('template_name','subject','email_from','email_name');
		$output = $this->crud->render();
		
		$data['output'] = $output;
		$data['page'] = "layout_crud";
		$data['module'] = "cpanel";
		$data['title'] = "Email template";
		
		$this->load->view($this->layout,$data);
	}
}