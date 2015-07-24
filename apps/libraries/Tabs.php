<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tabs
{
	var $ci;
	var $useCookie;
	var $tabId;
	var $arrTabs;
	var $setCookie;
	
	function Tabs()
	{
		$this->ci =& get_instance();
		$this->setTabId();
		$this->setCookie();
	}
	
	function setTabId($name='tabs'){
		$this->tabId = $name;	
	}
	
	function add_tabs($arr=array()){
		if( !empty($arr) and count($arr) > 1 )
		{
			$this->arrTabs = $arr;
		}
	}
	
	function setCookie($set=true){
		$this->setCookie = $set;	
	}
	
	function Init(){
		$out  = "";
		$i=1;
		if( !empty($this->arrTabs) )
		{
			$out .= "<div id=\"{$this->tabId}\">\n";
			$out .= "\t<ul>\n";
			foreach((array)$this->arrTabs as $title=>$content){
				$out .= "\t\t<li><a href=\"#{$this->tabId}-$i\">".strtoupper($title)."</a></li>\n";	
				$i++;
			}
			$out .= "\t</ul>\n";
			
			$j=1;
			foreach((array)$this->arrTabs as $title=>$content){
				$out .= "\t\t<div id=\"{$this->tabId}-$j\">$content</div>\n";	
				$j++;
			}
			
			$out .= "</div>";
		}
		return $out;
	}
	
	function display(){
		$out  = "";
		$css = _ASSET_URL."jqueryui/css/redmond/jquery-ui-1.10.3.custom.min.css";
		$js = _ASSET_URL."jqueryui/js/jquery-ui-1.10.3.custom.min.js";
		$out .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"$css\" />\n";
		$out .= "<script type=\"text/javascript\" src=\"$js\"></script>";
		$out .= "<script type=\"text/javascript\">\n";
		$out .= "$(document).ready( function(){\n";
		if( $this->setCookie )
		{
			$out .= "\t $(' #".$this->tabId."' ).tabs({
					active   : $.cookie('activetab'),
					activate : function( event, ui ){
						$.cookie( 'activetab', ui.newTab.index(),{
							expires : 10
						});
					}
				});\n";
		} else {
			$out .= "\t $('#".$this->tabId."').tabs();\n";
		}
		$out .= "});\n";
		$out .= "</script>\n";
		$out .= $this->Init();
		return $out;			
	}
}