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
$this->set_js_config($this->default_theme_path.'/twitter-bootstrap/js/app/twitter-bootstrap-add.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/jquery.functions.js');
?>
<div class="box box-info">
<div class="box-header">
	<h3 class="box-title"><i class="fa fa-pencil-square-o"></i> <?php echo $this->l('form_add'); ?> <?php echo $subject?></h3>
</div>
<div class="box-body">
	<div id="message-box" class="span12"></div>
	<?php echo form_open( $insert_url, 'method="post" id="crudForm" class="form-div span12" autocomplete="off" enctype="multipart/form-data"'); ?>
    <table class="table table-bordered tablesorter table-striped">
        <?php foreach($fields as $field): ?>
            <tr>
                <td width="20%" align="right">
                	<strong>
                    <?php echo $input_fields[$field->field_name]->display_as; ?>
                    <?php echo ($input_fields[$field->field_name]->required)? '<font color="red">*</font>' : ""; ?>
                    : </strong>
                </td>
                <td width="80%"><?php echo $input_fields[$field->field_name]->input?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<div class="box-footer text-right">
	<button type="button"  class="btn btn-large btn-primary submit-form">
    	<i class="fa fa-floppy-o"></i> <?php echo $this->l('form_save'); ?>
    </button>
    <?php 	if(!$this->unset_back_to_list) { ?>
    <button type="button" id="save-and-go-back-button" class="btn btn-large btn-primary">
		<i class="fa fa-share-square-o"></i> <?php echo $this->l('form_save_and_go_back'); ?>
    </button>
    <a href="<?php echo $list_url?>">
    <button type="button" class="btn btn-large btn-primary">
		<i class="fa fa-times-circle"></i> <?php echo $this->l('form_cancel'); ?>
    </button>
    </a>
    <?php 	} ?>
</div>
<?php
	if( !empty($hidden_fields) ){
		foreach($hidden_fields as $hidden_field){
			echo $hidden_field->input;
		}
	}
?>

<?php echo form_close(); ?>
</div>


<script>
var validation_url = "<?php echo $validation_url?>",
	list_url = "<?php echo $list_url?>",
	message_alert_add_form = "<?php echo $this->l('alert_add_form')?>",
	message_insert_error = "<?php echo $this->l('insert_error')?>";
</script>