<?php
if ( ! defined('_VALID_BBC')) {
	ob_start('ob_gzhandler');
	header('content-type: text/javascript; charset: UTF-8');
	header('cache-control: must-revalidate');
	$offset = 60 * 60 * 24 * 365;
	$expire = 'expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT';
	header($expire);
	include 'tabpage.js';
	die();
}

function tabpage($data, $use_cookie = 1, $name='tabs', $maxwidth = false, $r_iframe = array(), $automodeperiod=0)
{
	global $Bbc;
	$Bbc->tabpage_number = isset($Bbc->tabpage_number) ? ($Bbc->tabpage_number + 1) : 1;
	$name = $name.$Bbc->tabpage_number;
	$output = '';
	if(!empty($data))
	{
		if(count($data) > 1)
		{
			$r_pane = $r_page = array();$i = 0;
			foreach((array)$data AS $pane => $page)
			{
				$i++;
				if(is_url($page))
				{
					if(in_array($page, $r_iframe) || $r_iframe == 'all') {
						$r_pane[] = '<li><a href="'.$page.'" rel="#iframe">'.$pane.'</a></li>';
					}else{
						$r_pane[] = '<li><a href="'.$page.'" rel="#url">'.$pane.'</a></li>';
					}
					$r_page[] = '';
				}else{
					$r_pane[] = '<li><a  rel="#'.$i.'">'.$pane.'</a></li>';
					$r_page[] = '<div id="'.$name.'-page'.$i.'" class="tab-content">'.$page.'</div>';
				}
			}
			if(!empty($r_pane))
			{
				global $sys;
				$path = 'includes/function/tabs/';
				$sys->link_css($path.'tabpage.css');
				$sys->link_js($path.'tabpage.js');
				$use_cookie = $use_cookie ? 'true' : 'false';
				$maxwidth = $maxwidth ? 'true' : 'false';
				ob_start();
				?>
				<div class="wrapper-tab-pane-control">
					<ul class="tab-panel" id="<?=$name;?>"><?=implode('', $r_pane);?></ul>
					<div class="tab-page" id="<?=$name;?>-page"></div><?=implode('', $r_page);?>
					<script type="text/javascript">
						var <?=$name;?> = new danangtabs("<?=$name;?>");
						<?=$name;?>.setpersist(<?=$use_cookie;?>);
						<?=$name;?>.init(<?=$maxwidth;?>, <?=$automodeperiod;?>);
					</script>
				</div>
				<?
				$output = ob_get_contents();
				ob_end_clean();
			}
		}else{
			$output = current($data);
		}
	}
	return $output;
}

