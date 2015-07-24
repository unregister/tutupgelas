<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Admin_Controller
{
	var $user_table;
	var $user_primary;
	var $role_table;
	var $role_primary;
	var $role_field_name;
	
	function __construct(){
		parent::__construct();	
		$this->user_table = 'sys_users';
		$this->user_primary = 'id';
		$this->role_table = 'sys_roles';
		$this->role_primary = 'id';
		$this->role_field_name = 'name';
		$this->load->model('dx_auth/permissions', 'permissions'); 
		check_user('user.manager');
	}
	
	function index(){
		$this->data();	
	}
	
	function data()
	{
		$this->crud->set_table( $this->user_table );


		$this->crud->set_subject('Data user');
		$this->crud->required_fields('title','url');
		$this->crud->set_relation('role_id','sys_roles','name');
		$this->crud->unique_fields('username');
		
		$this->crud->set_rules('email','Email','valid_email');
		$this->crud->set_rules('password','Password','min_length[5]|max_length[12]');
		
		$this->crud->display_as('role_id','Group ID');
		$this->crud->required_fields('role_id','username','password','name','email','gender');
		
		$this->crud->field_type('banned','true_false',array('1' => 'Yes', '0' => 'No'));
		$this->crud->field_type('is_admin', 'hidden', 1);
		
		$this->crud->unset_add_fields('id','modified','last_login','last_ip','newpass','newpass_time','newpass_key');
		$this->crud->unset_edit_fields('id','password','username','modified','created','last_login','last_ip','newpass','newpass_time','newpass_key');
		
		$this->crud->field_type('gender','dropdown',array('1' => 'Laki-laki', '2' => 'Perempuan'));
		$this->crud->columns('username','role_id','name','email','created','banned','last_login');
		$this->crud->change_field_type('password', 'password');
		$this->crud->change_field_type('created', 'hidden',date('Y-m-d H:i:s'));
		$this->crud->set_field_upload('image','files/users/');
		$this->crud->callback_insert(array($this,'callback_encrypt_password'));
		
		$this->crud->unset_read();
		$output = $this->crud->render();
		
		$data['output'] = $output;
		$data['page'] = "layout_crud";
		$data['title'] = "User manager";
		$data['module'] = "cpanel";

		$this->load->view($this->layout,$data);			
	}
	
	
	function callback_encrypt_password($post_array)
	{	  
		$encrypt = crypt($this->dx_auth->_encode( $post_array['password']) );
		$post_array['password'] = $encrypt;	
		return $this->db->insert($this->user_table,$post_array);	
	} 
	
	
	function permission()
	{
		$this->system->add_breadcrumb('Data group','_cpanel/admin/user/groups');
		$this->system->add_breadcrumb('Edit permission');
		
		$groupId = (int)$_GET['id'];
		
		
		$allresources = $this->adodb->GetAll("SELECT id,name FROM sys_resources");
		$r_resources = array();
		
		foreach((array)$allresources as $sources){
			$r_resources[$sources['id']] = $sources['name'];	
		}
		
		$arrRule = array();
		if( isset($_POST['change_permission']) )
		{
			$post_allow = $_POST['allow'];
			foreach((array)$post_allow as $id=>$allow)
			{
				$is_allow = ( $allow == 1 )?TRUE:FALSE;
				$permission_name = $r_resources[$id];
				$this->permissions->set_permission_value($groupId, $permission_name, $is_allow);
			}			
			
			$data['msg'] = "<div class=\"message success-msg\">Permission berhasil disimpan</div>";

		}
				
		$permission_data = $this->permissions->get_permission_data($groupId); 
		$this->load->config('cpanel');
		
		$data['resources'] = array_resources(); 
		$data['permission'] = $permission_data;
		$data['group_id'] = $groupId;
		$data['module'] = '_cpanel';
		$data['page'] = 'layout_permission';
		$this->load->view($this->layout_content,$data);
		
	}
	
	
	
	function change_password()
	{
		//$this->system->add_breadcrumb('Ubah password');
		$id = (int)$this->user->user_id;
		
		$report = "";
		if( isset($_POST['change_password']) )
		{
			$old_password = $this->input->post('password_old',true);
			$new_password = $this->input->post('password_new',true);
			$ret_password = $this->input->post('password_con',true);	
			
			$this->form_validation->set_rules('password_old', 'Password lama', 'required');
			$this->form_validation->set_rules('password_new', 'Password baru', 'required|matches[password_con]|min_length[6]');
			$this->form_validation->set_rules('password_con', 'Konfirmasi password', 'required|min_length[6]');
			
			
			if( $this->form_validation->run() == FALSE )
			{
				$error = validation_errors();
				$report = error($error);	
			}
			else
			{
				$status = $this->dx_auth->change_password($old_password,$new_password);
				if( $status == TRUE )
				{
					$report = success("Password berhasil diubah");
				}
				else
				{
					$message = $this->dx_auth->get_auth_error();
					$report = error($message);
				}
			}
			
		}
		
		$data['report'] = $report;
		$data['form'] = $id;
		$data['title'] = "Ubah password";
		$data['module'] = "cpanel";
		$data['page'] = 'layout_change_password';
		$this->load->view($this->layout,$data);	
	}
}