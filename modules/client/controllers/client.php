<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CLient extends Public_Controller
{
	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		$client = (array)$this->client_model->get_client();

		if( count($client) > 0 ){

			$i=1;
			$arr_client = array();
			foreach((array)$client as $row){
				if( $i%4==0 ){
					$row->break = TRUE;
				}else{
					$row->break = FALSE;
				}
				$row->client_website = prep_url($row->client_website);
				$arr_client[] = (array)$row;
				$i++;
			}
		}

		$this->THEMEVARS['client'] = $arr_client;
		$this->THEMEVARS['page'] = 'templates/client.html';				
		$this->twig->display($this->layout, $this->THEMEVARS );
	}
}