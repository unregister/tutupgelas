<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends CI_Model
{
	var $ids;
	
	public function __construct()
	{
		parent::__construct();	
	}
	
	public function get_menu($position)
	{
		$query = "SELECT id,title,url,meta_keyword,meta_description,parent_id FROM sys_menu 
				  WHERE position = '$position' AND is_admin = 0 
				  ORDER BY orderby ASC";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	public function get_content()
	{
		$query = "SELECT id,title FROM content WHERE type_id = 1 AND active = 1 ORDER BY id DESC";
		return $this->db->query($query)->result_array();
	}
	
	public function get_single_content( $id )
	{
		$query = "SELECT id,title,seo FROM content WHERE id = $id AND type_id = 1 AND active = 1 ORDER BY id DESC";
		return $this->db->query($query)->row_array();
	}
	
	public function save_menu( $arr )
	{
		$arr['is_admin'] = 0;
		$arr['orderby'] = ( $this->get_last_orderby( $arr['position'] )+1);
		$save = $this->db->insert("sys_menu", $arr);
		return $save;
	}
	
	public function update_menu( $arr, $id )
	{
		$arr['is_admin'] = 0;
		$this->db->where("id",$id);
		$save = $this->db->update("sys_menu", $arr);
		return $save;
	}
	
	public function get_edit_menu($id)
	{
		$this->db->where("id",$id);
		$result = $this->db->get("sys_menu") -> row_array();
		return $result;	
	}
	
	public function get_last_orderby( $pos )
	{
		$this->db->where('position',$pos);
		$this->db->select_max('orderby', 'last_orderby');
		$query = $this->db->get('sys_menu') -> row();
		return $query->last_orderby;
	}
	
	public function get_descendants($id)
	{
		$query = $this->db->query("SELECT id FROM sys_menu WHERE parent_id = $id");
		if( $query->num_rows() > 0 )
		{
			foreach((array)$query->result_array() as $row)
			{
				$this->ids[] = $row['id'];
				$this->get_descendants( $row['id'] );
			}
		}

	}
	
	public function delete_menu( $id )
	{
		$query = "DELETE FROM sys_menu WHERE id IN($id)";
		return $this->db->query( $query );
	}
	
	
	public function get_label($row) 
	{
		$position = (isset($_GET['position']))?$_GET['position']:'top';
		$label =
			'<div class="ns-row">' .
				'<div class="ns-title">'.$row['title'].'</div>' .
				'<div class="ns-url">'.$row['url'].'</div>' .

				'<div class="ns-actions">' .
					'<a href="'.site_url('cpanel/menu/index/'.$row['id']).'?position='.$position.'&edit=1" class="edit-menu" title="Edit Menu">' .
						'<i class="fa fa-pencil"></i>' .
					'</a>' .
					'<a href="'.site_url('cpanel/menu/delete/'.$row['id']).'?position='.$position.'" class="delete-menu" onClick="return confirm(\'Menghapus menu ini akan sekaligus menghapus sub-menu dibawahnya. Apakah anda yakin?\');">' .
						'<i class="fa fa-times"></i>' .
					'</a>' .
					'<input type="hidden" name="menu_id" value="'.$row['id'].'">' .
				'</div>' .
			'</div>';
		return $label;
	}
}