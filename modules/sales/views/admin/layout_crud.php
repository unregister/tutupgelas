
<?php 
# Load CSS
foreach($output->css_files as $file):
	$this->meta->css($file,false);
endforeach;

# Load Javascript
foreach($output->js_files as $file): 
	$this->meta->js($file,false);
endforeach;

# Load custom css
if( isset($css) and count($css) > 0 ):
	foreach((array)$css as $style):
		$this->meta->css($style,false);
	endforeach;
endif;

# Load custom Javascript
if( isset($js) and count($js) > 0 ):
	foreach((array)$js as $script):
		$this->meta->js($script,false);
	endforeach;
endif;

# Load custom html
if( isset($html) ){
	echo $html;	
}

# Render Crud
echo $output->output;

?>
