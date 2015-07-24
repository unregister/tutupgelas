<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title"><?=$this->icon->fa['gears']?> Advance settings</h3>
    </div>
    
    <div class="box-body">
    
        <div class="row" id="advance-settings">
        
        	<?php if( check_user('advance.settings',false) ):?>
			
            <div class="col-md-4 settings-box">
                <div class="row">
                    <div class="col-md-3 settings-icon"><?=$this->icon->fa['gears']?></div>
                    <div class="col-md-9">
                        <div class="settings-title"><a href="<?=site_url('cpanel/settings/data')?>">Pengaturan website</a></div>
                        <div class="settings-desc">Menu ini digunakan untuk konfigurasi standar website. </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if( check_user('menu.manager',false) ):?>
            <div class="col-md-4 settings-box">
                <div class="row">
                    <div class="col-md-3 settings-icon"><?=$this->icon->fa['tasks']?></div>
                    <div class="col-md-9">
                        <div class="settings-title"><a href="<?=site_url('cpanel/menu')?>">Menu manager</a></div>
                        <div class="settings-desc">Menu manager adalah halaman dimana Anda bisa mengatur konfigurasi menu website Anda. </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if( check_user('user.manager',false) ):?>
            <div class="col-md-4 settings-box">
                <div class="row">
                    <div class="col-md-3 settings-icon"><?=$this->icon->fa['user']?></div>
                    <div class="col-md-9">
                        <div class="settings-title"><a href="<?=site_url('cpanel/user/data')?>">User manager</a></div>
                        <div class="settings-desc">Menu ini dipergunakan untuk mengatur user yang akan mengelola webiste Anda. </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if( check_user('group.manager',false) ):?>
            <div class="col-md-4 settings-box">
                <div class="row">
                    <div class="col-md-3 settings-icon"><?=$this->icon->fa['users']?></div>
                    <div class="col-md-9">
                        <div class="settings-title"><a href="<?=site_url('cpanel/groups/index')?>">User groups</a></div>
                        <div class="settings-desc">User groups digunakan untuk mengatur level user yang mengelola konten website. </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if( check_user('resources.manager',false) ):?>
            <div class="col-md-4 settings-box">
                <div class="row">
                    <div class="col-md-3 settings-icon"><?=$this->icon->fa['code']?></div>
                    <div class="col-md-9">
                        <div class="settings-title"><a href="<?=site_url('cpanel/resources/index')?>">Resources</a></div>
                        <div class="settings-desc">Menu ini berisi data resources yang berkaitan dengan permission halaman Admin. </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
             <div class="col-md-4 settings-box">
                <div class="row">
                    <div class="col-md-3 settings-icon"><?=$this->icon->fa['unlock-alt']?></div>
                    <div class="col-md-9">
                        <div class="settings-title"><a href="<?=site_url('cpanel/user/change_password')?>">Ubah password</a></div>
                        <div class="settings-desc">Anda dapat mengubah password login anda melalui menu ini. </div>
                    </div>
                </div>
            </div>

            
            <?php if( check_user('module.manager',false) ):?>
            <div class="col-md-4 settings-box">
                <div class="row">
                    <div class="col-md-3 settings-icon"><?=$this->icon->fa['suitcase']?></div>
                    <div class="col-md-9">
                        <div class="settings-title"><a href="<?=site_url('cpanel/module/index')?>">Module manager</a></div>
                        <div class="settings-desc">Menu ini digunakan untuk menginstall/uninstall module website. </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if( check_user('theme.manager',false) ):?>
            <div class="col-md-4 settings-box">
                <div class="row">
                    <div class="col-md-3 settings-icon"><?=$this->icon->fa['leaf']?></div>
                    <div class="col-md-9">
                        <div class="settings-title"><a href="<?=site_url('cpanel/theme/index')?>">Theme manager</a></div>
                        <div class="settings-desc">Anda bisa mengganti tampilan website pada menu ini. </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if( check_user('email.manager',false) ):?>
            <div class="col-md-4 settings-box">
                <div class="row">
                    <div class="col-md-3 settings-icon"><?=$this->icon->fa['envelope']?></div>
                    <div class="col-md-9">
                        <div class="settings-title"><a href="<?=site_url('cpanel/email/index')?>">Email template manager</a></div>
                        <div class="settings-desc">Menu ini digunakan untuk konfigurasi email templating. </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
     </div>
     
     <div class="box-footer text-right">
    &nbsp;
    </div>
    
</div>