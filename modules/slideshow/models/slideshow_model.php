<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slideshow_model extends CI_Model
{
	public $ci;
	public $image = array();
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_slideshow()
	{
		$query = "SELECT * FROM slideshow WHERE active = 1";
		$result = $this->db->query($query);
		
		if( $result->num_rows() > 0 )
		{
			$r = array();
			foreach((array)$result->result_array() as $row)
			{
				$slide['id'] 		= $row['id'];
				$slide['title'] 	= $row['title'];
				$slide['url'] 		= $row['url'];
				$slide['height'] 	= (int)$row['height'];
				$slide['width'] 	= (int)$row['width'];
				$slide['image_url']	= $this->returnImage($row['image'],"files/slideshow/");
				$slide['image']		= $row['image'];
				$r[$row['id']] = $slide;
			}
			$this->image = $r;
			return $r;
		}
	}
	
	public function get_paging()
	{
		$count = count($this->image);
		if( $count > 0 )
		{
			$num = array();
			for($i=1;$i<=$count;$i++)
			{
				$num[$i] = $i;
			}
			
			return $num;
		}
	}
	
	protected function returnImage($image, $path)
	{
		if( !empty($image) )
		{
			if( file_exists(_ROOT.$path.$image) )
			{
				return _URL . $path . $image;
			}
			else
			{
				return false;	
			}
		}
	}
}