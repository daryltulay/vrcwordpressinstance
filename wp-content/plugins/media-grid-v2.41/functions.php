<?php

// get the current URL
function lcwp_curr_url() {
	$pageURL = 'http';
	
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://" . $_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];

	return $pageURL;
}
	

// get file extension from a filename
function lcwp_stringToExt($string) {
	$pos = strrpos($string, '.');
	$ext = strtolower(substr($string,$pos));
	return $ext;	
}


// get filename without extension
function lcwp_stringToFilename($string, $raw_name = false) {
	$pos = strrpos($string, '.');
	$name = substr($string,0 ,$pos);
	if(!$raw_name) {$name = ucwords(str_replace('_', ' ', $name));}
	return $name;	
}


// string to url format // NEW FROM v1.11 for non-latin characters 
function lcwp_stringToUrl($string){
	
	// if already exist at least an option, use the default encoding
	if(!get_option('mg_non_latin_char')) {
		$trans = array("à" => "a", "è" => "e", "é" => "e", "ò" => "o", "ì" => "i", "ù" => "u");
		$string = trim(strtr($string, $trans));
		$string = preg_replace('/[^a-zA-Z0-9-.]/', '_', $string);
		$string = preg_replace('/-+/', "_", $string);	
	}
	
	else {$string = trim(urlencode($string));}
	
	return $string;
}


// normalize a url string
function lcwp_urlToName($string) {
	$string = ucwords(str_replace('_', ' ', $string));
	return $string;	
}


// remove a folder and its contents
function lcwp_remove_folder($path) {
	if($objs = @glob($path."/*")){
		foreach($objs as $obj) {
			@is_dir($obj)? lcwp_remove_folder($obj) : @unlink($obj);
		}
	 }
	@rmdir($path);
	return true;
}


// create youtube and vimeo embed url
function lcwp_video_embed_url($raw_url) {
	if(strpos($raw_url, 'vimeo')) {
		$code = substr($raw_url, (strrpos($raw_url, '/') + 1));
		$url = '//player.vimeo.com/video/'.$code.'?title=0&amp;byline=0&amp;portrait=0';
	}
	elseif(strpos($raw_url, 'youtu.be')) {
		$code = substr($raw_url, (strrpos($raw_url, '/') + 1));
		$url = '//www.youtube.com/embed/'.$code.'?rel=0';	
	}
	else {return 'wrong_url';}
	
	// autoplay
	if(get_option('mg_video_autoplay')) {$url .= '&amp;autoplay=1';}
	
	return $url;
}

/////////////////////////////

// sanitize input field values
function mg_sanitize_input($val) {
	return trim(
		str_replace(array('\'', '"', '<', '>'), array('&apos;', '&quot;', '&lt;', '&gt;'), (string)$val)
	);	
}


// item types array
function mg_main_types() {
	return array(
		'image' => __('Image', 'mg_ml'), 
		'img_gallery' => __('Image Gallery', 'mg_ml'), 
		'video' => __('Video', 'mg_ml'), 
		'audio' => __('Audio', 'mg_ml')
	);	
}


// slider cropping methods
function mg_galleria_crop_methods($type = false) {
	$types = array(
		'true' 		=> __('Fit, center and crop', 'mg_ml'),
		'false' 	=> __('Scale down', 'mg_ml'),
		'height'	=> __('Scale to fill the height', 'mg_ml'),
		'width'		=> __('Scale to fill the width', 'mg_ml'),
		'landscape'	=> __('Fit images with landscape proportions', 'mg_ml'),
		'portrait' 	=> __('Fit images with portrait proportions', 'mg_ml')
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}


// slider effects
function mg_galleria_fx($type = false) {
	$types = array(
		'fadeslide' => __('Fade and slide', 'mg_ml'),
		'fade' 		=> __('Fade', 'mg_ml'),
		'flash'		=> __('Flash', 'mg_ml'),
		'pulse'		=> __('Pulse', 'mg_ml'),
		'slide'		=> __('Slide', 'mg_ml'),
		''			=> __('None', 'mg_ml')
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}


// slider thumbs visibility options
function mg_galleria_thumb_opts($type = false) {
	$types = array(
		'always'	=> __('Always', 'mg_ml'),
		'yes' 		=> __('Yes with toggle button', 'mg_ml'),
		'no' 		=> __('No with toggle button', 'mg_ml'),
		'never' 	=> __('Never', 'mg_ml'),
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}


// image ID to path
function mg_img_id_to_path($img_src) {
	if(is_numeric($img_src)) {
		$wp_img_data = wp_get_attachment_metadata((int)$img_src);
		if($wp_img_data) {
			$upload_dirs = wp_upload_dir();
			$img_src = $upload_dirs['basedir'] . '/' . $wp_img_data['file'];
		}
	}
	
	return $img_src;
}


// thumbnail source switch between timthumb and ewpt
function mg_thumb_src($img_id, $width = false, $height = false, $quality = 80, $alignment = 'c', $resize = 1, $canvas_col = 'FFFFFF', $fx = array()) {
	if(!$img_id) {return false;}
	
	if(get_option('mg_use_timthumb')) {
		$thumb_url = MG_TT_URL.'?src='.mg_img_id_to_path($img_id).'&w='.$width.'&h='.$height.'&a='.$alignment.'&q='.$quality.'&zc='.$resize.'&cc='.$canvas_col;
	} else {
		$thumb_url = easy_wp_thumb($img_id, $width, $height, $quality, $alignment, $resize, $canvas_col , $fx);
	}	
	
	return $thumb_url;
}
 


// get the patterns list 
function mg_patterns_list() {
	$patterns = array();
	$patterns_list = scandir(MG_DIR."/img/patterns");
	
	foreach($patterns_list as $pattern_name) {
		if($pattern_name != '.' && $pattern_name != '..') {
			$patterns[] = $pattern_name;
		}
	}
	return $patterns;	
}


// check if there is at leat one custom option
function mg_cust_opt_exists() {
	$types = mg_main_types();
	$exists = false;
	
	foreach($types as $type => $name) {
		if(get_option('mg_'.$type.'_opt') && count(get_option('mg_'.$type.'_opt')) > 0) {$exists = true; break;}	
	}
	return $exists;
}





// sizes array
function mg_sizes() {
	return array(
		'1_1',
		'1_2',
		
		'1_3',
		'2_3',
		
		'1_4',
		'3_4',
		
		'1_5',
		'2_5',
		'3_5',
		'4_5',
		
		'1_6',
		'5_6'
	);
}


// sizes to percents
function mg_size_to_perc($size) {
	switch($size) {
		case '5_6': $perc = 0.83; break;
		case '1_6': $perc = 0.166; break;
		
		case '4_5': $perc = 0.80; break;
		case '3_5': $perc = 0.60; break;
		case '2_5': $perc = 0.40; break;
		case '1_5':
		case 'auto':$perc = 0.20; break;
		
		case '3_4': $perc = 0.75; break;
		case '1_4': $perc = 0.25; break;
		
		case '2_3': $perc = 0.666; break;
		case '1_3': $perc = 0.333; break;
		
		case '1_2': $perc = 0.50; break;
		default :	$perc = 1; break;
	}
	
	return $perc;
}


// get translated option name - WPML integration
function mg_wpml_string($type, $original_val) {
	if(function_exists('icl_t')){
		$typename = ($type == 'img_gallery') ? 'Image Gallery' : ucfirst($type);
		$index = $typename.' Options - '.$original_val;
		
		return icl_t('Media Grid - Item Options', $index, $original_val);
	}
	else{
		return $original_val;
	}
}


// print type options fields
function mg_get_type_opt_fields($type, $post) {
	if(!get_option('mg_'.$type.'_opt')) {return false;}
	
	$copt = '
	<h4>Custom Options</h4>
	<table class="widefat lcwp_table lcwp_metabox_table mg_user_opt_table">';	
	
	foreach(get_option('mg_'.$type.'_opt') as $opt) {
		$val = get_post_meta($post->ID, 'mg_'.$type.'_'.strtolower(lcwp_stringToUrl($opt)), true);
		$copt .= '
		<tr>
          <td class="lcwp_label_td">'.mg_wpml_string($type, $opt).'</td>
          <td class="lcwp_field_td">
		  	<input type="text" name="mg_'.$type.'_'.strtolower(lcwp_stringToUrl($opt)).'" value="'.mg_sanitize_input($val).'" />
          </td>     
          <td><span class="info"></span></td>
        </tr>
		';
	}
	
	$copt .= '</table>';
	return $copt;
}


// metabox types options
function mg_types_meta_opt($type) {
	
	// img slider
	if($type == 'img_gallery') {
		$opt_arr = array(
			array(
				'type' 		=> 'empty',
				'validate'	=> array('index'=>'mg_slider_w_val', 'label'=>'Slider height value')
			),
			array(
				'type' 		=> 'empty',
				'validate'	=> array('index'=>'mg_slider_w_type', 'label'=>'Slider height type')
			),
			array(
				'label' 	=> __('Crop Method', 'mg_ml'),
				'name'		=> 'mg_slider_crop',
				'descr'		=> __('Select the cropping method for the shown image', 'mg_ml'),
				'type' 		=> 'select',
				'options'	=> mg_galleria_crop_methods(),
				'validate'	=> array('index'=>'mg_slider_crop', 'label'=>'Crop Method')
			),
			array(
				'label' 	=> __('Autoplay slideshow?', 'mg_ml'),
				'name'		=> 'mg_slider_autoplay',
				'descr'		=> __('If checked autoplay the slider slideshow', 'mg_ml'),
				'type' 		=> 'checkbox',
				'validate'	=> array('index'=>'mg_slider_autoplay', 'label'=>'Autoplay slideshow')
			),
			array(
				'label' 	=> __('Show thumbnails?', 'mg_ml'),
				'name'		=> 'mg_slider_thumbs',
				'descr'		=> __('Select if and how the thumbs will be shown', 'mg_ml'),
				'type' 		=> 'select',
				'options'	=> mg_galleria_thumb_opts(),
				'validate'	=> array('index'=>'mg_slider_thumbs', 'label'=>'Show thumbnails')
			),
			array(
				'label' 	=> __('Display captions?', 'mg_ml'),
				'name'		=> 'mg_slider_captions',
				'descr'		=> __('If checked displays the captions in the slider', 'mg_ml'),
				'type' 		=> 'checkbox',
				'validate'	=> array('index'=>'mg_slider_captions', 'label'=>'Slider Captions')
			),
			array(
				'type' 		=> 'empty',
				'validate'	=> array('index'=>'mg_slider_img', 'label'=>'Slider Images')
			)
		);
	}
	
	// video
	elseif($type == 'video') {
		$opt_arr = array(
			array(
				'type' 		=> 'empty',
				'validate'	=> array('index'=>'mg_video_url', 'label'=>'Video URL')
			),
			array(
				'type' 		=> 'empty',
				'validate'	=> array('index'=>'mg_video_use_poster', 'label'=>'Use featured image as poster (HTML 5 video)')
			)
		);
	}
	
	// audio
	elseif($type == 'audio') {
		$opt_arr = array(
				 array(
				 	'type' 		=> 'empty',
					'validate'	=> array('index'=>'mg_audio_tracks', 'label'=>'Tracklist')
			)
		);	
	}
		
	// link
	elseif($type == 'link') {
		$opt_arr = array(
			array(
				'label' 	=> __('Link URL', 'mg_ml'),
				'name'		=> 'mg_link_url',
				'descr'		=> '',
				'type' 		=> 'text',
				'validate'	=> array('index'=>'mg_link_url', 'label'=>'Link URL')
			),
			array(
				'label' 	=> __('Link Target', 'mg_ml'),
				'name'		=> 'mg_link_target',
				'descr'		=> __('where the link will be opened', 'mg_ml'),
				'type' 		=> 'select',
				'options'	=> array('top' => __('In the same page', 'mg_ml'), 'blank' => __('In a new page', 'mg_ml')),
				'validate'	=> array('index'=>'mg_link_target', 'label'=>'Link target')
			),
			array(
				'label' 	=> __('Use nofollow?', 'mg_ml'),
				'name'		=> 'mg_link_nofollow',
				'descr'		=> __('if enabled, use the rel="nofollow"', 'mg_ml'),
				'type' 		=> 'select',
				'options'	=> array('0' => __('No', 'mg_ml'), '1' => __('Yes', 'mg_ml')),
				'validate'	=> array('index'=>'mg_link_nofollow', 'label'=>'Link nofollow')
			)
		);
	}
	
	else {return false;}
	
	return $opt_arr;	
}


// metabox option generator 
function mg_meta_opt_generator($type, $post) {
	$opt_arr = mg_types_meta_opt($type);
	$opt_data = '<table class="widefat lcwp_table lcwp_metabox_table">';
	
	foreach($opt_arr as $opt) {
		if($opt['type'] != 'empty') {
			$val = get_post_meta($post->ID, $opt['name'], true);
			
			$opt_data .= '
			<tr>
			  <td class="lcwp_label_td">'.$opt['label'].'</td>
			  <td class="lcwp_field_td">';
			  
			if($opt['type'] == 'text') {  
				$opt_data .= '<input type="text" name="'.$opt['name'].'" value="'.$val.'" />';
			}
			
			elseif($opt['type'] == 'select') {
				$opt_data .= '<select data-placeholder="'. __('Select an option', 'mg_ml') .' .." name="'.$opt['name'].'" class="chzn-select" tabindex="2">';
				
				foreach($opt['options'] as $key=>$name) {
					($key == $val) ? $sel = 'selected="selected"' : $sel = '';
					$opt_data .= '<option value="'.$key.'" '.$sel.'>'.$name.'</option>';	
				}
				
				$opt_data .= '</select>';
			} 
			
			elseif($opt['type'] == 'checkbox') {
				($val) ? $sel = 'checked="checked"' : $sel = '';
				$opt_data .= '<input type="checkbox" name="'.$opt['name'].'" value="1" class="ip-checkbox" '.$sel.' />';	
			}
			  
			$opt_data .= ' 
			  </td>     
			  <td><span class="info">'.$opt['descr'].'</span></td>
			</tr>
			';
		}
	}
	
	return $opt_data . '</table>';
}


// get type options indexes from the main type
function mg_get_type_opt_indexes($type) {
	if($type == 'simple_img' || $type == 'link') {return false;}
	
	if($type == 'single_img') {$copt_id = 'image';}
	else {$copt_id = $type;}

	if(!get_option('mg_'.$copt_id.'_opt')) {return false;}
	
	$indexes = array();
	foreach(get_option('mg_'.$copt_id.'_opt') as $opt) {
		$indexes[] = 'mg_'.$copt_id.'_'.strtolower(lcwp_stringToUrl($opt));
	}
	
	return $indexes;	
}


// prepare the array of custom options not empty for an item
function mg_item_copts_array($type, $post_id) {
	if($type == 'single_img') {$type = 'image';}
	$copts = get_option('mg_'.$type.'_opt');
	
	$arr = array();
	if(is_array($copts)) {
		foreach($copts as $copt) {
			$val = get_post_meta($post_id, 'mg_'.$type.'_'.strtolower(lcwp_stringToUrl($copt)), true);
			
			if($val && $val != '') {
				$arr[$copt] = $val;	
			}
		}
	}
	return $arr;
}


// given the item main type slug - return the name
function item_slug_to_name($slug) {
	$vals = array(
		'simple_img' 	=> __('Single Image (static)', 'mg_ml'),
		'single_img' 	=> __('Single Image', 'mg_ml'),
		'img_gallery' 	=> __('Multiple Images (slider)', 'mg_ml'),
		'video' 		=> __('Video', 'mg_ml'),
		'audio'			=> __('Audio', 'mg_ml'),
		'link'			=> __('Link', 'mg_ml'),
		'lb_text'		=> __('Custom Content', 'mg_ml'),
		'spacer'		=> __('Spacer', 'mg_ml')
	);
	return $vals[$slug];	
}


// giving an array of items categories, return the published items
function mg_get_cat_items($cat) {
	if(!$cat) {return false;}
	
	$args = array(
		'posts_per_page'  => -1,
		'post_type'       => 'mg_items',
		'post_status'     => 'publish'
	);
	
	if($cat != 'all') {
		$term_data = get_term_by( 'id', $cat, 'mg_item_categories');	
		$args['mg_item_categories'] = $term_data->slug;		
	}	
	$items = get_posts($args);
	
	$items_list = array();
	foreach($items as $item) {
		$post_id = $item->ID;
		$img_id = get_post_thumbnail_id($post_id);
		$type = get_post_meta($post_id, 'mg_main_type', true);
		
		// show only items with featured image
		if(!empty($img_id) || $type == 'spacer') {
			$items_list[] = array(
				'id'	=> $post_id, 
				'title'	=> $item->post_title, 
				'type' 	=> $type,
				'width'	=> get_post_meta($post_id, 'mg_width', true),
				'height'=> get_post_meta($post_id, 'mg_height', true),
				'img' => $img_id
			);
		}
	}
	return $items_list;
}


// given an array of post_id, retrieve the data for the builder
function mg_grid_builder_items_data($items) {
	if(!is_array($items) || count($items) == 0) {return false;}
	
	$items_data = array();
	foreach($items as $item_id) {	
		$items_data[] = array(
			'id'	=> $item_id, 
			'title'	=> get_the_title($item_id), 
			'type' 	=> get_post_meta($item_id, 'mg_main_type', true),
			'width'	=> get_post_meta($item_id, 'mg_width', true),
			'height'=> get_post_meta($item_id, 'mg_height', true)
		);
	}
	
	return $items_data;
}


// get the images from the WP library
function mg_library_images($page = 1, $per_page = 15) {
	$query_images_args = array(
		'post_type' => 'attachment', 
		'post_mime_type' =>'image', 
		'post_status' => 'inherit', 
		'posts_per_page' => $per_page, 
		'paged' => $page
	);
	
	$query_images = new WP_Query( $query_images_args );
	$images = array();
	
	foreach ( $query_images->posts as $image) { 
		$images[] = $image->ID;
	}
	
	// global images number
	$img_num = $query_images->found_posts;
	
	// calculate the total
	$tot_pag = ceil($img_num / $per_page);
	
	// can show more?
	$shown = $per_page * $page;
	($shown >= $img_num) ? $more = false : $more = true; 
	
	return array('img' => $images, 'pag' => $page, 'tot_pag' =>$tot_pag, 'more' => $more, 'tot' => $img_num);
}


// get the audio files from the WP library
function mg_library_audio($page = 1, $per_page = 15) {
	$query_audio_args = array(
		'post_type' => 'attachment', 'post_mime_type' =>'audio', 'post_status' => 'inherit', 'posts_per_page' => $per_page, 'paged' => $page
	);
	
	$query_audio = new WP_Query( $query_audio_args );
	$tracks = array();
	
	foreach ( $query_audio->posts as $audio) { 
		$tracks[] = array(
			'id'	=> $audio->ID,
			'url' 	=> $audio->guid, 
			'title' => $audio->post_title
		);
	}
	
	// global images number
	$track_num = $query_audio->found_posts;
	
	// calculate the total
	$tot_pag = ceil($track_num / $per_page);
	
	// can show more?
	$shown = $per_page * $page;
	($shown >= $track_num) ? $more = false : $more = true; 
	
	return array('tracks' => $tracks, 'pag' => $page, 'tot_pag' =>$tot_pag  ,'more' => $more, 'tot' => $track_num);
}


// given an array of selected images or tracks - returns only existing ones
function mg_existing_sel($media) {
	if(is_array($media)) {
		$new_array = array();
		
		foreach($media as $media_id) {
			if( get_the_title($media_id)) {	
				$new_array[] = $media_id;
			}
		}
		
		if(count($new_array) == 0) {return false;}
		else {return $new_array;}
	}
	else {return false;}	
}


// return the grid categories by the chosen order
function mg_order_grid_cats($terms) {
	$ordered = array();
	
	foreach($terms as $term_id) {
		$ord = (int)get_option("mg_cat_".$term_id."_order");
		
		// check the final order
		while( isset($ordered[$ord]) ) {
			$ord++;	
		}
		
		$ordered[$ord] = $term_id;
	}
	
	ksort($ordered, SORT_NUMERIC);
	return $ordered;	
}


// get the grid terms data
function mg_grid_terms_data($grid_id, $return = 'html') {
	$terms = get_option('mg_grid_'.$grid_id.'_cats');
	
	if(!$terms) { return false; }
	else {
		$terms = mg_order_grid_cats($terms);
		$terms_data = array();
		
		$a = 0;
		foreach($terms as $term) {
			$term_data = get_term_by('id', $term, 'mg_item_categories');
			if(is_object($term_data)) {
				$terms_data[$a] = array('id' => $term, 'name' => $term_data->name, 'slug' => $term_data->slug); 		
				$a++;
			}
		}
		
		if($return != 'html') {return $terms_data;}
		else {
			$grid_terms_list = '<a class="mg_cats_selected mgf" rel="*">'.__('All', 'mg_ml').'</a>';
			$separator = (get_option('mg_use_old_filters')) ? '<span>/</span>' : '';
			
			foreach($terms_data as $term) {
				$grid_terms_list .= $separator.'<a rel="'.$term['slug'].'" class="mgf_id_'.$term['id'].' mgf">'.$term['name'].'</a>';	
			}
			
			return $grid_terms_list;
		}
	}
}


// get the terms of a grid item - return the CSS class
function mg_item_terms_classes($post_id) {
	$pid_classes = array();
	
	$pid_terms = wp_get_post_terms($post_id, 'mg_item_categories', array("fields" => "slugs"));
	foreach($pid_terms as $pid_term) { $pid_classes[] = 'mgc_'.$pid_term; }	
	
	return implode(' ', $pid_classes);	
}


// create the frontend css and js
function mg_create_frontend_css() {	
	ob_start();
	require(MG_DIR.'/frontend_css.php');
	
	$css = ob_get_clean();
	if(trim($css) != '') {
		if(!@file_put_contents(MG_DIR.'/css/custom.css', $css, LOCK_EX)) {$error = true;}
	}
	else {
		if(file_exists(MG_DIR.'/css/custom.css'))	{ unlink(MG_DIR.'/css/custom.css'); }
	}
	
	if(isset($error)) {return false;}
	else {return true;}
}


// custom excerpt
function mg_excerpt($string, $max) {
	$num = strlen($string);
	
	if($num > $max) {
		$string = substr($string, 0, $max) . '..';
	}
	
	return $string;
}


// get the upload directory (for WP MU)
function mg_wpmu_upload_dir() {
	$dirs = wp_upload_dir();
	$basedir = $dirs['basedir'] . '/YEAR/MONTH';
	 
	 
	return $basedir;	
}


///////////////////////////////////////////////////////////////////

// create and manage items overlay - with overlay manager add-on integration
class mg_overlay_manager {
	private $preview_mode = false;
	private $title_under = false;
	private $overlay;
	
	// image overlay 
	public $ol_txt_part = '<div class="cell_type"><span class="mg_overlay_tit">%MG-TITLE-OL%</span></div>';
	public $ol_code = '
		<div class="overlay"></div>
		<div class="cell_more"><span></span></div>
	';
	
	// title under
	public $tit_under_code = '<span>%MG-TITLE-OL%</span>';
	
	// image effect attribute
	public $img_fx_attr = '';
	
	// txt visibility trick - classes
	public $txt_vis_class = false;
	
	
	// handle grid global vars
	function __construct($ol_to_use, $title_under, $preview_mode = false) {
		$this->preview_mode = $preview_mode;
		$this->title_under = ($title_under == 1) ? true : false;
		
		// get the add-on code
		if (!defined('MGOM_DIR') || $ol_to_use == 'default' || !filter_var($ol_to_use, FILTER_VALIDATE_INT)) {
			if(defined('MGOM_DIR')) {
				$global_ol = get_option('mg_default_overlay');
				$overlay = (empty($global_ol)) ? 'default' : (int)$global_ol;
			}
			else {$overlay = 'default';}
		} 
		else {
			$overlay = (!defined('MGOM_DIR')) ? 'default' : (int)$ol_to_use;	
		}
		$this->overlay = $overlay;
		
		if($overlay != 'default') {
			$this->tit_under_code = '<div class="mg_title_under">%MG-TITLE-OL%</div>';
			$this->get_om_code($overlay);
		}
	}
	
	
	// get the add-on overlay code
	private function get_om_code($overlay_id) {
			
		if(function_exists('mgom_ol_frontend_code')) {
			$code = mgom_ol_frontend_code($overlay_id, $this->title_under);	

			$this->ol_code = $code['graphic'];
			$this->img_fx_attr = $code['img_fx_elem'];
			$this->txt_vis_class = $code['txt_vis_class'];
			
			if($this->title_under) {
				$this->tit_under_code = $code['txt'];
			} else {
				$this->ol_txt_part = $code['txt'];	
			}
		} 
	}
	
	
	// get the image overlay code
	public function get_img_ol($item_id) {
		
		// if not txt under - execute the text code	
		if(!$this->title_under) {
			$title = ($this->preview_mode) ? 'Lorem ipsum' : get_the_title($item_id);
			$txt_part =	str_replace('%MG-TITLE-OL%', $title, $this->ol_txt_part);
			
			if(strpos($txt_part, '%MG-DESCR-OL%') !== false) {
				if($this->preview_mode) {
					$descr = 'dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquid ex ea commodi consequat. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
				} 
				else {
					$descr = do_shortcode( get_post_field('post_excerpt', $item_id));
					if(empty($descr)) {$descr = strip_shortcodes( strip_tags( get_post_field('post_content', $item_id), '<p><a><br>'));}
				}
				
				$txt_part = str_replace('%MG-DESCR-OL%', $descr, $txt_part);
			}
		} else {
			$txt_part = '';	
		}
		
		return $this->ol_code . $txt_part;
	}
	
	
	// get the image overlay code
	public function get_txt_under($item_id) {
		$txt_part =	str_replace('%MG-TITLE-OL%', get_the_title($item_id), $this->tit_under_code);
		
		if(strpos($txt_part, '%MG-DESCR-OL%') !== false) {
			$descr = do_shortcode( get_post_field('post_excerpt', $item_id));
			if(empty($descr)) {$descr = strip_shortcodes( strip_tags( get_post_field('post_content', $item_id)));}
			
			$txt_part = str_replace('%MG-DESCR-OL%', $descr, $txt_part);
		}

		return '<div class="mg_title_under">'. $txt_part .'</div>';
	}
}


///////////////////////////////////////////////////////////////////

// predefined grid styles 
function mg_predefined_styles($style = '') {
	$styles = array(
		// LIGHTS
		'Light - Standard' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 4,
			'mg_cells_radius' => 1,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			
			'mg_cells_border_color' => '#CCCCCC',
			'mg_img_border_color' => 'rgb(255, 255, 255)',
			'mg_img_border_opacity' => 100,
			'mg_main_overlay_color' => '#FFFFFF',
			'mg_main_overlay_opacity' => 80,
			'mg_second_overlay_color' => '#555555',
			'mg_icons_col' => 'w',
			'mg_overlay_title_color' => '#222222',
			
			'mg_item_overlay_color' => '#FFFFFF',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#FFFFFF',
			'mg_item_txt_color' => '#222222',
			'mg_item_icons' => 'dark',
			'preview' => 'light_standard.jpg'
		),
	
		'Light - Minimal' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 3,
			'mg_cells_radius' => 2,
			'mg_cells_border' => 1,
			'mg_cells_shadow' => 0,
			'mg_item_radius' => 2,
			
			'mg_cells_border_color' => '#CECECE',
			'mg_img_border_color' => 'rgb(255, 255, 255)',
			'mg_img_border_opacity' => 0,
			'mg_main_overlay_color' => '#FFFFFF',
			'mg_main_overlay_opacity' => 80,
			'mg_second_overlay_color' => '#555555',
			'mg_icons_col' => 'w',
			'mg_overlay_title_color' => '#222222',
			
			'mg_item_overlay_color' => '#FFFFFF',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#FFFFFF',
			'mg_item_txt_color' => '#222222',
			'mg_item_icons' => 'dark',
			'preview' => 'light_minimal.jpg'
		),
		
		'Light - No Border' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 0,
			'mg_cells_radius' => 2,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			
			'mg_cells_border_color' => '#CCCCCC',
			'mg_img_border_color' => 'rgb(255, 255, 255)',
			'mg_img_border_opacity' => 0,
			'mg_main_overlay_color' => '#FFFFFF',
			'mg_main_overlay_opacity' => 80,
			'mg_second_overlay_color' => '#555555',
			'mg_icons_col' => 'tw',
			'mg_overlay_title_color' => '#222222',
			
			'mg_item_overlay_color' => '#FFFFFF',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#FFFFFF',
			'mg_item_txt_color' => '#222222',
			'mg_item_icons' => 'dark',
			'preview' => 'light_noborder.jpg'
		),
		
		'Light - Photo Wall' => array(
			'mg_cells_margin' => 0,
			'mg_cells_img_border' => 0,
			'mg_cells_radius' => 0,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			
			'mg_cells_border_color' => '#CCCCCC',
			'mg_img_border_color' => 'rgb(255, 255, 255)',
			'mg_img_border_opacity' => 0,
			'mg_main_overlay_color' => '#FFFFFF',
			'mg_main_overlay_opacity' => 80,
			'mg_second_overlay_color' => '#555555',
			'mg_icons_col' => 'tw',
			'mg_overlay_title_color' => '#222222',
			
			'mg_item_overlay_color' => '#FFFFFF',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#FFFFFF',
			'mg_item_txt_color' => '#222222',
			'mg_item_icons' => 'dark',
			'preview' => 'light_photowall.jpg'
		),
		
		'Light - Title Under Items' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 3,
			'mg_cells_radius' => 2,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			
			'mg_cells_border_color' => '#CCCCCC',
			'mg_img_border_color' => 'rgb(255, 255, 255)',
			'mg_img_border_opacity' => 100,
			'mg_main_overlay_color' => '#dddddd',
			'mg_main_overlay_opacity' => 0,
			'mg_second_overlay_color' => '#ffffff',
			'mg_icons_col' => 'g',
			'mg_overlay_title_color' => '#222222',
			
			'mg_item_overlay_color' => '#FFFFFF',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#FFFFFF',
			'mg_item_txt_color' => '#222222',
			'mg_item_icons' => 'dark',
			'preview' => 'light_tit_under.jpg'
		),
	
		// DARKS
		'Dark - Standard' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 4,
			'mg_cells_radius' => 1,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			
			'mg_cells_border_color' => '#999999',
			'mg_img_border_color' => 'rgb(55, 55, 55)',
			'mg_img_border_opacity' => 80,
			'mg_main_overlay_color' => '#222222',
			'mg_main_overlay_opacity' => 90,
			'mg_second_overlay_color' => '#bbbbbb',
			'mg_icons_col' => 'g',
			'mg_overlay_title_color' => '#ffffff',
			
			'mg_item_overlay_color' => '#222222',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#343434',
			'mg_item_txt_color' => '#ffffff',
			'mg_item_icons' => 'light',
			'preview' => 'dark_standard.jpg'
		),
	
		'Dark - Minimal' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 4,
			'mg_cells_radius' => 2,
			'mg_cells_border' => 1,
			'mg_cells_shadow' => 0,
			'mg_item_radius' => 2,
			
			'mg_cells_border_color' => '#555555',
			'mg_img_border_color' => 'rgb(55, 55, 55)',
			'mg_img_border_opacity' => 0,
			'mg_main_overlay_color' => '#222222',
			'mg_main_overlay_opacity' => 90,
			'mg_second_overlay_color' => '#bbbbbb',
			'mg_icons_col' => 'g',
			'mg_overlay_title_color' => '#ffffff',
			
			'mg_item_overlay_color' => '#222222',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#343434',
			'mg_item_txt_color' => '#ffffff',
			'mg_item_icons' => 'light',
			'preview' => 'dark_minimal.jpg'
		),
		
		'Dark - No Border' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 0,
			'mg_cells_radius' => 2,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			
			'mg_cells_border_color' => '#999999',
			'mg_img_border_color' => 'rgb(55, 55, 55)',
			'mg_img_border_opacity' => 80,
			'mg_main_overlay_color' => '#222222',
			'mg_main_overlay_opacity' => 90,
			'mg_second_overlay_color' => '#bbbbbb',
			'mg_icons_col' => 'g',
			'mg_overlay_title_color' => '#ffffff',
			
			'mg_item_overlay_color' => '#222222',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#343434',
			'mg_item_txt_color' => '#ffffff',
			'mg_item_icons' => 'light',
			'preview' => 'dark_noborder.jpg'
		),
		
		'Dark - Photo Wall' => array(
			'mg_cells_margin' => 0,
			'mg_cells_img_border' => 0,
			'mg_cells_radius' => 0,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			
			'mg_cells_border_color' => '#999999',
			'mg_img_border_color' => 'rgb(55, 55, 55)',
			'mg_img_border_opacity' => 80,
			'mg_main_overlay_color' => '#222222',
			'mg_main_overlay_opacity' => 90,
			'mg_second_overlay_color' => '#bbbbbb',
			'mg_icons_col' => 'g',
			'mg_overlay_title_color' => '#ffffff',
			
			'mg_item_overlay_color' => '#222222',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#343434',
			'mg_item_txt_color' => '#ffffff',
			'mg_item_icons' => 'light',
			'preview' => 'dark_photowall.jpg'
		),
		
		'Dark - Title Under Items' => array(
			'mg_cells_margin' => 5,
			'mg_cells_img_border' => 3,
			'mg_cells_radius' => 2,
			'mg_cells_border' => 0,
			'mg_cells_shadow' => 1,
			'mg_item_radius' => 4,
			
			'mg_cells_border_color' => '#ffffff',
			'mg_img_border_color' => 'rgb(58, 58, 58)',
			'mg_img_border_opacity' => 100,
			'mg_main_overlay_color' => '#222222',
			'mg_main_overlay_opacity' => 0,
			'mg_second_overlay_color' => '#9b9b9b',
			'mg_icons_col' => 'g',
			'mg_overlay_title_color' => '#ffffff',
			
			'mg_item_overlay_color' => '#222222',
			'mg_item_overlay_opacity' => 80,
			'mg_item_bg_color' => '#343434',
			'mg_item_txt_color' => '#ffffff',
			'mg_item_icons' => 'light',
			'preview' => 'dark_tit_under.jpg'
		),
	);
		
		
	if($style == '') {return $styles;}
	else {return $styles[$style];}	
}

?>