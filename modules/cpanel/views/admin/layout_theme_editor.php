<?php
$this->meta->css( _ASSET_URL . "plugins/treeview/jquery.treeview.css",false);
$this->meta->js( _ASSET_URL . "js/jquery.cookie.js",false);
$this->meta->js( _ASSET_URL . "plugins/treeview/jquery.treeview.js",false);
?>
<style type="text/css">
	#btnaction {
		border-bottom: 1px solid #f4f4f4;
		padding: 5px;
	}
	#filetree {
		overflow-y: scroll;
		min-height: 400px; 
	}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$('#tree').treeview();
});
</script>
<div class="box box-info">
	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-pencil-square-o"></i> Edit theme <?=$current_theme?></h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<div id="btnaction">
					<div class="pull-left">
						<div id="loadingsave" style="display:none;">							
						</div>
					</div>
					<div class="pull-right">
						<button class="btn btn-success btn-sm" id="save_file" type="submit">Simpan</button>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div id="filetree">
					<?php
						echo $file;
					?>
				</div>
			</div>
  			<div class="col-md-9">
  				<form method="post" action="<?=site_url('cpanel/theme/save_file')?>" id="form-editor">
  					<div id="theme-editor" style="min-height:500px"><?=$content?></div>
  				</form>
  			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?=_URL?>assets/js/ace/ace.js"></script>
<script type="text/javascript">
var editor = ace.edit("theme-editor");
editor.setTheme("ace/theme/ambience");
editor.getSession().setMode("ace/mode/<?php echo $mode; ?>");

$('#save_file').on('click',function(e){
	//e.preventDefault();
	var content = editor.getValue();
	//alert( content );
	$.ajax({
		url:$('#form-editor').attr('action'),
		type:'POST',
		dataType:'json',
		data:{ theme: '<?=$current_theme?>',file:'<?=$current_file?>',file_content:content },
		beforeSend: function(){			
			$('#loadingsave').text('Menyimpan file...').fadeIn(400);
		},
		success: function(response){
			if( response.status == 1 ){
				$('#loadingsave').html('<font color="green">Berhasil simpan file</font>').fadeIn(400);
			}else{
				$('#loadingsave').html('<font color="red">Gagal simpan file</font>').fadeIn(400);
			}
		},
		error: function(){
			$('#loadingsave').html('<font color="red">Gagal simpan file. Silahkan ulangi</font>').fadeIn(400);
		}
	});
	return false;
});

</script>