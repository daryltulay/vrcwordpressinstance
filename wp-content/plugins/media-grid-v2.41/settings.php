<?php 
include_once(MG_DIR . '/functions.php');

// item types array
$types = mg_main_types();
?>

<div class="wrap lcwp_form">  
	<div class="icon32"><img src="<?php echo MG_URL.'/img/mg_icon.png'; ?>" alt="mediagrid" /><br/></div>
    <?php echo '<h2 class="lcwp_page_title" style="border: none;">' . __( 'Media Grid Settings', 'mg_ml') . "</h2>"; ?>  

    <?php
	// HANDLE DATA
	if(isset($_POST['lcwp_admin_submit'])) { 
		include(MG_DIR . '/classes/simple_form_validator.php');		
		
		$validator = new simple_fv;
		$indexes = array();
		
		$indexes[] = array('index'=>'mg_cells_margin', 'label'=>__( 'Cells Margin', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_cells_img_border', 'label'=>__( 'Image Border', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_cells_radius', 'label'=>__( 'Cells Border Radius', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_cells_border', 'label'=>__( 'Cells Outer Border', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_cells_shadow', 'label'=>__( 'Cells Shadow', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_maxwidth', 'label'=>__( 'Grid max width', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_thumb_q', 'label'=>__( 'Thumbnail quality', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_filters_align', 'label'=>__( 'Filters Alignment', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_use_old_filters', 'label'=>__( 'Use old filters style', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_item_width', 'label'=>__( 'Item percentage width', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_item_maxwidth', 'label'=>__( 'Item maximum width', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_item_radius', 'label'=>__( 'Item Border Radius', 'mg_ml' ), 'type'=>'int');		
		$indexes[] = array('index'=>'mg_lb_not_vert_center', 'label'=>__( 'Lightbox not vertically centered', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_modal_lb', 'label'=>__( 'Use Lightbox modal mode', 'mg_ml' ));	
		$indexes[] = array('index'=>'mg_lb_touchswipe', 'label'=>__( 'Use touchswipe in lightbox', 'mg_ml' ));	
		$indexes[] = array('index'=>'mg_audio_autoplay', 'label'=>__( 'Audio player autoplay', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_audio_tracklist', 'label'=>__( 'Display full Tracklistlist', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_video_autoplay', 'label'=>__( 'Video player autoplay', 'mg_ml' ));		
		$indexes[] = array('index'=>'mg_slider_style', 'label'=>__( 'Slider style', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_slider_fx', 'label'=>__( 'Slider transition effect', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_slider_fx_time', 'label'=>__( 'Slider transition duration', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_slider_interval', 'label'=>__( 'Slider - Slideshow interval', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_slider_main_w_val', 'label'=>__( 'Slider - Global width', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_slider_main_w_type', 'label'=>__( 'Slider - Global width type', 'mg_ml' ), );		
		$indexes[] = array('index'=>'mg_disable_rclick', 'label'=>__( 'Disable right click', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_facebook', 'label'=>__( 'Facebook Button', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_twitter', 'label'=>__( 'Twitter Button', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_pinterest', 'label'=>__( 'Pinterest Button', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_googleplus', 'label'=>__( 'Google+ Button', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_preview_pag', 'label'=>__( 'Preview container', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_disable_dl', 'label'=>__( 'Disable Deeplinking', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_use_timthumb', 'label'=>__( 'Use TimThumb', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_js_head', 'label'=>__( 'Javascript in Header', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_enable_ajax', 'label'=>__( 'Enable Ajax Support', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_old_js_mode', 'label'=>__( 'Enable old jQuery compatibility', 'mg_ml' ));
		
		$indexes[] = array('index'=>'mg_cells_border_color', 'label'=>__( 'Cells border color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_img_border_color', 'label'=>__( 'Image Border Color', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_img_border_opacity', 'label'=>__( 'Image Border Opacity', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_main_overlay_color', 'label'=>__( 'Main Overlay Color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_main_overlay_opacity', 'label'=>__( 'Main Overlay Opacity', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_second_overlay_color', 'label'=>__( 'Second Overlay Color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_icons_col', 'label'=>__( 'Icons Color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_overlay_title_color', 'label'=>__( 'Second Overlay Color', 'mg_ml' ), 'type'=>'hex');

		$indexes[] = array('index'=>'mg_filters_txt_color', 'label'=>__( 'Filters Text Color', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_bg_color', 'label'=>__( 'Filters Background Color', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_border_color', 'label'=>__( 'Filters Border Color', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_txt_color_h', 'label'=>__( 'Filters Text Color - hover status', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_bg_color_h', 'label'=>__( 'Filters Background Color - hover status', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_border_color_h', 'label'=>__( 'Filters Border Color - hover status', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_txt_color_sel', 'label'=>__( 'Filters Text Color - selected status', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_bg_color_sel', 'label'=>__( 'Filters Background Color - selected status', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_border_color_sel', 'label'=>__( 'Filters Border Color - selected status', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_filters_radius', 'label'=>__( 'Filter Border Radius', 'mg_ml' ), 'type'=>'int');

		$indexes[] = array('index'=>'mg_item_overlay_color', 'label'=>__( 'Lightbox Overlay Color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_item_overlay_opacity', 'label'=>__( 'Lightbox Overlay Opacity', 'mg_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'mg_item_overlay_pattern', 'label'=>__( 'Lightbox Overlay Pattern', 'mg_ml' ));
		$indexes[] = array('index'=>'mg_item_bg_color', 'label'=>__( 'Item Background Color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_item_txt_color', 'label'=>__( 'Item Text Color', 'mg_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'mg_item_icons', 'label'=>__( 'Item Icons', 'mg_ml' ));
		
		if(is_multisite() && get_option('mg_use_timthumb')) {
			$indexes[] = array('index'=>'mg_wpmu_path', 'label'=>__('JS for old jQuery', 'mg_ml'), 'required'=>true);		
		}
		
		//// overlay manager add-on ////////
		if(defined('MGOM_DIR')) {
			$indexes[] = array('index'=>'mg_default_overlay', 'label'=>__( 'Default Overlay', 'mg_ml' ));
		}
		////////////////////////////////////
		
		foreach($types as $type => $name) {
			$indexes[] = array('index'=>'mg_'.$type.'_opt', 'label' => $name.' '.__('Options', 'mg_ml'), 'max_len'=>150);	
		}
		
		$indexes[] = array('index'=>'mg_custom_css', 'label'=>__( 'Custom CSS', 'mg_ml' ));
		
		$validator->formHandle($indexes);
		$fdata = $validator->form_val;
		
		
		// opt builder custom validation
		foreach($types as $type => $name) {
			if($fdata['mg_'.$type.'_opt']) {
				$a = 0;
				foreach($fdata['mg_'.$type.'_opt'] as $opt_val) {
					if(trim($opt_val) == '') {unset($fdata['mg_'.$type.'_opt'][$a]);}
					$a++;
				}
				
				if( count(array_unique($fdata['mg_'.$type.'_opt'])) < count($fdata['mg_'.$type.'_opt']) ) {
					$validator->custom_error[$name.' '.__('Options', 'mg_ml')] = __('There are duplicate values', 'mg_ml');
				}
			}
		}
		
		$error = $validator->getErrors();
		
		if($error) {echo '<div class="error"><p>'.$error.'</p></div>';}
		else {
			// clean data and save options
			foreach($fdata as $key=>$val) {
				if(!is_array($val)) {
					$fdata[$key] = stripslashes($val);
				}
				else {
					$fdata[$key] = array();
					foreach($val as $arr_val) {$fdata[$key][] = stripslashes($arr_val);}
				}
				
				if(!$fdata[$key]) {delete_option($key);}
				else {
					if(!get_option($key)) { add_option($key, '255', '', 'yes'); }
					update_option($key, $fdata[$key]);	
				}
			}
			
			// create frontend.css else print error
			if(!get_option('mg_inline_css')) {
				if(!mg_create_frontend_css()) {
					if(!get_option('mg_inline_css')) { add_option('mg_inline_css', '255', '', 'yes'); }
					update_option('mg_inline_css', 1);	
					
					echo '<div class="updated"><p>'. __('An error occurred during dynamic CSS creation. The code will be used inline anyway', 'mg_ml') .'</p></div>';
				}
				else {delete_option('mg_inline_css');}
			}
			
			echo '<div class="updated"><p><strong>'. __('Options saved.', 'mg_ml') .'</strong></p></div>';
		}
	}
	
	else {  
		// Normal page display
		$fdata['mg_cells_margin'] = get_option('mg_cells_margin');  
		$fdata['mg_cells_img_border'] = get_option('mg_cells_img_border');  
		$fdata['mg_cells_radius'] = get_option('mg_cells_radius'); 
		$fdata['mg_cells_border'] = get_option('mg_cells_border'); 
		$fdata['mg_cells_shadow'] = get_option('mg_cells_shadow'); 
		$fdata['mg_maxwidth'] = get_option('mg_maxwidth', 1100); 
		$fdata['mg_thumb_q'] = get_option('mg_thumb_q');
		$fdata['mg_filters_align'] = get_option('mg_filters_align');
		$fdata['mg_use_old_filters'] = get_option('mg_use_old_filters');
		$fdata['mg_item_width'] = get_option('mg_item_width'); 
		$fdata['mg_item_maxwidth'] = get_option('mg_item_maxwidth');
		$fdata['mg_item_radius'] = get_option('mg_item_radius');
		$fdata['mg_lb_not_vert_center'] = get_option('mg_lb_not_vert_center');	
		$fdata['mg_modal_lb'] = get_option('mg_modal_lb'); 
		$fdata['mg_lb_touchswipe'] = get_option('mg_lb_touchswipe');	
		$fdata['mg_audio_autoplay'] = get_option('mg_audio_autoplay');
		$fdata['mg_audio_tracklist'] = get_option('mg_audio_tracklist');
		$fdata['mg_video_autoplay'] = get_option('mg_video_autoplay');		
		$fdata['mg_slider_style'] = get_option('mg_slider_style');
		$fdata['mg_slider_fx'] = get_option('mg_slider_fx', 'fadeslide');
		$fdata['mg_slider_fx_time'] = get_option('mg_slider_fx_time', 400);
		$fdata['mg_slider_interval'] = get_option('mg_slider_interval', 3000);
		$fdata['mg_slider_main_w_val'] = get_option('mg_slider_main_w_val', 55);
		$fdata['mg_slider_main_w_type'] = get_option('mg_slider_main_w_type', '%');	
		$fdata['mg_disable_rclick'] = get_option('mg_disable_rclick');
		$fdata['mg_facebook'] = get_option('mg_facebook');
		$fdata['mg_twitter'] = get_option('mg_twitter');  
		$fdata['mg_pinterest'] = get_option('mg_pinterest'); 
		$fdata['mg_googleplus'] = get_option('mg_googleplus'); 
		$fdata['mg_preview_pag'] = get_option('mg_preview_pag'); 
		$fdata['mg_disable_dl'] = get_option('mg_disable_dl'); 
		$fdata['mg_use_timthumb'] = get_option('mg_use_timthumb'); 
		$fdata['mg_js_head'] = get_option('mg_js_head'); 
		$fdata['mg_enable_ajax'] = get_option('mg_enable_ajax'); 
		$fdata['mg_old_js_mode'] = get_option('mg_old_js_mode'); 
		$fdata['mg_wpmu_path'] = get_option('mg_wpmu_path'); 
		
		$fdata['mg_cells_border_color'] = get_option('mg_cells_border_color'); 
		$fdata['mg_img_border_color'] = get_option('mg_img_border_color');  
		$fdata['mg_img_border_opacity'] = get_option('mg_img_border_opacity'); 
		$fdata['mg_main_overlay_color'] = get_option('mg_main_overlay_color'); 
		$fdata['mg_main_overlay_opacity'] = get_option('mg_main_overlay_opacity'); 
		$fdata['mg_second_overlay_color'] = get_option('mg_second_overlay_color');
		$fdata['mg_icons_col'] = get_option('mg_icons_col', '#fff'); 
		$fdata['mg_overlay_title_color'] = get_option('mg_overlay_title_color');
		
		$fdata['mg_filters_txt_color'] = get_option('mg_filters_txt_color', '#444444'); 
		$fdata['mg_filters_bg_color'] = get_option('mg_filters_bg_color', '#ffffff');
		$fdata['mg_filters_border_color'] = get_option('mg_filters_border_color', '#999999'); 
		$fdata['mg_filters_txt_color_h'] = get_option('mg_filters_txt_color_h', '#666666'); 
		$fdata['mg_filters_bg_color_h'] = get_option('mg_filters_bg_color_h', '#ffffff'); 
		$fdata['mg_filters_border_color_h'] = get_option('mg_filters_border_color_h', '#666666');
		$fdata['mg_filters_txt_color_sel'] = get_option('mg_filters_txt_color_sel', '#222222'); 
		$fdata['mg_filters_bg_color_sel'] = get_option('mg_filters_bg_color_sel', '#ffffff'); 
		$fdata['mg_filters_border_color_sel'] = get_option('mg_filters_border_color_sel', '#555555');
		$fdata['mg_filters_radius'] = get_option('mg_filters_radius', 2); 
		
		$fdata['mg_item_overlay_color'] = get_option('mg_item_overlay_color'); 
		$fdata['mg_item_overlay_opacity'] = get_option('mg_item_overlay_opacity'); 
		$fdata['mg_item_overlay_pattern'] = get_option('mg_item_overlay_pattern'); 
		$fdata['mg_item_bg_color'] = get_option('mg_item_bg_color'); 
		$fdata['mg_item_txt_color'] = get_option('mg_item_txt_color');
		$fdata['mg_item_icons'] = get_option('mg_item_icons');
		
		$fdata['mg_custom_css'] = get_option('mg_custom_css'); 
		
		//// overlay manager add-on
		if(defined('MGOM_DIR')) {$fdata['mg_default_overlay'] = get_option('mg_default_overlay');}
		//////
		
		foreach($types as $type => $name) {
			$fdata['mg_'.$type.'_opt'] = get_option('mg_'.$type.'_opt'); 
		}
		
		// fix for secondary overlay color v2.3 to v2.4
		if(!preg_match('/^#[a-f0-9]{6}$/i', $fdata['mg_icons_col']) && !isset($_POST['mg_icons_col'])) {$fdata['mg_icons_col'] = '#ffffff';}
	}  
	?>


	<br/>
    <div id="tabs">
    <form name="lcwp_admin" method="post" class="form-wrap" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    	
    <ul class="tabNavigation">
    	<li><a href="#layout_opt"><?php _e('Main Options', 'mg_ml') ?></a></li>
        <li><a href="#color_opt"><?php _e('Colors', 'mg_ml') ?></a></li>
        <li><a href="#opt_builder"><?php _e('Items Options', 'mg_ml') ?></a></li>
        <li><a href="#advanced"><?php _e('Custom CSS', 'mg_ml') ?></a></li>
    </ul>    
        
    
    <div id="layout_opt"> 
    	<h3><?php _e("Predefined Styles", 'mg_ml'); ?></h3>
        
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Choose a style", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e('Select a style', 'mg_ml') ?> .." name="mg_pred_styles" id="mg_pred_styles" class="chzn-select" tabindex="2">
                	<option value="" selected="selected"></option>
                  <?php 
                  $styles = mg_predefined_styles();
                  foreach($styles as $style => $val) { 
				  	echo '<option value="'.$style.'">'.$style.'</option>'; 
				  }
                  ?>
                </select>
            </td>
            <td>
            	<input type="button" name="mg_set_style" id="mg_set_style" value="<?php _e('Set', 'mg_ml') ?>" class="button-secondary" />
            </td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Preview", 'mg_ml'); ?></td>
            <td class="lcwp_field_td" colspan="2">
            	<?php
				$styles = mg_predefined_styles();
                foreach($styles as $style => $val) { 
				  echo '<img src="'.MG_URL.'/img/pred_styles_demo/'.$val['preview'].'" class="mg_styles_preview" alt="'.$style.'" style="display: none;" />';	
				}
				?>
            </td>
          </tr>
        </table>
        
       
        <h3><?php _e("Grid Layout", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Grid Cells Margin", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="25" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_cells_margin']; ?>" name="mg_cells_margin" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the space between cells', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Image Border Size", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_cells_img_border']; ?>" name="mg_cells_img_border" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the cells border size', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Cells Border Radius", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<div class="lcwp_slider" step="1" max="25" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_cells_radius']; ?>" name="mg_cells_radius" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the cells border radius', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the outer cell border?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_cells_border'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_cells_border" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked displays the cells external border', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the Cell Shadow?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_cells_shadow'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_cells_shadow" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked displays a soft shadow around cells', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Grid max width", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="20" max="1960" min="860"></div>
                <?php if((int)$fdata['mg_maxwidth'] == 0) {$fdata['mg_maxwidth'] = 960;} ?>
                <input type="text" value="<?php echo(int)$fdata['mg_maxwidth']; ?>" name="mg_maxwidth" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the maximum width of the grid (used only for thumbnails, default: 960)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Thumbnails quality", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="100" min="30"></div>
                <?php if((int)$fdata['mg_thumb_q'] == 0) {$fdata['mg_thumb_q'] = 85;} ?>
                <input type="text" value="<?php echo(int)$fdata['mg_thumb_q']; ?>" name="mg_thumb_q" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e('Set the thumbnail quality. Low value = lighter but fuzzier images (default: 85%)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Filters Alignment", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="mg_filters_align" class="chzn-select" data-placeholder="<?php _e("Select a style", 'mg_ml'); ?> .." tabindex="2">
                  <option value="left">Left</option>
                  <option value="center" <?php if($fdata['mg_filters_align'] == 'center') {echo 'selected="selected"';} ?>>Center</option>
                  <option value="right" <?php if($fdata['mg_filters_align'] == 'right') {echo 'selected="selected"';} ?>>Right</option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the filters alignment", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use the old filters style?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_use_old_filters'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_use_old_filters" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked use the old Media Grid filters style', 'mg_ml') ?></span></td>
          </tr>
          
          <?php
          //// overlay manager add-on //////////////
		  //////////////////////////////////////////
		  if(defined('MGOM_DIR')) : ?>
          <tr>
            <td class="lcwp_label_td"><?php _e("Default Overlay", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="mg_default_overlay" class="chzn-select" data-placeholder="<?php _e("Select an overlay", 'mg_ml'); ?> .." tabindex="2">
                  <option value="">(<?php _e('original one', 'mg_ml') ?>)</option>
                  
                  <?php
				  $overlays = get_terms('mgom_overlays', 'hide_empty=0');
				  foreach($overlays as $ol) {
						$sel = ($ol->term_id == $fdata['mg_default_overlay']) ? 'selected="selected"' : '';
						echo '<option value="'.$ol->term_id.'" '.$sel.'>'.$ol->name.'</option>'; 
				  }
				  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Choose the default overlay to apply", 'mg_ml'); ?> - overlay manager add-on</span></td>
          </tr>
		  <?php endif;
          //////////////////////////////////////////
          ?>
        </table> 
        
        <h3><?php _e("Item's Lightbox", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Item Container Width", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="100" min="30"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_item_width']; ?>" name="mg_item_width" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e('Width percentage of the opened items in relation to the screen (default: 70)', 'mg_ml') ?></span></td>
          </tr> 
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Item Container Maximum Width", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" value="<?php echo (int)$fdata['mg_item_maxwidth']; ?>" name="mg_item_maxwidth" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Maximum width in pixels of the opened items (default: 960)', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Item Container Border Radius", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_item_radius']; ?>" name="mg_item_radius" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the border radius for the item container', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Disable the lightbox vertical centering?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_lb_not_vert_center'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_lb_not_vert_center" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked, disable the system to center the lightbox vertically', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use lightbox as modal?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_modal_lb'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_modal_lb" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('If checked, only the close button will close the lightbox', 'mg_ml') ?></span>
            </td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Use touchSwipe?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_lb_touchswipe'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_lb_touchswipe" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked, use the touchSwipe navigation on mobile devices', 'mg_ml') ?></span></td>
          </tr>
        </table> 
        
       	<h3><?php _e("Audio & video players", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Autoplay tracks?" ); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_audio_autoplay'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_audio_autoplay" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked autoplays the tracks in the audio player', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the full tracklist?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_audio_tracklist'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_audio_tracklist" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked shows the full tracklist in the player', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Autoplay videos?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_video_autoplay'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_video_autoplay" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked autoplays videos', 'mg_ml') ?></span></td>
          </tr>  
        </table>
        
        <h3><?php _e("Slider", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Style", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="mg_slider_style" class="chzn-select" data-placeholder="<?php _e("Select a style", 'mg_ml'); ?> .." tabindex="2">
                  <option value="light">Light</option>
                  <option value="dark" <?php if($fdata['mg_slider_style'] == 'dark') {echo 'selected="selected"';} ?>>Dark</option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the slider style", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Height", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" class="lcwp_slider_input" name="mg_slider_main_w_val" value="<?php echo $fdata['mg_slider_main_w_val']; ?>" maxlength="3">
                <select name="mg_slider_main_w_type" style="width: 50px; margin-left: -5px;">
                  <option value="%">%</option>
                  <option value="px" <?php if($fdata['mg_slider_main_w_type'] == 'px') {echo 'selected="selected"';} ?>>px</option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Default slider height (% is related to the width)", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Transition effect", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="mg_slider_fx" class="chzn-select" data-placeholder="<?php _e("Select a transition", 'mg_ml'); ?> .." tabindex="2">
                  <?php	
                  foreach(mg_galleria_fx() as $key => $val) {
					  ($key == $fdata['mg_slider_fx']) ? $sel = 'selected="selected"' : $sel = '';
					  echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
                  }
                  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Select the transition effect between slides", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Transition duration", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="50" max="1000" min="100"></div>
                <input type="text" value="<?php echo $fdata['mg_slider_fx_time']; ?>" name="mg_slider_fx_time" class="lcwp_slider_input" />
                <span>ms</span>
            </td>
            <td><span class="info"><?php _e("How much time the transition takes (in milliseconds - default 400)", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Slideshow interval", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="500" max="8000" min="1000"></div>
                <input type="text" value="<?php echo $fdata['mg_slider_interval']; ?>" name="mg_slider_interval" class="lcwp_slider_input" />
                <span>ms</span>
            </td>
            <td><span class="info"><?php _e("How long each slide will be shown (in milliseconds - default 3000)", 'mg_ml'); ?></span></td>
          </tr>
        </table>
        
        <h3><?php _e("Image Protection", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Disable right click" ); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_disable_rclick'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_disable_rclick" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('Check to disable right click on grid images', 'mg_ml') ?></span></td>
          </tr>
        </table>    
        
        <h3><?php _e("Socials", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the Facebook button?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_facebook'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_facebook" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked displays the Facebook button in opened items', 'mg_ml') ?></span></td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the Twitter button?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_twitter'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_twitter" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked displays the Twitter button in opened items', 'mg_ml') ?></span></td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the Pinterest button?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_pinterest'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_pinterest" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked displays the Pinterest button in opened items', 'mg_ml') ?></span></td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Display the Google+ button?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_googleplus'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_googleplus" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td><span class="info"><?php _e('If checked displays the Google+ button in opened items (<strong>only with deeplinking</strong>)', 'mg_ml') ?></span></td>
          </tr>
        </table> 
        
        <?php if(is_multisite() && get_option('mg_use_timthumb')) : ?>
            <h3><?php _e("Timthumb basepath", 'mg_ml'); ?> <small>(<?php _e('for MU installations', 'mg_ml') ?>)</small></h3>
            <table class="widefat lcwp_table">
              <tr>
                <td class="lcwp_label_td"><?php _e("Basepath of the WP MU images", 'mg_ml'); ?></td>
                <td>
                    <?php if(!$fdata['mg_wpmu_path'] || trim($fdata['mg_wpmu_path']) == '') { $fdata['mg_wpmu_path'] = mg_wpmu_upload_dir();} ?>
                    <input type="text" value="<?php echo $fdata['mg_wpmu_path'] ?>" name="mg_wpmu_path" style="width: 90%;" />
                    
                    <p class="info" style="margin-top: 3px;">By default is: 
                    	<span style="font-family: Tahoma, Geneva, sans-serif; font-size: 13px; color: #727272;"><?php echo mg_wpmu_upload_dir(); ?></span>
                    </p>
                </td>
              </tr> 
            </table> 
        <?php endif; ?>    
        
        <h3><?php _e("Various", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Preview container", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select name="mg_preview_pag" class="chzn-select" data-placeholder="<?php _e("Select a page", 'gg_ml'); ?> .." tabindex="2">
                  <option value=""></option>
                  <?php
                  foreach(get_pages() as $pag) {
                      ($fdata['mg_preview_pag'] == $pag->ID) ? $selected = 'selected="selected"' : $selected = '';
                      echo '<option value="'.$pag->ID.'" '.$selected.'>'.$pag->post_title.'</option>';
                  }
                  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Choose the page to use as preview container", 'mg_ml'); ?></span></td>
          </tr>
        </table>  
        
        <h3><?php _e("Advanced", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Disable deeplinking?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_disable_dl'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_disable_dl" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('If checked, disable the deeplinking for lightbox and category filter', 'mg_ml') ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use TimThumb?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_use_timthumb'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_use_timthumb" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('If checked, use Timthumb instead of Easy WP Thumbs', 'mg_ml') ?></span>
            </td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Use javascript in the head?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_js_head'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_js_head" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('Put javascript in the website head, check it ONLY IF you notice some incompatibilities', 'mg_ml') ?></span>
            </td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Enable the AJAX support?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_enable_ajax'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_enable_ajax" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('Enable the support for AJAX-loaded grids', 'mg_ml') ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Enable the old jQuery mode?", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['mg_old_js_mode'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="mg_old_js_mode" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('Enable the support for old jQuery versions - use ONLY if you are using an older version than 1.7', 'mg_ml') ?></span>
            </td>
          </tr>
        </table>

        
        <?php if(!get_option('mg_use_timthumb')) {ewpt_wpf_form();} ?>
    </div>

	<div id="color_opt">
    	<h3><?php _e("Grid Items", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Cells Outer Border Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_cells_border_color']; ?>" name="mg_cells_border_color" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('The cells outer border color', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Image Border Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_img_border_color']; ?>" name="mg_img_border_color" />
            </td>
            <td><span class="info"><?php _e('The cells image border color', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Image Border Opacity", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="10" max="100" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_img_border_opacity']; ?>" name="mg_img_border_opacity" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e('Set the CSS3 image border opacity', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Main Overlay Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_main_overlay_color']; ?>" name="mg_main_overlay_color" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Color of the main overlay that appears on item mouseover', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Main Overlay Opacity", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="10" max="100" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_main_overlay_opacity']; ?>" name="mg_main_overlay_opacity" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e('Opacity of the main overlay that appears on item mouseover', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Secondary Overlay Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_second_overlay_color']; ?>" name="mg_second_overlay_color" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Color of the secondary overlay that appears on item mouseover', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Secondary Overlay Icons Color", 'mg_ml'); ?></td>
           	<td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_icons_col']; ?>" name="mg_icons_col" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Color of the icons in the secondary overlay', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Overlay Title Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_overlay_title_color']; ?>" name="mg_overlay_title_color" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Color of the item title that appear on the main overlay', 'mg_ml') ?></span></td>
          </tr>
        </table> 

		<h3><?php _e("Item Filters", 'mg_ml'); ?></h3>
		<table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Text Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_filters_txt_color']; ?>" name="mg_filters_txt_color" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Filters text color - default status', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Background Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_filters_bg_color']; ?>" name="mg_filters_bg_color" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Filters background color - default status', 'mg_ml') ?> <?php _e('(not for old style)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_filters_border_color']; ?>" name="mg_filters_border_color" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Filters border color - default status', 'mg_ml') ?> <?php _e('(not for old style)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Text Color (on mouse hover)", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_filters_txt_color_h']; ?>" name="mg_filters_txt_color_h" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Filters text color - mouse hover status', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Background Color (on mouse hover)", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_filters_bg_color_h']; ?>" name="mg_filters_bg_color_h" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Filters background color - mouse hover status', 'mg_ml') ?> <?php _e('(not for old style)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Color (on mouse hover)", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_filters_border_color_h']; ?>" name="mg_filters_border_color_h" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Filters border color - mouse hover status', 'mg_ml') ?> <?php _e('(not for old style)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Text Color (selected filter)", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_filters_txt_color_sel']; ?>" name="mg_filters_txt_color_sel" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Filters text color - selected status', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Background Color (selected filter)", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_filters_bg_color_sel']; ?>" name="mg_filters_bg_color_sel" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Filters background color - selected status', 'mg_ml') ?> <?php _e('(not for old style)', 'mg_ml') ?></span></td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Color (selected filter)", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_filters_border_color_sel']; ?>" name="mg_filters_border_color_sel" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Filters border color - selected status', 'mg_ml') ?> <?php _e('(not for old style)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Radius", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_filters_radius']; ?>" name="mg_filters_radius" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the border radius for each filter', 'mg_ml') ?> (<?php _e('not for old style', 'mg_ml') ?>)</span></td>
          </tr>
        </table>  
        
       	<h3><?php _e("Opened Item", 'mg_ml'); ?></h3>
		<table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Lightbox Overlay Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_item_overlay_color']; ?>" name="mg_item_overlay_color" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Color of the fullpage overlay when an item is opened', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Lightbox Overlay Opacity", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="10" max="100" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['mg_item_overlay_opacity']; ?>" name="mg_item_overlay_opacity" class="lcwp_slider_input" />
                <span>%</span>
            </td>
            <td><span class="info"><?php _e('Opacity of the fullpage overlay when an item is opened', 'mg_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Lightbox Overlay Pattern", 'mg_ml'); ?></td>
            <td class="lcwp_field_td" colspan="2">
            	<input type="hidden" value="<?php echo $fdata['mg_item_overlay_pattern']; ?>" name="mg_item_overlay_pattern" id="mg_item_overlay_pattern" />
            
            	<div class="mg_setting_pattern <?php if(!$fdata['mg_item_overlay_pattern'] || $fdata['mg_item_overlay_pattern'] == 'none') {echo 'mg_pattern_sel';} ?>" id="mgp_none"> no pattern </div>
                
                <?php 
				foreach(mg_patterns_list() as $pattern) {
					($fdata['mg_item_overlay_pattern'] == $pattern) ? $sel = 'mg_pattern_sel' : $sel = '';  
					echo '<div class="mg_setting_pattern '.$sel.'" id="mgp_'.$pattern.'" style="background: url('.MG_URL.'/img/patterns/'.$pattern.') repeat top left transparent;"></div>';		
				}
				?>
            </td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Item Container Background" ); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_item_bg_color']; ?>" name="mg_item_bg_color" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Color of the item background (default: #FFFFFF)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Item Container Text Color", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="color" value="<?php echo $fdata['mg_item_txt_color']; ?>" name="mg_item_txt_color" data-hex="true" />
            </td>
            <td><span class="info"><?php _e('Text color of the item (default: #222222)', 'mg_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Item Container Icons", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select data-placeholder="<?php _e('Select a style', 'mg_ml') ?> .." name="mg_item_icons" class="chzn-select" tabindex="2">
                  <option value="dark" <?php if($fdata['mg_item_icons'] == 'dark') {echo 'selected="selected"';} ?>><?php _e('Dark icons', 'mg_ml') ?></option>
                  <option value="light" <?php if($fdata['mg_item_icons'] == 'light') {echo 'selected="selected"';} ?>><?php _e('Light Icons', 'mg_ml') ?></option>
                </select>
            </td>
            <td><span class="info"><?php _e('Color of the item icons', 'mg_ml') ?></span></td>
          </tr>        
        </table>  
    </div>
    
    <div id="opt_builder">
    <?php 
	// WPML sync button
	if(function_exists('icl_register_string')) {
		echo '
		<p id="mg_wpml_opt_sync_wrap">
			<input type="button" value="'. __('Sync with WPML', 'mg_ml').'" class="button-secondary" />
			<span><em>'. __('Save the options before sync', 'mg_ml') .'</em></span>
		</p>';	
	}
	
	foreach($types as $type => $name) :
	?>
		<h3 style="border: none;">
			<?php echo $name.' '.__('Options', 'mg_ml') ?>
            <a id="opt_<?php echo $type; ?>" class="add_option add-opt-h3"><?php _e('Add option', 'mg_ml') ?></a>
        </h3>
        <table class="widefat lcwp_table" id="<?php echo $type; ?>_opt_table" style="width: 400px !important;">
          <thead>
          <tr>
          	<th><?php _e('Option Name', 'mg_ml') ?></th>
            <th></th>
          	<th style="width: 20px;"></th>
            <th style="width: 20px;"></th>
          </tr>
          </thead>
          <tbody>
          	<?php
			if(is_array($fdata['mg_'.$type.'_opt'])) {
				foreach($fdata['mg_'.$type.'_opt'] as $type_opt) {
					echo '
					<tr>
						<td class="lcwp_field_td">
							<input type="text" name="mg_'.$type.'_opt[]" value="'.mg_sanitize_input($type_opt).'" maxlenght="150" />
						</td>
						<td></td>
						<td><span class="lcwp_move_row"></span></td>
						<td><span class="lcwp_del_row"></span></td>
					</tr>
					';	
				}
			}
			?>
          </tbody>
        </table>

	<?php endforeach; ?>
    
    </div>
    
    <div id="advanced">    
        <h3><?php _e("Custom CSS", 'mg_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_field_td">
            	<textarea name="mg_custom_css" style="width: 100%" rows="18"><?php echo $fdata['mg_custom_css']; ?></textarea>
            </td>
          </tr>
        </table>
        
        <h3><?php _e("Elements Legend", 'mg_ml'); ?></h3> 
        <table class="widefat lcwp_table">  
          <tr>
            <td class="lcwp_label_td">.mg_filter</td>
            <td><span class="info">Grid filter container (each filter is a <xmp><a></xmp> element, each separator is a <xmp><span></xmp> element)</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_grid_wrap</td>
            <td><span class="info">Grid container</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_box</td>
            <td><span class="info">Single item box</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_overlay_tit</td>
            <td><span class="info">Main overlay title</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_title_under</td>
            <td><span class="info">Title under item</span></td>
          </tr>
          		
          <tr>
            <td class="lcwp_label_td">#mg_full_overlay_wrap</td>
            <td><span class="info">Opened Item - Full page overlay</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_item_load</td>
            <td><span class="info">Opened Item - Item loader during the opening</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">#mg_overlay_content</td>
            <td><span class="info">Opened Item - Item body</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">#mg_close</td>
            <td><span class="info">Opened Item - Close item command</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">#mg_nav</td>
            <td><span class="info">Opened Item - Item navigator container</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_item_title</td>
            <td><span class="info">Opened Item - Item title</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_item_text</td>
            <td><span class="info">Opened Item - Item text</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_cust_options</td>
            <td><span class="info">Opened Item - Item options container (each option is a <xmp><li></xmp> element)</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.mg_socials</td>
            <td><span class="info">Opened Item - Item socials container (each social is a <xmp><li></xmp> element)</span></td>
          </tr>
          
        </table> 
    </div> 
   
    <input type="submit" name="lcwp_admin_submit" value="<?php _e('Update Options', 'mg_ml' ) ?>" class="button-primary" />  
    
	</form>
    </div>
</div>  

<?php // SCRIPTS ?>
<script src="<?php echo MG_URL; ?>/js/functions.js" type="text/javascript"></script>
<script src="<?php echo MG_URL; ?>/js/chosen/chosen.jquery.min.js" type="text/javascript"></script> 
<script src="<?php echo MG_URL; ?>/js/iphone_checkbox/iphone-style-checkboxes.js" type="text/javascript"></script> 
<script src="<?php echo MG_URL; ?>/js/colorpicker/js/mColorPicker_small.js" type="text/javascript"></script>

<script type="text/javascript" charset="utf8" >
jQuery(document).ready(function($) {
	<?php 
	// WPML sync button
	if(function_exists('icl_register_string')) :
	?>
	jQuery('body').delegate('#mg_wpml_opt_sync_wrap input', 'click', function() {
		jQuery('#mg_wpml_opt_sync_wrap span').html('<div style="width: 30px;" class="lcwp_loading"></div>');
		
		var data = {action: 'mg_options_wpml_sync'};
		jQuery.post(ajaxurl, data, function(response) {
			var resp = jQuery.trim(response);
			
			if(resp == 'success') {jQuery('#mg_wpml_opt_sync_wrap span').html('<?php _e('Options synced succesfully', 'mg_ml'); ?>!');}
			else {jQuery('#mg_wpml_opt_sync_wrap span').html('<?php _e('Error syncing', 'mg_ml'); ?> ..');}
		});	
	});
	<?php endif; ?>
	
	// set a predefined style 
	jQuery('body').delegate('#mg_set_style', 'click', function() {
		var sel_style = jQuery('#mg_pred_styles').val();
		
		if(confirm('<?php _e('It will overwrite your current settings, continue?', 'mg_ml') ?>') && sel_style != '') {
			var data = {
				action: 'mg_set_predefined_style',
				style: sel_style
			};
			
			jQuery(this).parent().html('<div style="width: 30px; height: 30px;" class="lcwp_loading"></div>');
			
			jQuery.post(ajaxurl, data, function(response) {
				window.location.href = location.href;
			});	
		}
	});
	
	// predefined style  preview toggle
	jQuery('body').delegate('#mg_pred_styles', "change", function() {
		var sel = jQuery('#mg_pred_styles').val();
		
		jQuery('.mg_styles_preview').hide();
		jQuery('.mg_styles_preview').each(function() {
			if( jQuery(this).attr('alt') == sel ) {jQuery(this).fadeIn();}
		});
	});
	
	
	// select a pattern
	jQuery('body').delegate('.mg_setting_pattern', 'click', function() {
		var pattern = jQuery(this).attr('id').substr(4);
		
		jQuery('.mg_setting_pattern').removeClass('mg_pattern_sel');
		jQuery(this).addClass('mg_pattern_sel'); 
		
		jQuery('#mg_item_overlay_pattern').val(pattern);
	});
	
	// add options
	jQuery('.add_option').click(function(){
		var type_subj = jQuery(this).attr('id').substr(4);
		
		var optblock = '<tr>\
			<td class="lcwp_field_td"><input type="text" name="mg_'+type_subj+'_opt[]" maxlenght="150" /></td>\
			<td></td>\
		    <td><span class="lcwp_move_row"></span></td>\
			<td><span class="lcwp_del_row"></span></td>\
		</tr>';

		jQuery('#'+type_subj + '_opt_table tbody').append(optblock);
	});
	
	// remove opt 
	jQuery('body').delegate('.lcwp_del_row', "click", function() {
		if(confirm('<?php _e('Delete the option', 'mg_ml') ?>?')) {
			jQuery(this).parent().parent().slideUp(function() {
				jQuery(this).remove();
			});	
		}
	});
	
	// sort opt
	jQuery('#opt_builder table').each(function() {
        jQuery(this).children('tbody').sortable({ handle: '.lcwp_move_row' });
		jQuery(this).find('.lcwp_move_row').disableSelection();
    });

	
	// tabs
	jQuery("#tabs").tabs();
});
</script>