<?php
$this->meta->css(_ASSET_URL.'grocery_crud/themes/twitter-bootstrap/css/style.css', false);
$this->meta->css(_ASSET_URL.'grocery_crud/themes/twitter-bootstrap/css/jquery-ui/flick/jquery-ui-1.9.2.custom.css',false);

?>

<form method="post" action="">
<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title"><?=$this->icon->fa['unlock-alt']?> Ubah password</h3>
    </div>
    
    <div class="box-body">    
    	<?php echo $report; ?>
        <table class="table table-bordered tablesorter table-striped">
        	<tr>
            	<td width="20%" align="right"><strong>Password Lama <font color="red">*</font> : </strong></td>
                <td width="80%"><input type="password"  value="" name="password_old" class="form-input col-xs-8" id="password_old"></td>
            </tr>
            <tr>
            	<td width="20%" align="right"><strong>Password Baru <font color="red">*</font> : </strong></td>
                <td width="80%"><input type="password"  value="" name="password_new" class="form-input col-xs-8" id="password_new"></td>
            </tr>
            <tr>
            	<td width="20%" align="right"><strong>Ulangi Password Baru <font color="red">*</font> : </strong></td>
                <td width="80%"><input type="password"  value="" name="password_con" class="form-input col-xs-8" id="password_con"></td>
            </tr>
        </table>        
    
    </div>
    
    <div class="box-footer text-right">
        <button type="submit" name="change_password" class="btn btn-large btn-primary submit-form">
            <i class="fa fa-floppy-o"></i> Ubah password
        </button>
    </div>
</div>
</form>