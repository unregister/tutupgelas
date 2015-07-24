<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		check_user('module.manager');	
	}
	
	public function index()
	{
		$this->_scandir();
		$this->crud->set_table('sys_modules');
		$this->crud->set_subject('Data modules');
		$this->crud->required_fields('name');
		$this->crud->columns('name','install_date','active','is_installed');
		$this->crud->field_type('active','true_false',array('1' => 'Yes', '0' => 'No'));
		$this->crud->field_type('meta_description','text');
		$this->crud->unset_texteditor('meta_description');
		$this->crud->unset_read();
		$this->crud->unset_add();
		$this->crud->unset_add_fields('id','parent_id');
		$this->crud->unset_edit_fields('id','name','is_installed','install_date','parent_id');
		$this->crud->callback_before_delete(array($this,'_delete_permission'));
		$this->crud->display_as('is_installed','Install');
		$this->crud->callback_column('is_installed',array($this,'_link_install'));
		
		$output = $this->crud->render();
		
		$data['output'] = $output;
		$data['page'] 	= "layout_crud";
		$data['module'] = "cpanel";
		$data['title'] = "Module manager";

		$this->load->view($this->layout,$data);
	}
	
	public function install()
	{
		if( $this->uri->segment(4) )
		{
			$id = (int)$this->uri->segment(4);
			$data['is_installed'] = 1;
			$data['install_date'] = date('Y-m-d H:i:s');
			
			$this->db->where("id",$id);
			$install = $this->db->update("sys_modules",$data);
			if($install)
			{
				$row = $this->db->query("SELECT name FROM sys_modules WHERE id = $id") -> row();
				$modulename = $row->name;
				
				//Install Table
				if( file_exists(_ROOT."modules/$modulename/init/__db.php") )
				{
					include_once _ROOT."modules/$modulename/init/__db.php";
					if( isset($db_install['query']) && !empty($db_install['query']) ){
						foreach($db_install['query'] as $table_name=>$query){
							$this->db->query( $query );
						}
					}
				}

				if( file_exists(_ROOT."modules/$modulename/init/__resources.php") )
				{
					include_once _ROOT."modules/$modulename/init/__resources.php";
					if( isset($resources[$modulename]) )
					{
						#pr( $resources[$modulename] );
						$resources_name  = $resources[$modulename]['parent']['value'];
						$resources_title = $resources[$modulename]['parent']['title'];
						
						$resources_id = $this->_save_resources($resources_name,$resources_title,$modulename);
						if( $resources_id )
						{
							if( isset( $resources[$modulename]['child'] ) )	
							{
								if( isset( $resources[$modulename]['mode'] ) && $resources[$modulename]['mode'] == 'multi' )
								{
								
									foreach((array)$resources[$modulename]['child'] as $child)
									{
										if( isset($child['parent']) )
										{
											$child_resources_name = $child['parent']['value'];
											$child_resources_title = $child['parent']['title'];
											$child_id = $this->_save_resources($child_resources_name,$child_resources_title,$modulename,$resources_id);
											
											if($child_id)
											{
												if( isset($child['child']) )
												{
													foreach((array)$child['child'] as $subchild)
													{
														$sub_name = $subchild['value'];
														$sub_title = $subchild['title'];
														$this->_save_resources($sub_name,$sub_title,$modulename,$child_id);	
													}
												}
											}
											
										}
									}
								}else{			
									if( isset($resources[$modulename]['child']) )
									{
										foreach((array)$resources[$modulename]['child'] as $resources)
										{
											$this->_save_resources($resources['value'],$resources['title'],$modulename,$resources_id);	
										}
									}
								}
							}
							
						}
						
						
					}
				}
				
				header('location:'.site_url('cpanel/module/index'));
				exit();	
			}
		}
	}
	
	// Kedepannya kalo pas unistal nggak usah hapus permission, begitu juga pas instal ngecek diresources ada apa belum
	// kalo belum maka insert, kalo udah brati false
	public function uninstall()
	{
		if( $this->uri->segment(4) )
		{
			$id = (int)$this->uri->segment(4);
			$data['is_installed'] = 0;
			
			$this->db->where("id",$id);
			$uninstall = $this->db->update("sys_modules",$data);
			if($uninstall)
			{
				$row = $this->db->query("SELECT name FROM sys_modules WHERE id = $id") -> row();
				$modulename = $row->name;
				
				if( file_exists(_ROOT."modules/$modulename/init/__db.php") )
				{
					include_once _ROOT."modules/$modulename/init/__db.php";
					if( isset($db_install['query']) && !empty($db_install['query']) ){
						foreach($db_install['query'] as $table_name=>$query){
							$this->db->query( "DROP TABLE " .$table_name );
						}
					}
				}

				$this->db->where('module',$modulename);
				$this->db->delete('sys_resources');
				
				header('location:'.site_url('cpanel/module/index'));
				exit();	
			}
		}
	}
	

	
	public function _link_install($value, $row)
	{
		$is_install = $this->db->query("SELECT is_installed FROM sys_modules WHERE id = {$row->id}")->row_array();
		if($is_install['is_installed'] == 1){
			$mode = 'uninstall';
			$name = 'UNINSTALL';
			$label = 'label-edit';  
		}else{
			$mode = 'install';
			$name = 'INSTAll'; 
			$label = 'label-custom'; 
		}
		
		$html  = "<div style='text-align:center;'><a href='".site_url('cpanel/module/'.$mode.'/'.$row->id)."' >";
		$html .= "<span class=\"label-action $label callout\">".$this->icon->fa['retweet']." $name</span>";
		$html .= "</a></div>";
		return $html;
	}
	
	
	public function _delete_permission($id)
	{
		$modulename = $this->db->query("SELECT name FROM sys_modules WHERE id = {$id}")->row_array();
		if($modulename['name'] == 'cpanel'){
			return false;	
		}
		return true;
	}
	
	public function _scandir()
	{
		$scandir = scandir(_ROOT."modules");	
		$not_allowed = array('.','..','index.html','.htaccess');
		foreach((array)$scandir as $folder)
		{
			if( !in_array($folder,$not_allowed) )
			{
				$available = $this->db->query("SELECT * FROM sys_modules WHERE name = '$folder'");
				if($available->num_rows() <= 0){
					$mod['name'] = $folder;
					$mod['is_installed'] = 0;
					$mod['active'] = 1;	
					$this->db->insert("sys_modules",$mod);
				}
			}
		}
	}
	
	protected function _save_resources( $name, $title = '', $modulename = '', $parent_id = 1 )
	{
		if( !$this->_check_resources($name) )
		{
			$data['name'] = $name;
			$data['title'] = $title;
			$data['parent_id'] = $parent_id;
			$data['module'] = $modulename;
			$this->db->insert("sys_resources",$data);
			return $this->db->insert_id();	
		}
	}
	
	protected function _check_resources( $name )
	{
		$this->db->where('name',$name);
		$result = $this->db->get("sys_resources");
		if( $result->num_rows() > 0 ){
			return true;	
		}
		return false;
	}
}