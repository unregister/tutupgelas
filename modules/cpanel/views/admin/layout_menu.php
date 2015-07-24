<?php $this->meta->js( $this->theme_url . "js/jquery-ui-1.8.16.custom.min.js",false ); ?>
<?php $this->meta->js( $this->theme_url . "js/jquery-nestable.js",false ); ?>

<script type="text/javascript">
$(document).ready(function(){
	$('#menu-is-content').change(function(){
		if( $(this).val() == 1 ){
			$('#menu-content-id').prop('disabled',false);
			$('#menu-meta-keyword, #menu-meta-description').prop('disabled',true);	
		}else{
			$('#menu-content-id').prop('disabled',true);
			$('#menu-url').val('');
			$('#menu-meta-keyword, #menu-meta-description').prop('disabled',false);	
		}
	});	
	
	$('#menu-content-id').change(function(){
		var content_id = $(this).val();
		$.post('<?=site_url('cpanel/menu/get_content')?>',{action:'get_content',content_id:content_id},function(data){
			if(data != '')
			{
				$('#menu-url').val(data);	
			}
		});	
	});
	
	$('#menumanager').nestedSortable({
		listType: 'ul',
		handle: 'div',
		items: 'li',
		opacity: .6,
		toleranceElement: '> div',
		forcePlaceholderSize: true,
		tabSize: 15,
		placeholder: 'ns-helper',
		maxLevels: 5,
		update: function() {
			var sorted = $('#menumanager').nestedSortable('serialize');
			//var sorted = $('.sortable').nestedSortable('toArray');
			//var sorted = $('.sortable').nestedSortable('toHierarchy');
			//console.log(sorted);
			//$('#serialize').text(sorted);
			$.ajax({
				type: 'POST',
				url: $('#sortmenu').attr('action'),
				data: sorted,
				error: function() {
					//$.colorbox({html:'<h2>Error</h2>Simpan kategori gagal'});
				},
				success: function(data) {
					//$.colorbox({html: data + ' kategori berhasil disimpan'});
					//update_kategori_select();
				}
			});

		}
	});
	
});
</script>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <?php
        
        $current = 'top';
        if( isset($_GET['position']) && !empty($_GET['position']) ){
            $current = $_GET['position'];
        }
        
        foreach((array)$position as $title=>$pos)
        {
            $active = '';
            if( $pos == $current )	{
                $active = ' class="active"';	
            }
        ?>
            <li<?=$active?>><a href="<?=site_url('cpanel/menu/index?position='.$pos)?>">POSISI <?=strtoupper($title)?></a></li>
        <?php
        }
        ?>
    </ul>
    <div class="tab-content">
       
        <div id="tab_2">
           
           <div class="rows">
            	<div class="col-md-7">
                	<form method="post" action="<?=site_url('cpanel/menu/update')?>" id="sortmenu">
                	<div class="menu-admin-box" id="nestable">
                    	<?php echo $menu; ?>
                    </div>
                    </form>
                </div>
            	<div class="col-md-5 pull-right">
                	<div class="menu-admin-box">
                        <?php
						if( $on_edit )
						{
						?>
                        <h3 class="title-add-menu">Edit menu</h3>
                        <form method="post" id="form-add-menu" action="<?=site_url('cpanel/menu/edit')?>">
                        	<?= get_msg() ?>
                        	<input type="hidden" name="id" value="<?=$edit_menu['id']?>">
                        	<table border="0" width="100%">
                            	<tr>
                                	<td width="25%" align="right"><b>Position : </b></td>
                                    <td width="75%">
                                    	<select name="position" class="form-control">
                                    	<?php
										foreach((array)$position as $eTitle=>$ePos)
										{
										?>
                                        	<option value="<?=$ePos?>"<?=($edit_menu['position']==$ePos)?' selected="selected"':''?>><?=$eTitle?></option>
                                        <?php	
										}
										?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                	<td width="25%" align="right"><b>Title : </b></td>
                                    <td width="75%">
                                    	<input type="text" name="title" placeholder="Title menu" id="menu-title" class="form-control" value="<?=$edit_menu['title']?>">
                                    </td>
                                </tr>
                                <tr>
                                	<td width="25%" align="right"><b>Url : </b></td>
                                    <td width="75%">
                                    	<input type="text" name="url" placeholder="Url menu" id="menu-url" class="form-control" value="<?=$edit_menu['url']?>">
                                    </td>
                                </tr>
                                
                                <tr>
                                	<td width="25%" align="right"><b>Is Content? : </b></td>
                                    <td width="75%">
                                    	 <select name="is_content" id="menu-is-content" class="form-control" style="width:100px">
                                			<option value="0"<?=($edit_menu['is_content']==0)?' selected':''?>>No</option>
                                            <option value="1"<?=($edit_menu['is_content']==1)?' selected':''?>>Yes</option>
                               			 </select>
                                    </td>
                                </tr>
                                <tr>
                                	<td width="25%" align="right"><b>Content Id : </b></td>
                                    <td width="75%">
                                    	 <select name="content_id" id="menu-content-id" class="form-control" <?=($edit_menu['is_content']==0)?' disabled':''?>>
                                         	<?php
											foreach((array)$content as $row)
											{
											?>
                                				<option value="<?=$row['id']?>"<?=($edit_menu['content_id']==$row['id'])?' selected':''?>><?=$row['title']?></option>
                                            <?php
											}
											?>
                               			 </select>
                                    </td>
                                </tr>
                                <tr>
                                	<td width="25%" align="right"><b>Meta keyword : </b></td>
                                    <td width="75%">
                                    	<input type="text" name="meta_keyword" <?=($edit_menu['is_content']==1)?' disabled':''?> placeholder="Meta keyword" id="menu-meta-keyword" class="form-control" value="<?=$edit_menu['meta_keyword']?>">
                                    </td>
                                </tr>
                                
                                 <tr>
                                	<td width="25%" align="right"><b>Meta description : </b></td>
                                    <td width="75%">
                                        <textarea name="meta_description" <?=($edit_menu['is_content']==1)?' disabled':''?> placeholder="Meta description" id="menu-meta-description" class="form-control"><?=$edit_menu['meta_description']?></textarea>
                                    </td>
                                </tr>
                                
                                <tr>
                                	<td width="25%" align="right"><b>Active : </b></td>
                                    <td width="75%">
                                    	 <select name="active" id="menu-active" class="form-control" style="width:100px">
                                         	<option value="1"<?=($edit_menu['active']==1)?' selected':''?>>Yes</option>
                                			<option value="0"<?=($edit_menu['active']==0)?' selected':''?>>No</option>                                            
                               			 </select>
                                    </td>
                                </tr>
                                
                                 <tr>
                                	<td width="25%" align="right">&nbsp;</td>
                                    <td width="75%">
                                    	<br>
                                        <button type="submit" name="save-menu" class="btn btn-success btn-sm">
                                        	<i class="fa fa-floppy-o"></i> SIMPAN PERUBAHAN
                                        </button>
                                        <a href="<?=site_url('cpanel/menu/index/?position='.$current_position)?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> TAMBAH MENU</a>
                                    </td>
                                </tr>
                                
                            </table>
                            
                        </form>
                        <?php
						}
						else
						{
						?>
                        <h3 class="title-add-menu">Tambah menu</h3>
                    	<form method="post" id="form-add-menu" action="<?=site_url('cpanel/menu/add')?>">
                        	<?= get_msg() ?>
                        	<input type="hidden" name="position" value="<?=$current?>">
                        	<table border="0" width="100%">
                            	<tr>
                                	<td width="25%" align="right"><b>Title : </b></td>
                                    <td width="75%">
                                    	<input type="text" name="title" placeholder="Title menu" id="menu-title" class="form-control">
                                    </td>
                                </tr>
                                
                                <tr>
                                	<td width="25%" align="right"><b>Url : </b></td>
                                    <td width="75%">
                                    	<input type="text" name="url" placeholder="Url menu" id="menu-url" class="form-control">
                                    </td>
                                </tr>
                                
                                <tr>
                                	<td width="25%" align="right"><b>Is Content? : </b></td>
                                    <td width="75%">
                                    	 <select name="is_content" id="menu-is-content" class="form-control" style="width:100px">
                                			<option value="0">No</option>
                                            <option value="1">Yes</option>
                               			 </select>
                                    </td>
                                </tr>
                                
                                <tr>
                                	<td width="25%" align="right"><b>Content Id : </b></td>
                                    <td width="75%">
                                    	 <select name="content_id" id="menu-content-id" class="form-control" disabled="disabled">
                                         	<?php
											foreach((array)$content as $row)
											{
											?>
                                				<option value="<?=$row['id']?>"><?=$row['title']?></option>
                                            <?php
											}
											?>
                               			 </select>
                                    </td>
                                </tr>
                                <tr>
                                	<td width="25%" align="right"><b>Meta keyword : </b></td>
                                    <td width="75%">
                                    	<input type="text" name="meta_keyword" placeholder="Meta keyword" id="menu-meta-keyword" class="form-control" value="">
                                    </td>
                                </tr>
                                
                                 <tr>
                                	<td width="25%" align="right"><b>Meta description : </b></td>
                                    <td width="75%">
                                    	<textarea name="meta_description" placeholder="Meta description" id="menu-meta-description" class="form-control"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                	<td width="25%" align="right"><b>Active : </b></td>
                                    <td width="75%">
                                    	 <select name="active" id="menu-active" class="form-control" style="width:100px">
                                         	<option value="1">Yes</option>
                                			<option value="0">No</option>                                            
                               			 </select>
                                    </td>
                                </tr>
                                
                                 <tr>
                                	<td width="25%" align="right">&nbsp;</td>
                                    <td width="75%">
                                    	<br>
                                        <button type="submit" name="save-menu" class="btn btn-success btn-sm">
                                        	<i class="fa fa-floppy-o"></i> SIMPAN
                                        </button>
                                    </td>
                                </tr>
                                
                            </table>
                            
                        </form>
                        <?php
						}
						?>
                    </div>
                </div>
                <br style="clear:both;"/>
           </div>
           
        </div><!-- /.tab-pane -->
    </div><!-- /.tab-content -->
</div>