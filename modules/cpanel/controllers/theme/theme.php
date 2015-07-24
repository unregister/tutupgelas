<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Theme extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		check_user('theme.manager');	
	}
	
	public function index()
	{
		$data['current_theme'] = $this->meta->current_theme();
		$data['theme']	= $this->_scan_theme();
		$data['page'] 	= "layout_theme";
		$data['module'] = "cpanel";
		$data['title']  = "Theme manager";

		$this->load->view($this->layout,$data);
	}
	
	protected function _scan_theme()
	{
		$not_allowed = array('.','..','admin','default');
		$scandir = scandir(_ROOT."themes");	
		
		$themes = array();
		if( $scandir )
		{
			foreach((array)$scandir as $folder)
			{
				if( !in_array($folder,$not_allowed) )
				{
					if( file_exists(_ROOT."themes/$folder/layout.html") ){
						$themes[] = $folder;
					}
				}
			}
			return $themes;
		}
	}
	
	public function change()
	{
		if( $this->uri->segment(4) )
		{
			$themename = $this->uri->segment(4);
			$this->db->where('name','theme');
			$this->db->set('value',$themename);
			$update = $this->db->update("sys_config");
			
			if( $update )
			{
				redirect( 'cpanel/theme' );
				exit();	
			}
		}
	}

	public function editor()
	{
		if( isset($_GET['theme']) && !empty($_GET['theme']) )
		{
			$themename = trim($_GET['theme']);
			if( !is_dir(_ROOT."themes/$themename") ){
				$msg = error("Theme yang Anda maksud tidak ditemukan");
				set_msg( $msg );
				redirect('cpanel/theme');
				exit();
			}



			$themepath 	= _ROOT."themes/$themename/";
			$dir = path_list_r($themepath);
			$filetree =  tree_files($dir);

			$mode = 'html';
			$content = 'layout.html';

			if( isset($_GET['file']) && !empty($_GET['file']) )
			{
				$filename = trim($_GET['file']);
				if( $filename != 'layout.html' )
				{
					list($folder,$file) = explode(':',$filename);	
				}else{
					$file = $filename;
				}
				
				$extension = strtolower( end( explode('.',$file) ) );

				switch($extension)
				{
					case 'js':
						$mode = 'javascript';
						break;
					case 'css':
						$mode = 'css';
						break;
					case 'html':
						$mode = 'html';
						break;
					default:
						$mode = 'html';
						break;
				}

				$content = str_replace(':','/',$filename);
			}
			else
			{
				$filename = 'layout.html';
			}

			$filecontent = file_read( $themepath.$content);
			$filecontent = htmlentities($filecontent);
			
			$data['current_file'] = $filename;
			$data['file'] = $filetree;
			$data['mode'] = $mode;
			$data['content'] = $filecontent;
			$data['current_theme'] =$themename;
			$data['page'] 	= "layout_theme_editor";
			$data['module'] = "cpanel";
			$data['title']  = "Theme editor";
			$this->load->view($this->layout,$data);

		}
	}

	public function save_file()
	{
		$response['status'] = 0;
		$response['msg'] = "File gagal disimpan";
		if( isset($_POST) )
		{
			$theme = $_POST['theme'];
			$file = str_replace(":","/",$_POST['file']);
			$content =  stripslashes($_POST['file_content']) ;
			
			$theme_dir = _ROOT . "themes/$theme/";
			$file_path = $theme_dir.$file;

			if( is_writable($file_path) )
			{
				$handle = fopen($file_path, 'w');
				if (fwrite($handle, $content) !== false) {
					$response['status'] = 1;
					$response['msg'] = "Berhasil simpan file";
				}
			}
			else
			{
				$response['status'] = 0;
				$response['msg'] = 'Gagal membuka file';
			}
		}

		header('Content-type: application/json');
		echo json_encode($response);
		die;

	}
}