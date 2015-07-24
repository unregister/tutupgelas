<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}

	public function dbQueryCache( $query, $type = 'all', $age = 10 )
	{
		if ( !$this->cache->apc->is_supported() ){
		    $a = $this->GetDataFromDB( $query, $type, $age );
		}
		$cache_name = 'cc_' . md5($query) . '_' . $type;
		$data = $this->cache->get( $cache_name );

		if( !$data ){
			$data = $this->GetDataFromDB( $query, $type );
			$this->cache->save( $cache_name, $data, $age);
		}

		return $data;
	}

	public function GetDataFromDB( $sql, $type = 'all')
	{
		switch($type){
			case 'all':
				$data = $this->GetAll( $sql, true );
			break;
			case 'row':
				$data = $this->GetRow( $sql, true );
			break;
			case 'one':
				$data = $this->GetOne( $sql );
			break;
			default:
				$data = $this->GetAll( $sql, true );
			break;
		}
		return $data;
	}

	public function GetAll( $query, $to_array = false ){
		if( $query ){
			$execute = $this->db->query( $query );
			if( $execute->num_rows() > 0 )
			{
				if( $to_array ){
					$return = $execute->result_array();
				}
				return $execute->result();
			}else{
				return array();
			}			
		}
	}

	public function GetRow( $query, $to_array = false ){
		if( $query ){
			$execute = $this->db->query( $query );
			return $execute->row_array();
		}
	}

	public function GetOne( $query ){
		if( $query ){
			$execute = $this->db->query( $query );
			$result = $execute->row_array();
			foreach($result as $key=>$return){
				return $return;
			}
		}
	}

}