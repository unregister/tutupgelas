<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends Admin_Controller
{
	var $table;
	var $primary;
	var $glyphicon;
	var $controller_url;
	
	public function __construct()
	{
		parent::__construct();	
		$this->table = 'sys_menu';
		$this->primary = 'id';
		$this->load->config('glyphicon');
		$this->glyphicon = $this->config->item('glyphicon');
		$this->load->model('cpanel/menu_model');
		$this->load->library('tree');
		check_user('menu.manager');
	}
	
	public function index()
	{
		$position = array('top'				=>'top',
						  'top middle'		=>'top-middle',
						  'bottom'			=>'bottom',
						  'bottom middle'	=>'bottom-middle',
						  'left'			=>'left',
						  'right'			=>'right');
						  
		$current_position = 'top';
		if( isset($_GET['position']) && $_GET['position'] != '' && in_array($_GET['position'],$position) )
		{
			$current_position = strtolower($_GET['position']);	
		}
		
		$output = '<ul id="menumanager"></ul>';
		$menu = $this->menu_model->get_menu( $current_position );
		if( $menu )
		{
			foreach ((array)$menu as $row) {
				$this->tree->add_row(
					$row['id'],
					$row['parent_id'],
					' id="menu-'.$row['id'].'" class="sortable"',
					$this->menu_model->get_label($row)
				);
			}

			$output = $this->tree->generate_list('id="menumanager"');
		}
		
		if( isset($_GET['edit']) )
		{
			$menu_id = $this->uri->segment(4);
			$data['on_edit'] = true;
			$data['edit_menu']	 = $this->menu_model->get_edit_menu($menu_id);
		}
		else
		{
			$data['on_edit'] = false;	
		}
		
		$data['current_position'] = $current_position;
		$data['content'] = $this->menu_model->get_content();
		$data['menu'] = $output;
		$data['position'] = $position;
		$data['title'] = "Menu manager";
		$data['page'] = "layout_menu";
		$data['module'] = "cpanel";
		$this->load->view($this->layout,$data);	
	}
	
	public function add()
	{
		$this->load->library('form_validation');
		if( isset($_POST['save-menu']) )
		{
			$this->form_validation->set_rules('title','Judul menu','trim|required');
			$this->form_validation->set_rules('url','Url menu','trim|required');
			
			if( $this->form_validation->run() == false )
			{
				$error = error( validation_errors() );
				set_msg( $error );
				redirect( 'cpanel/menu/index?position='.$this->input->post('position') );	
				exit();
			}
			else
			{
				$r = array();
				$r['title'] 		= $this->input->post('title',true);
				$r['url'] 			= $this->input->post('url',true);
				$r['position'] 		= $this->input->post('position',true);
				$r['is_content'] 	= $this->input->post('is_content',true);
				$r['meta_keyword']	= $this->input->post('meta_keyword',true);
				$r['meta_description']	= $this->input->post('meta_description',true);
				$r['content_id'] 	= ( $r['is_content'] == 1) ? $this->input->post('content_id',true) : 0;
				$r['active'] 		= $this->input->post('active',true);
				
				$save = $this->menu_model->save_menu( $r );
				if( $save )
				{
					$success = success("Data berhasil disimpan");
					set_msg( $success );
					redirect( 'cpanel/menu/index?position='.$this->input->post('position') );	
					exit();
				}
			}
			
		}
		else
		{
			show_404();	
		}
	}
	
	public function edit()
	{
		$this->load->library('form_validation');
		if( isset($_POST['save-menu']) )
		{
			$menu_id = $this->input->post('id');
			$this->form_validation->set_rules('title','Judul menu','trim|required');
			$this->form_validation->set_rules('url','Url menu','trim|required');
			
			if( $this->form_validation->run() == false )
			{
				$error = error( validation_errors() );
				set_msg( $error );
				redirect( 'cpanel/menu/index/'.$menu_id.'?position='.$this->input->post('position').'&edit=1' );	
				exit();
			}
			else
			{
				$r = array();
				$r['title'] 		= $this->input->post('title',true);
				$r['url'] 			= $this->input->post('url',true);
				$r['position'] 		= $this->input->post('position',true);
				$r['is_content'] 	= $this->input->post('is_content',true);
				$r['meta_keyword']	= $this->input->post('meta_keyword',true);
				$r['meta_description']	= $this->input->post('meta_description',true);
				$r['content_id'] 	= ( $r['is_content'] == 1) ? $this->input->post('content_id',true) : 0;
				$r['active'] 		= $this->input->post('active',true);
				
				$save = $this->menu_model->update_menu( $r, $menu_id );
				if( $save )
				{
					$success = success("Data berhasil disimpan");
					set_msg( $success );
					redirect( 'cpanel/menu/index/'.$menu_id.'?position='.$this->input->post('position').'&edit=1' );	
					exit();
				}
			}
			
		}
		else
		{
			show_404();	
		}
	}
	
	public function delete()
	{
		if( $this->uri->segment(4) )
		{
			$id = (int)$this->uri->segment(4);
			$this->menu_model->get_descendants($id);
			$this->menu_model->ids[] = $id;
			$in_menu = implode(",",$this->menu_model->ids);
			
			$delete = $this->menu_model->delete_menu($in_menu);
			if( $delete )
			{
				$success = success("Data berhasil dihapus");
				set_msg( $success );
				redirect( 'cpanel/menu/index?position='.$_GET['position'] );	
				exit();	
			}
			else
			{
				$error = error("Data gagal dihapus");
				set_msg( $error );
				redirect( 'cpanel/menu/index?position='.$_GET['position'] );	
				exit();	
			}
			
		}
		else
		{
			show_404();	
		}
	}
	
	public function update()
	{
		if( isset($_POST['menu']) && !empty($_POST['menu']) )
		{
			$menu = $this->input->post('menu',true);
			foreach ($menu as $k => $v) {
				if ($v == 'null') {
					$menu2[0][] = $k;
				} else {
					$menu2[$v][] = $k;
				}
			}
			
			$success = 0;
			if (!empty($menu2)) {
				foreach ($menu2 as $k => $v) {
					$i = 1;
					foreach ($v as $v2) {
						$data['parent_id'] = $k;
						$data['orderby'] = $i;
						
						$this->db->where("id",$v2);
						if ($this->db->update('sys_menu', $data)) {
							$success++;
						}
						$i++;
					}
				}
			}
			echo $success;
			die;
			
		}
	}
	
	public function get_content()
	{
		if( $this->input->post('action') && $this->input->post('action') == 'get_content' )
		{
			if( $this->input->post('content_id') )
			{
				$data = $this->menu_model->get_single_content( (int)$this->input->post('content_id') );
				if( !empty($data['seo']) )
				{
					$url = "content/detail/".$data['seo'];	
				}
				else
				{
					$title = url_title($data['title'],"-",true);
					$content_id = $data['id'];
					$url = "content/detail/$content_id/$title";	
				}
				echo $url;
			}
		}
	}
	
}