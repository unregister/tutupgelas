<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function pr($var,$as_string=false)
{
	if ($as_string) ob_start();
	
	if (isset($_SERVER['HTTP_USER_AGENT'])) { 
		echo " <pre>\n";print_r($var);echo "</pre>\n";
	} else
		print_r($var);
		
	if ($as_string) {
		$s = ob_get_contents();
		ob_end_clean();
		return $s;
	}
}

function current_theme()
{
	$obj =& get_instance();
	$theme = $obj->adodb->GetOne("SELECT `name` FROM sys_templates WHERE active = 1");
	return $theme;
}

function is_url($text)
{
	$regex	= '/^((?:ht|f)tps?:\/\/)?[a-z0-9\-]+\.[a-z0-9\-\.]+\/?/is';
	$output = (preg_match($regex, $text)) ? true : false;
	return $output;
}
// check is the string is email...
function is_email($text)
{
	$regex  = '/^[a-z0-9\_\.]+@(?:[a-z0-9\-\.]){1,66}\.(?:[a-z]){2,6}$/is';
	$output = (preg_match($regex, $text)) ? true : false;
	return $output;
}
// check is the string is phone...
function is_phone($text)
{
	$regex  = '/^\+?([0-9]+[\s\.\-]?(?:[0-9\s\.-]+)?){5,}$/is';
	$output = (preg_match($regex, $text)) ? true : false;
	return $output;
}

function css( $style, $is_meta = true )
{
	$ci =& get_instance();
	return $ci->meta->css( $style, $is_meta );
}

function js( $script, $is_meta = true )
{
	$ci =& get_instance();
	return $ci->meta->js( $script, $is_meta );
}

function asset_url( $url )
{
	$extension = pathinfo($url, PATHINFO_EXTENSION);
	$output = "";
	
	if( strtolower($extension) == 'css' )
	{
		if ( !preg_match('#http://#isU', $url) ) 
		{
			$asset_url = _URL . "assets/";
			$output .= $asset_url.$url;
		}
		else
		{
			$output .= $url;
		}
	}
	elseif (strtolower($extension) == 'js' )
	{
		if ( !preg_match('#http://#isU', $url) ) 
		{
			$asset_url = _URL . "assets/";
			$output .= $asset_url.$url;
		}
		else
		{
			$output .= $url;
		}
	}
	return $output;
}

function theme_url( $url )
{
	$extension = pathinfo($url, PATHINFO_EXTENSION);
	$ci =& get_instance();
	
	$output = "";
	
	if( strtolower($extension) == 'css' )
	{
		if ( !preg_match('#http://#isU', $url) ) 
		{
			$current_theme = $ci->config->theme;
			$theme_url = _URL . "themes/$current_theme/assets/";
			$output .= $theme_url.$url;
		}
		else
		{
			$output .= $url;
		}
	}
	elseif (strtolower($extension) == 'js' )
	{
		if ( !preg_match('#http://#isU', $url) ) 
		{
			$current_theme = $ci->config->theme;
			$theme_url = _URL . "themes/$current_theme/assets/";
			$output .= $theme_url.$url;
		}
		else
		{
			$output .= $url;
		}
	}
	return $output;
}

function image( $folder, $attr = "" )
{
	if( is_file( _ROOT . $folder) ){
		$img = _URL.$folder;
		return $img;	
	}else{
		return FALSE;	
	}
}

function mysql_date($date,$format='d/m/y', $use_jam = false, $use_month = false)
{
	if( $date and $date != '0000-00-00 00:00:00' )
	{
		$month = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		
		$tgl = substr($date,8,2);
		$bln = substr($date,5,2);
		$thn = substr($date,0,4);
		$jam = substr($date,11,2);
		$mnt = substr($date,14,2);
		$dtk = substr($date,17,2);
		
		switch($format)
		{
			case 'd/m/y':
				if( $use_month )
				{
					if( $bln<10 )
					{
						$bln = str_replace("0","",$bln);
						$bln = $month[$bln];	
					}
				}
				$clock = ($use_jam)?" $jam:$mnt":"";
				$format = "$tgl/$bln/$thn".$clock;
			break;
			
			case 'd-m-y':
				if( $use_month )
				{
					if( $bln<10 )
					{
						$bln = str_replace("0","",$bln);
						$bln = $month[$bln];	
					}
				}
				$clock = ($use_jam)?" $jam:$mnt":"";
				$format = "$tgl-$bln-$thn".$clock;
			break;
			
			case 'd m y':
				if( $use_month )
				{
					if( $bln<10 )
					{
						$bln = str_replace("0","",$bln);
						$bln = $month[$bln];	
					}
				}
				$clock = ($use_jam)?" $jam:$mnt":"";
				$format = "$tgl $bln $thn".$clock;
			break;
			
			default:
				if( $use_month )
				{
					if( $bln<10 )
					{
						$bln = str_replace("0","",$bln);
						$bln = $month[$bln];	
					}
				}
				$clock = ($use_jam)?" $jam:$mnt":"";
				$format = "$tgl/$bln/$thn".$clock;
			break;
		}
		
		return $format;	
	}
}

function rupiah( $val, $real = false ){
	if ($real) {
		return number_format( $val, 0,',','.');
	}
	return 'Rp '. number_format( $val, 0,',','.') .',-';
}

function email_template( $name = '' )
{
	$ci =& get_instance();
	$ci->db->where('template_name',$name);
	$run = $ci->db->get('sys_email_template')->row();
	
	$row['subject'] = $run->subject;
	$row['content'] = $run->content;
	$row['email'] = $run->email_from;
	$row['name'] = $run->email_from_name;
	return $row;
}

function replace_email_template ( $content,$data = array() ){
	$regex	= '#\{(.*?)\}#is';
	preg_match_all( $regex, $content, $match );	
	
	$replace	= array();
	foreach( $match[1] as $val){			
		if ( isset($data[$val]) ){			
			$replace[]	= $data[$val];
		}else{
			$replace[]	= $val;
		}
	}

	$pattern	= array();
	foreach( $match[1] as $val){
		$pattern[]	= "#\{$val\}#";
	}
	
	$content	= preg_replace( $pattern, $replace, $content );
	
	return $content;
}

function path_list($path, $order = 'asc')
{
	$output = array();
	if ($dir = @opendir($path)) {
		while (($data = readdir($dir)) !== false) {
			if($data != '.' and $data != '..'){
				$output[] = $data;
			}
		}  
		closedir($dir);
	}
	if(strtolower($order) == 'desc')		rsort($output);
	else		sort($output);
	reset($output);
	return $output;	
}

function path_list_r($path, $top_level_only = FALSE)
{
	if ($fp = @opendir($path))
	{
		$path = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;		
		$filedata = $item = array();
		while (FALSE !== ($file = readdir($fp)))
		{
			if (strncmp($file, '.', 1) == 0)
			{
				continue;
			}
			if ($top_level_only == FALSE && @is_dir($path.$file))
			{
				$temp_array = array();
				$temp_array = path_list_r($path.$file.DIRECTORY_SEPARATOR);

				$item['name'] = $file;
				$item['is_folder'] = true;
				$item['path'] = $path.$file;
				$item['child'] = $temp_array;
				$filedata[] = $item;
			}
			else
			{
				$item['name'] = $file;
				$item['is_folder'] = false;
				$item['path'] = $path.$file;
				$item['child'] = array();
				$filedata[] = $item;
			}
		}
		closedir($fp);
		return $filedata;
	}
	return false;
}

function path_delete($path)
{
	if($path == _ROOT) return false;
	elseif(!preg_match('~^'._ROOT.'~', $path)) return false;
	if (file_exists($path)) {
		@chmod($path,0777);
		if (is_dir($path)) {
			$handle = opendir($path);
			while($filename = readdir($handle)) {
				if ($filename != "." && $filename != "..") {
					path_delete($path.'/'.$filename);
				}
			}
			closedir($handle);
			@rmdir($path);
		} else {
			@unlink($path);
		}
	}
}
function path_create($path, $chmod = 0777)
{
	if(!empty($path))
	{
		if(file_exists($path)) $output = true;
		else {
			$path = preg_replace('~^'.addslashes(_ROOT).'~', '', $path);
			$path = preg_replace('~^'.addslashes(_URL).'~', '', $path);
			$tmp_dir = _ROOT;
			$r = explode('/', $path);
			foreach($r AS $dir)
			{
				$tmp_dir .= $dir.'/';
				if(!file_exists($tmp_dir))
				{
					if(mkdir($tmp_dir, $chmod))
					{
						chmod($tmp_dir, $chmod);
					}
				}
			}
			$output = file_exists($tmp_dir);
		}
	}else{
		$output = false;
	}
	return $output;
}

function file_read($file = '', $method = 'r')
{
	if ( ! file_exists($file))
	{
		return FALSE;
	}
	if (function_exists('file_get_contents'))
	{
		return file_get_contents($file);
	}
	if ( ! $fp = @fopen($file, $method))
	{
		return FALSE;
	}
	flock($fp, LOCK_SH);
	$data = '';
	if (filesize($file) > 0)
	{
		$data =& fread($fp, filesize($file));
	}
	flock($fp, LOCK_UN);
	fclose($fp);
	return $data;
}

function file_write($path, $data='', $mode = 'w+')
{
	if(!file_exists(dirname($path)))
	{
		path_create(dirname($path));
	}
	if ( ! $fp = @fopen($path, $mode))
	{
		return FALSE;
	}
	flock($fp, LOCK_EX);
	fwrite($fp, $data);
	flock($fp, LOCK_UN);
	fclose($fp);
	@chmod($path, 0777);
	return TRUE;
}

function tree_files( $arr,$deep = 1 )
{
	$current_theme = $_GET['theme'];
	$theme_path = _ROOT."themes/$current_theme/";
	if( $arr )
	{
		$ulid = ($deep==1)?' id="tree" class="filetree"':'';
		$html = "<ul$ulid>\n";

		foreach( (array)$arr as $r )
		{			
			if( $r['is_folder'] ){
				$cls = 'folder';
				$name = $r['name'];
				$close = ' class="closed"';
			}else{
				$close = '';
				$paths = str_replace($theme_path,"",$r['path']);
				$paths = ltrim($paths,"\/");
				$o  = str_replace("\\",":",$paths);
				$cls = 'file';
				$name = "<a href=\"".site_url('cpanel/theme/editor?theme='.$current_theme)."&file=".urlencode($o)."\">$r[name]</a>";
			}
			$html .= "<li".$close.">";
			$html .= "<span class=\"$cls\">$name</span>";
			$html .= tree_files($r['child'],($deep+1));
			$html .= "</li>";
		}

		$html .= "</ul>\n";
		return $html;
	}
}

