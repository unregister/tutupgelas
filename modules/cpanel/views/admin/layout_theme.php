<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title"><?=$this->icon->fa['leaf']?> Theme manager</h3>
    </div>
    
    <div class="box-body">
    	<div class="row">
		<?php
            if( !empty($theme) )
            {
                foreach((array)$theme as $t)
                {
					if( file_exists(_ROOT."themes/$t/assets/preview.png") ){
						$preview = _URL."themes/$t/assets/preview.png";	
					}else{
						$preview = _ASSET_URL . "images/preview-default.png";	
					}
					
        ?>
                    <div class="col-md-4">
                    	<div class="text-center">
                        	<img src="<?=$preview?>" class="img-thumbnail">
                        </div>
                        <br>
                        <div>
                        	<div class="col-md-12">
								<div class="text-center">
									<?=strtoupper($t)?> | 
                                    <?php
									if( $current_theme == $t )
									{
										echo '<font color="green">THEME AKTIF</font>';	
									}
									else
									{
										echo '<a href="'.site_url('cpanel/theme/change/'.$t).'">GUNAKAN THEME INI</a>';
									}	
									?> | 
                                    <a href="<?=site_url("cpanel/theme/editor?theme=$t")?>">THEME EDITOR</a>
                                 </div>
                            </div>
                        </div>
                    </div>
        <?php	
                }
            }
            else
            {
                echo info("Tidak ada theme. Anda menggunakan theme default");	
            }
        ?>
    	</div>
    </div>
     
     <div class="box-footer text-right">&nbsp;</div>
    
</div>