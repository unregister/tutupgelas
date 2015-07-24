<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		check_user('post');	
	}
	
	public function index()
	{	
		
		$html  = "<script type=\"text/javascript\">\n";
		$html .= "$(document).ready(function(){	$('#field-title').friendurl({id : 'field-seo', divider: '-'}); });";
		$html .= "</script>";
		
		$allow_add = check_user('post.add',FALSE);
		$allow_edit = check_user('post.edit',FALSE);
		$allow_delete = check_user('post.delete',FALSE);

		$this->crud->set_table('content');
		$this->crud->where('type_id',2);		
		$this->crud->set_subject('Data post');
		$this->crud->required_fields('title','content');
		$this->crud->field_type('active','true_false',array('1' => 'Yes', '0' => 'No'));
		
		$this->crud->field_type('created','hidden',date('Y-m-d H:i:s'));
		
		$this->crud->unset_add_fields('meta_title','hits');
		$this->crud->unset_edit_fields('meta_title','hits','created_by','created','type_id');
		
		$this->crud->columns('title','category_id','seo','created_by','created');
		$this->crud->field_type('type_id','hidden',2);
		$this->crud->set_field_upload('image','files/post');
		
		$state = $this->crud->getState();
		if( $state == 'ajax_list' )
		{
			$this->crud->set_relation('created_by','sys_users','name');
		}
		else
		{
			$this->crud->field_type('created_by','hidden',$this->user->user_id);
		}
		
		$this->crud->set_relation('category_id','content_category','name');
		$this->crud->display_as('category_id','Kategori');
		
		if(!$allow_add){
			$this->crud->unset_add();
		}
		if(!$allow_edit){
			$this->crud->unset_edit();
		}
		if(!$allow_delete){
			$this->crud->unset_delete();
		}
			
		$this->crud->field_type('meta_description','text');
		$this->crud->unset_texteditor('meta_description');
		$output = $this->crud->render();
		
		$data['title'] 	= "Post";
		$data['js'] 	= array(_ASSET_URL."js/jquery.friendurl.min.js");
		$data['html'] 	= $html;
		$data['output'] = $output;
		$data['page'] 	= "layout_crud";
		$data['module'] = "post";
		$this->load->view($this->layout,$data);
	}
	
	public function category()
	{
		$this->crud->set_table('content_category');		
		$this->crud->set_subject('Data kategori');	
		$this->crud->required_fields('name');
		$this->crud->field_type('active','true_false',array('1' => 'Yes', '0' => 'No'));
		$this->crud->field_type('meta_description','text');
		$this->crud->unset_texteditor('meta_description');
		$this->crud->unset_read();
		
		$output = $this->crud->render();
		
		$data['title'] 	= "Post";
		$data['output'] = $output;
		$data['page'] 	= "layout_crud";
		$data['module'] = "post";
		$this->load->view($this->layout,$data);
	}
}