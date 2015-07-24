<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	public function get_setting_contact()
	{
		$query = "SELECT * FROM contact_settings WHERE id = 1";
		$content = $this->dbQueryCache( $query, 'row' );
		return $content;
	}
	
	public function save_contact( $arr )
	{
		if( $arr )
		{
			if( $this->db->insert("contact",$arr) ){
				return true;	
			}
			return false;
		}
	}
}