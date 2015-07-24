<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testimonial_model extends MY_Model
{
	public function __construct(){
		parent::__construct();
		$this->load->library('Pagination');
	}

	public function get_testimonial()
	{
		$data = array();
		$query = "SELECT * FROM testimonial WHERE testimonial_active = 1 ORDER BY testimonial_id DESC";
		$nav = new Pagination( $query, 3 );

		if( $nav->num_rows() > 0 ){
			foreach( $nav->fetch() as $row ){
				$data[] = $row;
			}
		}

		$return['data'] = $data;
		$return['nav'] = $nav->get_nav();
		return $return;
	}

	public function get_settings()
	{
		$query = $this->dbQueryCache("SELECT * FROM testimonial_settings WHERE id = 1",'row');
		if( $query ){
			return $query;
		}
	}

	public function save_testimonial( $data )
	{
		if( !$data ){
			return FALSE;
		}

		$this->db->set('testimonial_created','NOW()',FALSE);
		foreach((array)$data as $field=>$value){
			$this->db->set($field,$value);
		}

		$insert = $this->db->insert('testimonial');
		if( $insert ){
			return TRUE;
		}
		return FALSE;
	}
}