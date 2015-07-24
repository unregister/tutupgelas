<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slideshow extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	public function index()
	{
		$this->crud->set_table('slideshow');	
		$this->crud->unset_add_fields('id','width','height','orderby');
		$this->crud->unset_edit_fields('id','width','height','orderby');
		
		$this->crud->set_subject('Data slideshow');
		$this->crud->required_fields('image');
		$this->crud->set_field_upload('image','files/slideshow');
		$this->crud->field_type('active','true_false',array('1' => 'Yes', '0' => 'No'));
		
		$this->crud->columns('image','title','url','active');
		$this->crud->unset_read();
		
		$output = $this->crud->render();
		
		$data['output'] = $output;
		$data['page'] 	= "layout_crud";
		$data['module'] = "slideshow";
		$this->load->view($this->layout,$data);	
	}
}