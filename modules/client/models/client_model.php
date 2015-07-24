<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_model extends MY_Model 
{
	public function __construct(){
		parent::__construct();
	}

	public function get_client()
	{
		$query = "SELECT * FROM client WHERE client_active = 1 ORDER BY client_id DESC";
		$result = $this->dbQueryCache( $query,'all' );
		return $result;
	}
}