<?php

class Visitor
{
	var $ci;
	var $db;
	var $timeout = 600;
	var $timestamp;
	
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->db = $this->ci->adodb;
		$this->timestamp = time();
		
		$this->Init();
		
		$this->collect_users();
		#$this->delete_users();

	}
	
	public function Init()
	{
		$table_exists = $this->db->GetOne("SHOW TABLES LIKE 'visitor'");

		if(!$table_exists){
			$sql = "CREATE TABLE IF NOT EXISTS `visitor` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `datetime` datetime DEFAULT '0000-00-00 00:00:00',
					  `times` int(11) DEFAULT NULL,
					  `ip_address` varchar(255) DEFAULT NULL,
					  `browser` text,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";	
			$this->ci->db->query($sql);
		}
		
	}
	
	public function ip_address()
	{
		return $this->ci->input->ip_address();	
	}
	
	public function browser()
	{
		return $this->ci->input->user_agent();
	}
	
	public function collect_users()
	{
		$range = $this->db->GetOne("SELECT COUNT(*) FROM visitor WHERE `times` >= '".($this->timestamp-$this->timeout)."' 
									AND ip_address = '{$this->ip_address()}'");
		if($range <= 0)
		{
			$query = "INSERT INTO `visitor` SET 
						`ip_address` = '{$this->ip_address()}',
						`datetime` = NOW(),
						`times` = '{$this->timestamp}',
						`browser` = '{$this->browser()}'";
			return $this->db->Execute($query);
		}
	}
	
	public function delete_users()
	{
		$query = "DELETE FROM `visitor` WHERE `times` < ($this->timestamp - $this->timeout)";
		return $this->db->Execute($query);	
	}
	
	public function count_users()
	{
		$query = "SELECT DISTINCT `ip_address` FROM `visitor`";
		$result = $this->db->Execute($query);
		return $result->RecordCount();
	}
	
}