<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller
{
	function __construct(){
		parent::__construct();	
	}
	
	function index()
	{
		$this->system->add_breadcrumb('Settings');
		
		#$ss = $this->adodb->GetAll("select id as value, `name` from sys_templates");
		#echo serialize($ss);
		
		$setting = $this->adodb->GetAll("SELECT DISTINCT(category), type, default_value FROM sys_config WHERE category <> 'property'");
		$i=0;
		foreach((array)$setting as $set)
		{
			$i++;
			
			$setting[$i] = new phpEasyAdmin("sys_config");
			
			$setting[$i]->initRoll("WHERE category = '".$set['category']."' AND editable = 1 ORDER BY id ASC");
			$setting[$i]->roll->setDeleteTool(FALSE);
			$setting[$i]->roll->setNumRows(20);
			
			$setting[$i]->roll->addInput("header","header");
			$setting[$i]->roll->input->header->setTitle("Settings $set[category]");
			
			$setting[$i]->roll->addInput("description","text");
			$setting[$i]->roll->input->description->setTitle("Nama");
			$setting[$i]->roll->input->description->setPlaintext(true);
			
			$setting[$i]->roll->addInput("value",$set['type']);
			$setting[$i]->roll->input->value->setTitle("Value");
			
			if( $set['type'] == 'textarea' ){
				$setting[$i]->roll->input->value->setSize(3,40);
			}elseif($set['type'] == 'select'){				
				$value = unserialize($set['default_value']);
				foreach((array)$value as $val){
					$setting[$i]->roll->input->value->addOption("$val[name]",$val['value']);
				}				
			}elseif($set['type'] == 'text'){
				$setting[$i]->roll->input->value->setSize(60);
			}else{
				$setting[$i]->roll->input->value->setSize(3,40);	
			}
			
			$setting[$i]->roll->action();
			$form[$i] = $setting[$i]->roll->getForm();
			
			$tabs[strtoupper($set['category'])] = $form[$i];			
		}
		
		$this->tabs->add_tabs($tabs);
		
		
		
		$data['form'] = $this->tabs->display();
		$data['page'] = $this->general_form;
		$this->load->view($this->layout_content,$data);		
	}
}