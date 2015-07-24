<?php
#$this->set_css($this->default_theme_path.'/twitter-bootstrap/css/bootstrap.min.css');
#$this->set_css($this->default_theme_path.'/twitter-bootstrap/css/bootstrap-responsive.min.css');
$this->set_css($this->default_theme_path.'/twitter-bootstrap/css/style.css');	
$this->set_css($this->default_theme_path.'/twitter-bootstrap/css/jquery-ui/flick/jquery-ui-1.9.2.custom.css');

$this->set_js_lib($this->default_javascript_path.'/'.grocery_CRUD::JQUERY);
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/jquery-ui/jquery-ui-1.9.2.custom.js');
$this->set_js_lib($this->default_javascript_path.'/common/lazyload-min.js');

if (!$this->is_IE7()) {
	$this->set_js_lib($this->default_javascript_path.'/common/list.js');
}
#$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/libs/bootstrap/bootstrap.min.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/libs/bootstrap/application.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/libs/modernizr/modernizr-2.6.1.custom.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/libs/tablesorter/jquery.tablesorter.min.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/cookies.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/jquery.form.js');
$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.numeric.min.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/libs/print-element/jquery.printElement.min.js');
$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.fancybox-1.3.4.js');
$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.easing-1.3.pack.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/app/twitter-bootstrap.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/jquery.functions.js');
?>
<div class="box box-info">
<div class="box-header">
	<h3 class="box-title"><i class="fa fa-pencil-square-o"></i> <?=$subject?></h3>
</div>
<div class="box-body">
	<div id="message-box" class="span12"></div>
<script type="text/javascript">
	var base_url = "<?php echo base_url();?>",
		subject = "<?php echo $subject?>",
		ajax_list_info_url = "<?php echo $ajax_list_info_url?>",
		unique_hash = "<?php echo $unique_hash; ?>",
		message_alert_delete = "<?php echo $this->l('alert_delete'); ?>";
</script>

<!-- UTILIZADO PARA IMPRESSÃO DA LISTAGEM -->
<div id="hidden-operations"></div>

<div class="twitter-bootstrap">
	<div id="main-table-box">
		<div id="options-content" class="">
			<?php
			if(!$unset_add || !$unset_export || !$unset_print){?>
				<?php if(!$unset_add){?>
					<a href="<?php echo $add_url?>" title="<?php echo $this->l('list_add'); ?> <?php echo $subject?>" class="add-anchor btn btn-primary">
						<i class="glyphicon glyphicon-plus"></i>
						<?php echo $this->l('list_add'); ?> <?php echo $subject?>
					</a>
	 			<?php
	 			}
	 			if(!$unset_export) { ?>
		 			<a class="export-anchor btn btn-danger" data-url="<?php echo $export_url; ?>" rel="external">
		 				<i class="glyphicon glyphicon-floppy-save"></i>
		 				<?php echo $this->l('list_export');?>
		 			</a>
	 			<?php
	 			}
	 			if(!$unset_print) { ?>
		 			<a class="print-anchor btn btn-warning" data-url="<?php echo $print_url; ?>">
		 				<i class="glyphicon glyphicon-print"></i>
		 				<?php echo $this->l('list_print');?>
		 			</a>
	 			<?php
	 			}
	 		} ?>
 		</div>


        <div id="searchbox" class="">
            <?php echo form_open( $ajax_list_url, 'method="post" class="form-inline" id="filtering_form" autocomplete = "off"'); ?>

            <select name="search_field" id="search_field" class="form-input">
                <?php foreach($columns as $column){?>
                    <option value="<?php echo $column->field_name?>">
                        <?php echo $column->display_as; ?>
                    </option>
                <?php }?>
            </select>
            <input type="text" class="form-input" name="search_text" size="30" id="search_text" placeholder="Keyword"></td>

            <input type="button" class="btn btn-primary" data-dismiss="modal" value="<?php echo $this->l('list_search');?>" id="crud_search">
            <input type="button" class="btn btn-danger" data-dismiss="modal" value="<?php echo $this->l('list_clear_filtering');?>" id="search_clear">   
            <input type="hidden" name="page" value="1" size="4" id="crud_page">
            <input type="hidden" name="per_page" id="per_page" value="<?php echo $default_per_page; ?>" />        
            <input type="hidden" name="order_by[0]" id="hidden-sorting" value="<?php if(!empty($order_by[0])){?><?php echo $order_by[0]?><?php }?>" />        
            <input type="hidden" name="order_by[1]" id="hidden-ordering"  value="<?php if(!empty($order_by[1])){?><?php echo $order_by[1]?><?php }?>"/>
            <?php echo form_close(); ?>
        </div>


		<!-- CONTENT FOR ALERT MESSAGES -->
		<div id="message-box" class="">
			<div class="alert alert-success <?php echo ($success_message !== null) ? '' : 'hide'; ?>">
				<a class="close" data-dismiss="alert" href="#"> x </a>
				<?php echo ($success_message !== null) ? $success_message : ''; ?>
			</div>
		</div>

		<div id="ajax_list">
			<?php echo $list_view; ?>
		</div>

		<div class="pGroup">
        	<div class="row">
            	<div class="col-md-6">
                	<div class="padding10px">
                    <select name="tb_per_page" id="tb_per_page">
						<?php foreach($paging_options as $option){?>
                            <option value="<?php echo $option; ?>" <?php echo ($option == $default_per_page) ? 'selected="selected"' : ''; ?> ><?php echo $option; ?></option>
                        <?php }?>
                    </select>
                    <span class="pPageStat">
						<?php
                        $paging_starts_from = '<span id="page-starts-from">1</span>';
                        $paging_ends_to = '<span id="page-ends-to">'. ($total_results < $default_per_page ? $total_results : $default_per_page) .'</span>';
                        $paging_total_results = '<span id="total_items"><b>'.$total_results.'</b></span>';
                        echo str_replace( array('{start}','{end}','{results}'), array($paging_starts_from, $paging_ends_to, $paging_total_results), $this->l('list_displaying')); ?>
                    </span>
                    
                    <span class="pcontrol">
						<?php echo $this->l('list_page'); ?>
                        <input name="tb_crud_page" type="text" value="1" size="4" id="tb_crud_page">
                        <?php echo $this->l('list_paging_of'); ?>
                        <span id="last-page-number"><?php echo ceil($total_results / $default_per_page); ?></span>
                    </span>
                    
                    <span class="hide loading" id="ajax-loading"><?php echo $this->l('form_update_loading'); ?></span>
                    
                    </div>
                </div>
                <div class="col-md-6">
                	<div class="padding10px">
                    	<ul class="pager">
                            <li class="previous first-button"><a href="javascript:void(0);">&laquo; <?php echo $this->l('list_paging_first'); ?></a></li>
                            <li class="prev-button"><a href="javascript:void(0);">&laquo; <?php echo $this->l('list_paging_previous'); ?></a></li>
                            <li class="next-button"><a href="javascript:void(0);"><?php echo $this->l('list_paging_next'); ?> &raquo;</a></li>
                            <li class="next last-button"><a href="javascript:void(0);"><?php echo $this->l('list_paging_last'); ?> &raquo;</a></li>
                        </ul>
                    </div>
                </div>
            </div>			
		</div>
	</div>
</div>

</div>

