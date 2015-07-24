<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends Admin_Controller
{
	var $table;
	var $primary;
	var $glyphicon;
	
	function __construct()
	{
		parent::__construct();	
		$this->table = 'sys_menu';
		$this->primary = 'id';
		$this->load->config('glyphicon');
		$this->glyphicon = $this->config->item('glyphicon');
	}
	
	function index()
	{
		$this->publics();
	}
	
	function admin()
	{
		check_user( uri_string() );
		$this->system->add_breadcrumb("Data menu admin");
		
		if( isset($_POST['roll_submit_delete']) )
		{
			foreach( (array)$_POST['roll_delete'] as $key=>$val )
			{				
				$sub = $this->adodb->GetAll("SELECT id FROM sys_menu WHERE parent_id = ".$_POST['roll_id'][$key]);
				foreach((array)$sub as $child){
					$this->adodb->Execute("DELETE FROM sys_menu_text WHERE sys_menu_id = ".$child['id']);	
				}
				$this->adodb->Execute("DELETE FROM sys_menu WHERE parent_id = ".$_POST['roll_id'][$key]);	
			}
		}
		
		$formRoll = new phpEasyAdmin($this->table);
		$formRoll->initRoll("WHERE is_admin = 1 AND is_cpanel = 0 AND parent_id = 0 ORDER BY order_by ASC",$this->primary);
		$formRoll->roll->setLanguage();
		
		$formRoll->roll->addInput("header","header");
		$formRoll->roll->input->header->setTitle("Data menu admin");
		
		$formRoll->roll->addInput("title","text");
		$formRoll->roll->input->title->setTitle("Title");
		$formRoll->roll->input->title->setPlaintext(true);
		$formRoll->roll->input->title->setLanguage();
		
		$formRoll->roll->addInput("url","text");
		$formRoll->roll->input->url->setTitle("URL");
		$formRoll->roll->input->url->setPlaintext(true);
		
		$formRoll->roll->addInput("order_by","orderby");
		$formRoll->roll->input->order_by->setTitle("Order By");
		
		$formRoll->roll->addInput("active","checkbox");
		$formRoll->roll->input->active->setTitle("Active");
		$formRoll->roll->input->active->setCaption('Active');
		
		$formRoll->roll->addInput("id","editlinks");
		$formRoll->roll->input->id->setTitle("Edit");
		$formRoll->roll->input->id->setCaption("<i class=\"glyphicon glyphicon-edit\"></i>Edit");
		$formRoll->roll->input->id->setLinks( site_url('_cpanel/admin/menu/edit_admin') );
		
		$formRoll->roll->action();
		$roll = $formRoll->roll->getForm();
		
		
		$maxorderadmin = $this->adodb->GetOne("SELECT MAX(order_by) FROM {$this->table} WHERE is_admin = 1");
		if( (int)$maxorderadmin > 0 ){
			$orderByAdmin = ($maxorderadmin+1);	
		}else{
			$orderByAdmin = '0';	
		}
		
		
		$formAdd = new phpEasyAdmin($this->table);				
		$formAdd->initEdit();
		$formAdd->edit->setLanguage();
		
		$formAdd->edit->addInput("header","header");
		$formAdd->edit->input->header->setTitle("Tambah menu admin");
		
		$formAdd->edit->addInput("title","text");
		$formAdd->edit->input->title->setTitle("Title");
		$formAdd->edit->input->title->setTabName("admin");
		$formAdd->edit->input->title->setLanguage();
		
		$formAdd->edit->addInput("url","text");
		$formAdd->edit->input->url->setTitle("URL");
		
		$formAdd->edit->addInput("parent_id","select");
		$formAdd->edit->input->parent_id->setTitle("Parent");
		$formAdd->edit->input->parent_id->addOption("-No Parent-","0");
		$sql = "SELECT title,id FROM sys_menu m LEFT JOIN sys_menu_text l ON (m.id=l.sys_menu_id) 
				WHERE is_admin = 1 AND is_cpanel = 0  AND parent_id = 0 AND lang_id = 1 
				ORDER BY parent_id, order_by, title, id ASC";
		$result = $this->adodb->GetAll($sql);
		foreach((array)$result as $row){
			$formAdd->edit->input->parent_id->addOption($row['title'],$row['id']);
		}
		
		$formAdd->edit->addInput("in_home","checkbox");
		$formAdd->edit->input->in_home->setTitle("Show in home");
		$formAdd->edit->input->in_home->setCaption('Yes');
		
		$formAdd->edit->addInput("active","checkbox");
		$formAdd->edit->input->active->setTitle("Active");
		$formAdd->edit->input->active->setCaption('Active');
		
		$formAdd->edit->addInput("icon","select");
		$formAdd->edit->input->icon->setTitle("Icon");
		$formAdd->edit->input->icon->setExtra('id="pea-glyphicon"');
		$formAdd->edit->input->icon->addOption("- No icon -","none");
		foreach((array)$this->glyphicon as $icon)
		{
			$formAdd->edit->input->icon->addOption("&nbsp;".$icon ,$icon,'class="glyphicon '.$icon.'"');
		}
		
		$formAdd->edit->addInput("order_by","hidden");
		$formAdd->edit->input->order_by->setDefaultValue($orderByAdmin);
		
		$formAdd->edit->addInput("is_admin","hidden");
		$formAdd->edit->input->is_admin->setDefaultValue('1');
		
		$formAdd->edit->action();
		$add = $formAdd->edit->getForm();
		
		$tabs['Data menu admin'] 	= $roll;
		$tabs['Tambah menu admin'] 	= $add;
		
		$this->tabs->add_tabs($tabs);
		$form = $this->tabs->display();
		
		$data['form'] = $form;
		$data['page'] = $this->general_form;
		$this->load->view($this->layout_content,$data);
		
	}
	
	function publics()
	{
		check_user( uri_string() );
		$this->system->add_breadcrumb("Data menu public");

		$formRoll = new phpEasyAdmin($this->table);
		$formRoll->initRoll("WHERE is_admin = 0 AND parent_id = 0 AND is_cpanel = 0 ORDER BY parent_id, order_by, id ASC",$this->primary);
		$formRoll->roll->setLanguage();
		
		$formRoll->roll->addInput("header","header");
		$formRoll->roll->input->header->setTitle("Data menu public");
		
		$formRoll->roll->addInput("title","text");
		$formRoll->roll->input->title->setTitle("Title");
		$formRoll->roll->input->title->setPlaintext(true);
		$formRoll->roll->input->title->setLanguage();
		
		$formRoll->roll->addInput("url","text");
		$formRoll->roll->input->url->setTitle("URL");
		$formRoll->roll->input->url->setPlaintext(true);
		
		$formRoll->roll->addInput("seo","text");
		$formRoll->roll->input->seo->setTitle("SEO");
		$formRoll->roll->input->seo->setPlaintext(true);
		
		$formRoll->roll->addInput("order_by","orderby");
		$formRoll->roll->input->order_by->setTitle("Order By");
		
		$formRoll->roll->addInput("active","checkbox");
		$formRoll->roll->input->active->setTitle("Active");
		$formRoll->roll->input->active->setCaption('Active');
		
		$formRoll->roll->addInput("id","editlinks");
		$formRoll->roll->input->id->setTitle("Edit");
		$formRoll->roll->input->id->setCaption("<i class=\"glyphicon glyphicon-edit\"></i>Edit");
		$formRoll->roll->input->id->setLinks( site_url('_cpanel/admin/menu/edit_public') );
		
		$formRoll->roll->action();
	
		$maxorderpublic = $this->adodb->GetOne("SELECT MAX(order_by) FROM {$this->table} WHERE is_admin = 0");
		if( (int)$maxorderpublic > 0 ){
			$orderByPublic = ($maxorderpublic+1);	
		}else{
			$orderByPublic = '0';	
		}
		
		
		$formAdd = new phpEasyAdmin($this->table);				
		$formAdd->initEdit();
		$formAdd->edit->setLanguage();
		
		$formAdd->edit->addInput("header","header");
		$formAdd->edit->input->header->setTitle("Tambah menu");
		
		$formAdd->edit->addInput("title","text");
		$formAdd->edit->input->title->setTitle("Title");
		$formAdd->edit->input->title->setTabName("public");
		$formAdd->edit->input->title->setLanguage();
		
		$formAdd->edit->addInput("url","text");
		$formAdd->edit->input->url->setTitle("URL");
		
		$formAdd->edit->addInput("seo","text");
		$formAdd->edit->input->seo->setTitle("SEO");
		
		$formAdd->edit->addInput("position","select");
		$formAdd->edit->input->position->setTitle("Posisi");
		$formAdd->edit->input->position->addOption("Top","top");
		$formAdd->edit->input->position->addOption("Top middle","middle-top");
		$formAdd->edit->input->position->addOption("Left","left");
		$formAdd->edit->input->position->addOption("Right","right");
		$formAdd->edit->input->position->addOption("Bottom middle","middle-bottom");
		$formAdd->edit->input->position->addOption("Bottom","bottom");
		
		$formAdd->edit->addInput("is_content","checkbox");
		$formAdd->edit->input->is_content->setTitle("Is Content");
		$formAdd->edit->input->is_content->setCaption('Is content?');		
		
		$formAdd->edit->addInput("active","checkbox");
		$formAdd->edit->input->active->setTitle("Active");
		$formAdd->edit->input->active->setCaption('Active');
		
		$formAdd->edit->addInput("order_by","hidden");
		$formAdd->edit->input->order_by->setDefaultValue($orderByPublic);
		
		$formAdd->edit->action();
		
		$roll = $formRoll->roll->getForm();
		$add  = $formAdd->edit->getForm();
		
		$tabs['Data menu public'] 	= $roll;
		$tabs['Tambah menu public'] 	= $add;
		
		$this->tabs->add_tabs($tabs);
		$form = $this->tabs->display();
		
		$data['form'] = $form;
		$data['page'] = $this->general_form;
		$this->load->view($this->layout_content,$data);
		
	}
	
	function edit_admin()
	{
		$this->system->add_breadcrumb("Data menu admin","_cpanel/admin/menu/admin");
		$this->system->add_breadcrumb("Edit menu admin");
		
		$id = (int)$_GET['id'];
		
		
		# EDIT FORM
		$formEdit = new phpEasyAdmin($this->table);				
		$formEdit->initEdit("WHERE id = $id");
		$formEdit->edit->setLanguage();
		
		$formEdit->edit->addInput("header","header");
		$formEdit->edit->input->header->setTitle("Edit menu admin");
		
		$formEdit->edit->addInput("title","text");
		$formEdit->edit->input->title->setTitle("Title");
		$formEdit->edit->input->title->setTabName("admin");
		$formEdit->edit->input->title->setLanguage();
		
		$formEdit->edit->addInput("url","text");
		$formEdit->edit->input->url->setTitle("URL");
		
		$formEdit->edit->addInput("parent_id","select");
		$formEdit->edit->input->parent_id->setTitle("Parent");
		$formEdit->edit->input->parent_id->addOption("-No Parent-","0");
		$sql = "SELECT title,id FROM sys_menu m LEFT JOIN sys_menu_text l ON (m.id=l.sys_menu_id) 
				WHERE is_admin = 1 AND is_cpanel = 0 AND parent_id = 0 AND lang_id = 1 
				ORDER BY parent_id, order_by, title, id ASC";
		$result = $this->adodb->GetAll($sql);
		foreach((array)$result as $row){
			$formEdit->edit->input->parent_id->addOption($row['title'],$row['id']);
		}
		
		$formEdit->edit->addInput("in_home","checkbox");
		$formEdit->edit->input->in_home->setTitle("Show in home");
		$formEdit->edit->input->in_home->setCaption('Yes');
		
		$formEdit->edit->addInput("active","checkbox");
		$formEdit->edit->input->active->setTitle("Active");
		$formEdit->edit->input->active->setCaption('Active');
		
		$formEdit->edit->addInput("icon","select");
		$formEdit->edit->input->icon->setTitle("Icon");
		$formEdit->edit->input->icon->setExtra('id="pea-glyphicon"');
		$formEdit->edit->input->icon->addOption("- No icon -","none");
		foreach((array)$this->glyphicon as $icon)
		{
			$formEdit->edit->input->icon->addOption("&nbsp;".$icon ,$icon,'class="glyphicon '.$icon.'"');
		}
		
		
		
		$formEdit->edit->action();
		$edit = $formEdit->edit->getForm();
		
		# DATA MENU		
		$formRoll = new phpEasyAdmin($this->table);
		$formRoll->initRoll("WHERE is_admin = 1 AND parent_id = $id AND is_cpanel = 0 ORDER BY order_by ASC",$this->primary);
		$formRoll->roll->setLanguage();
		
		$formRoll->roll->addInput("header","header");
		$formRoll->roll->input->header->setTitle("Data menu admin");
		
		$formRoll->roll->addInput("title","text");
		$formRoll->roll->input->title->setTitle("Title");
		$formRoll->roll->input->title->setPlaintext(true);
		$formRoll->roll->input->title->setLanguage();
		
		$formRoll->roll->addInput("url","text");
		$formRoll->roll->input->url->setTitle("URL");
		$formRoll->roll->input->url->setPlaintext(true);
		
		$formRoll->roll->addInput("order_by","orderby");
		$formRoll->roll->input->order_by->setTitle("Order By");
		
		$formRoll->roll->addInput("active","checkbox");
		$formRoll->roll->input->active->setTitle("Active");
		$formRoll->roll->input->active->setCaption('Active');
		
		$formRoll->roll->addInput("id","editlinks");
		$formRoll->roll->input->id->setTitle("Edit");
		$formRoll->roll->input->id->setCaption("<i class=\"glyphicon glyphicon-edit\"></i>Edit");
		$formRoll->roll->input->id->setLinks( site_url('_cpanel/admin/menu/edit_admin') );
		
		$formRoll->roll->action();
		$roll = $formRoll->roll->getForm();
		
		# ADD FORM MENU
		
		$maxorderadmin = $this->adodb->GetOne("SELECT MAX(order_by) FROM {$this->table} WHERE is_admin = 1 AND parent_id = $id");
		if( (int)$maxorderadmin > 0 ){
			$orderByAdmin = ($maxorderadmin+1);	
		}else{
			$orderByAdmin = '0';	
		}
		
		$formAdd = new phpEasyAdmin($this->table);				
		$formAdd->initEdit();
		$formAdd->edit->setLanguage();
		
		$formAdd->edit->addInput("header","header");
		$formAdd->edit->input->header->setTitle("Edit menu admin");
		
		$formAdd->edit->addInput("title","text");
		$formAdd->edit->input->title->setTitle("Title");
		$formAdd->edit->input->title->setTabName("admin_edit");
		$formAdd->edit->input->title->setLanguage();
		
		$formAdd->edit->addInput("url","text");
		$formAdd->edit->input->url->setTitle("URL");
		
		$formAdd->edit->addInput("parent_id","hidden");
		$formAdd->edit->input->parent_id->setDefaultValue($id);
		
		$formAdd->edit->addInput("in_home","checkbox");
		$formAdd->edit->input->in_home->setTitle("Show in home");
		$formAdd->edit->input->in_home->setCaption('Yes');
		
		$formAdd->edit->addInput("active","checkbox");
		$formAdd->edit->input->active->setTitle("Active");
		$formAdd->edit->input->active->setCaption('Active');
		
		$formAdd->edit->addInput("icon","select");
		$formAdd->edit->input->icon->setTitle("Icon");
		$formAdd->edit->input->icon->setExtra('id="pea-glyphicon"');
		$formAdd->edit->input->icon->addOption("- No icon -","none");
		foreach((array)$this->glyphicon as $icon)
		{
			$formAdd->edit->input->icon->addOption("&nbsp;".$icon ,$icon,'class="glyphicon '.$icon.'"');
		}
		
		$formAdd->edit->addInput("is_admin","hidden");
		$formAdd->edit->input->is_admin->setDefaultValue('1');
		
		$formAdd->edit->addInput("order_by","hidden");
		$formAdd->edit->input->order_by->setDefaultValue($orderByAdmin);
		
		$formAdd->edit->action();
		$add = $formAdd->edit->getForm();
		
		$tabs['Edit menu'] = $edit;
		$tabs['Data submenu'] = $roll;
		$tabs['Tambah menu'] = $add;
		
		$this->tabs->add_tabs($tabs);
		$form = $this->tabs->display();
		
		$data['form'] = $form;
		$data['page'] = $this->general_form;
		$this->load->view($this->layout_content,$data);
	}
	
}