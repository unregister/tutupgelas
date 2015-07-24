<?php
$this->meta->css( _ASSET_URL . "plugins/checkboxtree/jquery.checkboxtree.css", false );
$this->meta->css( _ASSET_URL . "plugins/checkboxtree/library/jquery-ui-1.8.12.custom/css/smoothness/jquery-ui-1.8.12.custom.css",false);
//$this->meta->js( _ASSET_URL . "plugins/checkboxtree/library/jquery-1.4.4.js",false);
$this->meta->js( _ASSET_URL . "plugins/checkboxtree/library/jquery-ui-1.8.12.custom/js/jquery-ui-1.8.12.custom.min.js",false);
$this->meta->js( _ASSET_URL . "plugins/checkboxtree/jquery.checkboxtree.min.js",false);
?>

<script type="text/javascript">
$(document).ready(function(){

	$('#resourcestree').checkboxTree({ collapseUiIcon: 'ui-icon-plus', expandUiIcon: 'ui-icon-minus', leafUiIcon: '' });
	
	$("form").submit(function() {
      var formData = $(this).serializeArray();

      $.post("<?=site_url('cpanel/groups/save_permission')?>",formData,function(response){
   			$('#response').html(response.msg);
      });
      return false;
    });

	
});
</script>

<form method="post" action="" name="edit" enctype="multipart/form-data">
<input type="hidden" name="group_id" value="<?=$group_id?>" />
<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title"><?=$this->icon->fa['gears']?> Group permissions</h3>
    </div>
    
    <div class="box-body"> 
    	<div id="response"><?php echo isset($msg) ? $msg : '';?></div> 
    	  
        
        <?php echo $tree_resources; ?>
       
    </div>
     
    <div class="box-footer text-right">
    	<button type="submit" name="save_permission" class="btn btn-large btn-primary submit-form">
        	<i class="fa fa-floppy-o"></i> 
            Simpan akses
        </button>
    </div>
    
</div>
 </form>