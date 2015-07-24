<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	public function index()
	{
		$this->THEMEVARS['page'] = 'templates/news.html';				
		$this->twig->display($this->layout, $this->THEMEVARS );
	}
	
	public function detail()
	{
		$param = $this->uri->segment(3);
		
		if( !empty($param) )
		{
			$content = $this->content_model->get_content($param);
			$recent_property = $this->property_model->get_recent_property();
			
			$page = (!$content)?'layout_404':'layout_content';
			$meta = $this->content_model->set_meta();
			$this->meta->set_meta($meta);
			
			$data['recent_property'] = $recent_property;
			$data['content'] = $content;
			$data['page'] = $page;
			$this->load->view($this->layout,$data);
		}
		else
		{
			$data['page'] = 'layout_404';
			$this->load->view($this->layout,$data);
		}
	}
}