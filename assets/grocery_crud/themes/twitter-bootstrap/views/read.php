<?php
$this->set_css($this->default_theme_path.'/twitter-bootstrap/css/style.css');
?>
<div class="box box-info">
<div class="box-header">
	<h3 class="box-title"><i class="fa fa-pencil-zoom"></i> Preview</h3>
</div>
<div class="box-body">
	<div id="message-box" class="span12"></div>
	
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
    <a href="<?php echo $list_url?>">
    <button type="button" class="btn btn-large btn-primary">
		<i class="fa fa-times-circle"></i> Kembali
    </button>
    </a>
</div>

</div>
