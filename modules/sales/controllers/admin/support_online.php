<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support_online extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	public function index()
	{
		$this->crud->set_table('sales_yahoo_msg');
		$this->crud->set_subject('Data yahoo messenger');
		$this->crud->required_fields('nama','nama_panggilan','email','yahoo_id');
		$this->crud->field_type('aktif','true_false',array('1'=>'Aktif','0'=>'Tidak aktif'));
		
		$this->crud->unset_read();
		$output = $this->crud->render();
		
		$data['output'] = $output;
		$data['page'] 	= "layout_crud";
		$data['module'] = "sales";
		$this->load->view($this->layout,$data);	
	}
}