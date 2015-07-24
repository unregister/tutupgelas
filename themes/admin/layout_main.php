<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<script type="text/javascript">
var BASE_URL = '<?=_URL?>';
</script>
<?php
$this->meta->css( $this->theme_url . "css/bootstrap.min.css" );
$this->meta->css( $this->theme_url . "css/font-awesome.min.css" );
$this->meta->css( $this->theme_url . "css/ionicons.min.css" );
$this->meta->css( $this->theme_url . "css/style.css" );

$this->meta->js( $this->theme_url . "js/jquery.js");
$this->meta->js( $this->theme_url . "js/bootstrap.min.js");
$this->meta->js( $this->theme_url . "js/app.js");

echo $this->meta->display( false );
?>



</head>
<body class="skin-blue pace-done fixed">
    <!-- header logo: style can be found in header.less -->
    <header class="header">
        <a href="<?=site_url('home/admin/dashboard')?>" class="logo">
           <?php if( $this->meta->logo() ): ?>
           	<img src="<?=$this->meta->logo()?>" style="height:30px">
           <?php else: ?>
           	Administrator
           <?php endif; ?>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-right">
                <ul class="nav navbar-nav">
           
                    <li class="dropdown tasks-menu">
                        <a href="<?= _URL ?>" target="_blank">
                            <i class="fa fa-globe"></i>
                            <span>Lihat website</span>
                        </a>                        
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown">
                        <a href="<?=site_url('auth/logout')?>">
                            <i class="fa fa-power-off"></i>
                            <span>Logout</span>
                        </a>                        
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="left-side sidebar-offcanvas">                
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                    	<?php
						if( $this->user->image ){
							$avatar = _URL . "files/users/{$this->user->image}";	
						}else{
							if($this->user->gender == 1){
								$avatar = $this->theme_url . "img/male.png";	
							}else{
								$avatar = $this->theme_url . "img/female.png";	
							}
							
								
						}
						?>
                        <img src="<?=$avatar?>" class="img-circle" alt="User Image" />
                    </div>
                    <div class="pull-left info">
                        <p>Hello, <?=$this->user->name?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                <!-- search form -->
                
                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li>
                        <a href="<?= site_url('home/admin/dashboard')?>">
                            <i class="fa fa-home"></i> <span>DASHBOARD</span>
                        </a>
                    </li>
                    <?php $this->meta->get_admin_menu()?>
                    <li>
                        <a href="<?= site_url('cpanel/settings/dashboard')?>">
                            <i class="fa fa-wrench"></i> <span>SETTINGS</span>
                        </a>
                    </li>
                 
                    
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">                
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?=(isset($title))?$title:""?>
                </h1>
                <!--<ol class="breadcrumb">
                    <?php echo $this->meta->breadcrumb();?>
                </ol>-->
            </section>

            <!-- Main content -->
            <section class="content">

			 <?php
				if( isset($page) ) 
				{
					
					if( isset($module) and !empty($module) )
					{
						if( file_exists(_ROOT."modules/$module/views/admin/".$page.".php") ){
							include _ROOT . "modules/$module/views/admin/".$page.".php";
						}else{
							echo "File : "._ROOT."modules/$module/views/admin/".$page.".php tidak ditemukan";	
						}
					}else{
						#$this->load->view($page);
						
						if( file_exists(_ROOT."themes/admin/".$page.".php") ){
							include _ROOT . "themes/admin/".$page.".php";
						}else{
							echo "File : "._ROOT."themes/admin/".$page.".php tidak ditemukan";	
						}
						
					}
				}
				?>

            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->


</body>
</html>