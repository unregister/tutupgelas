<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagination
{
	protected $ci;
	protected $db;
	protected $model;

	var $layout;
	var $int_tot_rows = 0;
	var $int_tot_page = 0;
	var $int_cur_page = 1;
	var $curl_sql_pos = 0;
	var $num_nav;
	var $string_cur_uri;
	var $string_name;
	var $is_loaded = array();
	var $arr_result = array();

	public function __construct( $query = null, $limit = 10, $int_num_nav = 3, $string_name = 'page' ){
		$ci =& get_instance();
		$this->db = $ci->db;
		$this->limit = (int)$limit;
		$this->query = trim($query);
		$this->string_name = $string_name;
		$this->num_nav = (int)$int_num_nav;

		$this->layout['prev_word']	= "Prev";
		$this->layout['first_word']	= "First";
		$this->layout['next_word']	= "Next";
		$this->layout['last_word']	= "Last";
		$this->layout['result']		= "Data ke"; 
		$this->layout['to']			= "s/d";
		$this->layout['of']			= "dari total";
		$this->is_loaded['get_data'] = false;

		$ci->load->model('application_model');
		$this->model = $ci->application_model;
	}


	public function per_page( $per_page = 10 ){
		$this->limit = (int)$per_page;
		return $this;
	}


	public function get_num_all_rows(){
		if( $this->query ){
			$query = preg_replace('/SELECT(.*?)FROM/','SELECT COUNT(*) FROM ',$this->query);
			$count = $this->model->dbQueryCache( $query, 'one' );
			$this->int_tot_rows =  $count;
		}
	}

	public function num_rows(){
		if( $this->query ){
			$query = preg_replace('/SELECT(.*?)FROM/','SELECT COUNT(*) FROM ',$this->query);
			$count = $this->model->dbQueryCache( $query, 'one' );
			return $count;
		}else{
			return '0';
		}
	}

	public function get_num_page(){
		return $this->int_tot_page = ceil($this->int_tot_rows / $this->limit);
	}

	public function parse_url()
	{
		$this->string_cur_uri = preg_replace("#\?.*#", "", $_SERVER['REQUEST_URI'])."?";
		if (isset($_GET)) {
			foreach($_GET as $name=>$val){
				if ($name != $this->string_name){
					$this->string_cur_uri .= $name . "=" . $val ."&";
				}
			}
		}
	}

	public function get_data(){
		if( isset($_GET[$this->string_name]) && $_GET[$this->string_name] != '' ){
			$this->int_cur_page = (int)$_GET[$this->string_name];
		}else{
			$this->int_cur_page = 1;
		}
		$this->cur_sql_pos = ($this->int_cur_page-1) * $this->limit;

		$this->get_num_all_rows();
		$this->get_num_page();
		$this->parse_url();
		$this->is_loaded['get_data'] = true;
	}

	public function get_prev(){
		if( !$this->is_loaded['get_data'] ){
			$this->get_data();
		}

		if ($this->int_cur_page != 1) 
		{
			$int_prev_page = ($this->int_cur_page-1);
			$string_prev  = "<li><a href=". $this->string_cur_uri . $this->string_name ."=". $int_prev_page .">". $this->layout['prev_word'] ."</a></li>";
			$string_prev .= "<li><a href=". $this->string_cur_uri . $this->string_name ."=1>".$this->layout['first_word']."</a></li>";
			return $string_prev;
		}
	}

	public function get_next(){
		
		if( !$this->is_loaded['get_data'] ){
			$this->get_data();
		}

		if ( $this->int_cur_page != $this->int_tot_page && $this->int_tot_page != 0 ) 
		{
			$int_next_page = ($this->int_cur_page+1);
			$string_next  = "<li><a href=". $this->string_cur_uri . $this->string_name ."=".$this->int_tot_page.">".$this->layout['last_word']."</a></li>";
			$string_next .= "<li><a href=". $this->string_cur_uri . $this->string_name ."=". $int_next_page .">". $this->layout['next_word'] ."</a></li>";
			return $string_next;
		}
	}

	public function get_arr_nav()
	{
		if( !$this->is_loaded['get_data'] ){
			$this->get_data();
		}

		$arr_nav = array();
		if ($this->int_tot_page < $this->num_nav){
			$max = $this->int_tot_page;
		}else {
			$max = ($this->int_cur_page + $this->num_nav) -1;
		}
			
		if ($max > $this->int_tot_page){
			$max = $this->int_tot_page;
		}

		if ($max > (2 * $this->num_nav)-1 ){
			$n = $max - 2 * $this->num_nav;
		}else{
			$n = 0;
		}

		$k = 0;

		for ($i=$n; $i < $max; $i++) 
		{
			$j = $i+1;
			$arr_nav[$i] = "";
			if ( $i<$this->num_nav ){
				$arr_nav[$i].="";
			}

			if ($this->int_cur_page==$j){
				$arr_nav[$i] .= "<li class=\"active\">$j</li>";
			}else {
				$arr_nav[$i] .= "<li><a href=". $this->string_cur_uri . $this->string_name ."=". $j .">". $j ."</a></li>";
			}

			$k++;
		}
		return $arr_nav;
	}

	public function get_nav_status()
	{
		if( !$this->is_loaded['get_data'] ){
			$this->get_data();
		}

		$arr_status['begin'] = ($this->cur_sql_pos + 1);
		$num_view_rows = $this->cur_sql_pos+$this->int_max_rows;

		$arr_status['end'] = ($num_view_rows < $this->int_tot_rows) ? $num_view_rows : $this->int_tot_rows;
		$arr_status['total'] =  $this->int_tot_rows;
		return $arr_status;
	}
	
	public function get_nav( $ul_class = '' )
	{
		if( !$this->is_loaded['get_data'] ){
			$this->get_data();
		}

		$print_nav = "";
		if ($this->int_tot_page > 1) 
		{		
			$prev = $this->get_prev();
			$next = $this->get_next();

			$arr_nav = $this->get_arr_nav();
			foreach ($arr_nav as $key=>$val){
				$print_nav .= $val ." \n";			
			}

			$class = ( $ul_class ) ? ' class="'.$ul_class.'"' : '';
			$print_nav = "<ul$class>".$prev ."  ". $print_nav ."  ". $next."</ul>";
			return $print_nav;
		}
	}

	public function get_array_result()
	{
		if( !$this->is_loaded['get_data'] ){
			$this->get_data();
		}

		$sql 	= $this->query ." LIMIT ". $this->cur_sql_pos .", ". $this->limit;
		$result	= $this->model->dbQueryCache( $sql );		
		$this->arr_result = $result;
	}

	public function fetch()
	{
		$this->get_array_result();
		return $this->arr_result;
	}

}