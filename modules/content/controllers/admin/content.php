<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		check_user('Content');	
		check_user('Content.data');
	}
	
	public function index()
	{	
		
		$html  = "<script type=\"text/javascript\">\n";
		$html .= "$(document).ready(function(){	$('#field-title').friendurl({id : 'field-seo', divider: '-'}); });";
		$html .= "</script>";
		
		$allow_add = check_user('Content.add',FALSE);
		$allow_edit = check_user('Content.edit',FALSE);
		$allow_delete = check_user('Content.delete',FALSE);

		$this->crud->set_table('content');
		$this->crud->where('type_id',1);		
		$this->crud->set_subject('Data content');
		$this->crud->required_fields('title','content');
		$this->crud->field_type('active','true_false',array('1' => 'Yes', '0' => 'No'));
		$this->crud->field_type('created_by','hidden',$this->user->user_id);
		$this->crud->field_type('created','hidden',date('Y-m-d H:i:s'));
		
		$this->crud->unset_add_fields('category_id','hits','meta_title');
		$this->crud->unset_edit_fields('category_id','hits','meta_title','created_by','created','type_id');
		
		$this->crud->columns('title','seo','created');
		$this->crud->field_type('type_id','hidden',1);
		$this->crud->set_field_upload('image','files/content');
		
		$this->crud->field_type('meta_description','text');
		$this->crud->unset_texteditor('meta_description');
		
		if(!$allow_add){
			$this->crud->unset_add();
		}
		if(!$allow_edit){
			$this->crud->unset_edit();
		}
		if(!$allow_delete){
			$this->crud->unset_delete();
		}

		$output = $this->crud->render();
		
		$data['title'] 	= "Content";
		$data['js'] 	= array(_ASSET_URL."js/jquery.friendurl.min.js");
		$data['html'] 	= $html;
		$data['output'] = $output;
		$data['page'] 	= "layout_crud";
		$data['module'] = "content";
		$this->load->view($this->layout,$data);
	}
}