<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Public_Controller
{
	function __construct(){
		parent::__construct();	
	}
	
	function index()
	{
	
		echo "Dashboard";
	}
	
	function main()
	{
		
		echo "main dashboard";
	}
	
}