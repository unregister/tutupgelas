<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	public function detail()
	{
		
		$param = $this->uri->segment(3);
		if( !empty($param) )
		{
			$content = $this->content_model->get_content($param);
			$page = (!$content)?'templates/layout_404.html':'templates/content.html';
			
			
			$this->THEMEVARS['breadcrumb'][$content['title']] = '';
			
			$meta = $this->content_model->set_meta();	
			
			$this->THEMEVARS['page_title'] = $content['title'];		
			$this->THEMEVARS['meta'] = $this->meta->set_meta($meta);
			$this->THEMEVARS['content'] = $content;
			$this->THEMEVARS['page'] = $page;
			
			$this->twig->display($this->layout, $this->THEMEVARS );
		}
	}
	
}