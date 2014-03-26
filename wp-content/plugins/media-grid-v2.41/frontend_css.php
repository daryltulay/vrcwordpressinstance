<?php
////////////////////////////////////
// DYNAMICALLY CREATE THE CSS //////
////////////////////////////////////
require_once('functions.php');

// remove the HTTP/HTTPS for SSL compatibility
$safe_baseurl = str_replace(array('http:', 'https:', 'HTTP:', 'HTTPS:'), '', MG_URL);
?>

@import url("<?php echo $safe_baseurl; ?>/css/frontend.css");

@import url("<?php echo $safe_baseurl; ?>/js/jquery.galleria/themes/lcweb/galleria.lcweb.css");
@import url("<?php echo $safe_baseurl; ?>/js/jPlayer/skin/media.grid/jplayer.media.grid.css");


/* cell border */
.mg_box { 
  padding: <?php echo (int)get_option('mg_cells_margin'); ?>px; 
}

/* cell shadow  */
.mg_shadow_div {
	<?php if(get_option('mg_cells_shadow')) echo 'box-shadow: 0px 0px 3px rgba(25,25,25,0.6);'; ?>
}

/* image border */
.img_wrap {
	padding: <?php echo (int)get_option('mg_cells_img_border'); ?>px;
	
	<?php
	if(get_option('mg_img_border_color') && get_option('mg_img_border_color') != '' && get_option('mg_img_border_opacity')) {
	  $alpha_val = (int)get_option('mg_img_border_opacity') / 100;  
	  
	  echo 'background-color: '.get_option('mg_img_border_color').';
	  ';  
	
	  if($alpha_val != 0) {
		  $rgba = str_replace(array('rgb', ')'), array('rgba', ', '.$alpha_val.')'), get_option('mg_img_border_color'));
		  echo 'background-color: '.$rgba.';'; 
	  }
	}
	?>
    
    <?php
	if(get_option('mg_cells_border')) {
	  (get_option('mg_cells_border_color')) ? $col = get_option('mg_cells_border_color') : $col = '#444';
	  echo 'border: 1px solid '.$col.';';   
	}
	?> 
}

/* title under items */
.mg_title_under {
	 <?php
	if(get_option('mg_cells_border')) {
	  (get_option('mg_cells_border_color')) ? $col = get_option('mg_cells_border_color') : $col = '#444';
	  echo '
	  	border-color: '.$col.';
		border-width: 0px 1px 1px;
    	border-style: solid;
		
		margin-top: -1px;
	  ';   
	}
	?> 
}


/* overlay colors */
.img_wrap .overlays .overlay {
	<?php
	if(get_option('mg_main_overlay_color') && get_option('mg_main_overlay_color') != '') {
		$main_ol_col = get_option('mg_main_overlay_color');
	}
	else {$main_ol_col = 'rgb(255,255,255)';}
	
	echo 'background: '.$main_ol_col.';';
	?>
}
.img_wrap:hover .overlays .overlay,
.mg_touch_on .overlays .overlay {
   <?php
	// alpha
	$alpha_val = (int)get_option('mg_main_overlay_opacity') / 100;  
	
	echo '
	opacity: '.$alpha_val.';
	filter: alpha(opacity='.(int)get_option('mg_main_overlay_opacity').') !important;
	';
	?> 
}

.img_wrap .overlays .cell_more {
	<?php
	if(get_option('mg_second_overlay_color') && get_option('mg_second_overlay_color') != '') {
		$sec_ol_col = get_option('mg_second_overlay_color');
	}
	else {$sec_ol_col = '#474747';}
	
	echo 'background-color: '.$sec_ol_col.';';
	?>
}

span.mg_overlay_tit {
	<?php 
	(get_option('mg_overlay_title_color')) ? $col = get_option('mg_overlay_title_color') : $col = '#222';
	echo 'color: '.$col.';';
	?>	  
}

/* icons color */
.img_wrap .overlays .cell_more span:before {
    color: <?php echo get_option('mg_icons_col') ?>;
}

/* img */
.mg_image .img_wrap .overlays .cell_more span:before {
	content: "\e605";
}

/* gallery */
.mg_gallery .img_wrap .overlays .cell_more span:before {
	content: "\e608";
}

/* video */
.mg_video .img_wrap .overlays .cell_more span:before {
	content: "\e606";;
}

/* audio */
.mg_audio .img_wrap .overlays .cell_more span:before {
	content: "\e609";
}

/* link */
.mg_link .img_wrap .overlays .cell_more span:before {
	content: "\e604";
}

/* custom content */
.mg_lb_text .img_wrap .overlays .cell_more span:before {
	content: "\e607";
}


/* border radius */
.mg_box, .mg_shadow_div, .mg_box .img_wrap {
  border-radius: <?php echo (int)get_option('mg_cells_radius'); ?>px;
}
.mg_box .mg_title_under {
  border-bottom-left-radius: <?php echo (int)get_option('mg_cells_radius'); ?>px;
  border-bottom-right-radius: <?php echo (int)get_option('mg_cells_radius'); ?>px;
}


/* title under */
.mg_title_under {
	<?php
	if(get_option('mg_img_border_color') && get_option('mg_img_border_color') != '' && get_option('mg_img_border_opacity')) {
	  $alpha_val = (int)get_option('mg_img_border_opacity') / 100;  
	  
	  echo 'background-color: '.get_option('mg_img_border_color').';
	  ';  
	
	  if($alpha_val != 0) {
		  $rgba = str_replace(array('rgb', ')'), array('rgba', ', '.$alpha_val.')'), get_option('mg_img_border_color'));
		  echo 'background-color: '.$rgba.';'; 
	  }
	}
	?>
    
    <?php 
	(get_option('mg_overlay_title_color')) ? $col = get_option('mg_overlay_title_color') : $col = '#222';
	echo 'color: '.$col.';';
	?>	  
    
    <?php $pdg = (int)get_option('mg_cells_img_border'); ?>
    padding-bottom: <?php echo ($pdg < 4) ? 4 : $pdg; ?>px;
    padding-left: 	<?php echo ($pdg < 8) ? 8 : $pdg; ?>px;
    padding-right: 	<?php echo ($pdg < 8) ? 8 : $pdg; ?>px;
    padding-top: 	<?php echo ($pdg < 4) ? 4 : 0; ?>px !important;
}


/* filters */
.mg_filter {
	text-align: <?php echo get_option('mg_filters_align', 'left'); ?>;
    padding: 0px <?php echo (int)get_option('mg_cells_margin'); ?>px;
}
.mg_filter a.mgf {	
	color: <?php echo get_option('mg_filters_txt_color', '#444444'); ?>;
}
.mg_filter a.mgf:hover {	
	color: <?php echo get_option('mg_filters_txt_color_h', '#666666'); ?> !important;
}
.mg_filter a.mgf.mg_cats_selected,
.mg_filter a.mgf.mg_cats_selected:hover {	
	color: <?php echo get_option('mg_filters_txt_color_sel', '#222222'); ?> !important;;
}
.mg_new_filters a.mgf {	
	background-color: <?php echo get_option('mg_filters_bg_color', '#ffffff'); ?>;
    border: 1px solid <?php echo get_option('mg_filters_border_color', '#999999'); ?>;
    border-radius: <?php echo (int)get_option('mg_filters_radius', 2); ?>px;
    
    <?php if(get_option('mg_filters_align', 'left') == 'right') : ?>
    margin-right: 0px !important;
    <?php else : ?>
    margin-left: 0px !important;
    <?php endif; ?>
}
.mg_new_filters a.mgf:hover {	
	background-color: <?php echo get_option('mg_filters_bg_color_h', '#ffffff'); ?>;
    border: 1px solid <?php echo get_option('mg_filters_border_color_h', '#666666'); ?>;
}
.mg_new_filters a.mgf.mg_cats_selected,
.mg_new_filters a.mgf.mg_cats_selected:hover {	
	background-color: <?php echo get_option('mg_filters_bg_color_sel', '#ffffff'); ?>;
    border: 1px solid <?php echo get_option('mg_filters_border_color_sel', '#555555'); ?>;
}


/* opened item */
#mg_full_overlay_wrap {
  <?php  
  // color
  if(get_option('mg_item_overlay_color') && get_option('mg_item_overlay_color') != '') {
	  $item_ol_col = get_option('mg_item_overlay_color');
  }
  else {$item_ol_col = '#fff';}  
  
  // alpha
  $alpha_val = (int)get_option('mg_item_overlay_opacity') / 100;  
  
  // pattern
  if(get_option('mg_item_overlay_pattern') && get_option('mg_item_overlay_pattern') != 'none') {
	  $pat = 'url('. $safe_baseurl .'/img/patterns/'.get_option('mg_item_overlay_pattern').') repeat top left';
  }
  else {$pat = '';}
  
  echo '
  background: '.$pat.' '.$item_ol_col.';	
  
  opacity: '.$alpha_val.';
  filter: alpha(opacity='.(int)get_option('mg_item_overlay_opacity').') !important;
  ';  
  ?>  
}
#mg_overlay_content {
  <?php 
  (get_option('mg_item_width')) ? $w = get_option('mg_item_width') : $w = 70;
  echo 'width: '.$w.'%;';
  
  (get_option('mg_item_maxwidth')) ? $w = get_option('mg_item_maxwidth') : $w = 960;
  echo 'max-width: '.$w.'px;';
  ?>
}

/* colors */
#mg_overlay_content,
.mg_item_load {
    <?php 
	(get_option('mg_item_txt_color')) ? $col = get_option('mg_item_txt_color') : $col = '#222';
	echo 'color: '.$col.';
';
	?>	
    <?php 
	(get_option('mg_item_bg_color')) ? $col = get_option('mg_item_bg_color') : $col = '#fff';
	echo 'background-color: '.$col.';
';
	?>
}

/* icons */
<?php if(get_option('mg_item_icons') == 'light') : ?>
#mg_full_overlay .mg_item_load,
#mg_full_overlay .mg_item_featured {
	background-image: url(<?php echo $safe_baseurl; ?>/img/loader_w.gif);
}	
	
#mg_close {
	background-image: url(<?php echo $safe_baseurl; ?>/img/item_close_w.png);
}	
#mg_nav .mg_nav_prev span {
	background-image: url(<?php echo $safe_baseurl; ?>/img/item_prev_w.png);		
}
#mg_nav .mg_nav_next span {
	background-image: url(<?php echo $safe_baseurl; ?>/img/item_next_w.png);	
}
<?php endif; ?>

/* border radius */
#mg_overlay_content {
	border-radius: <?php echo (int)get_option('mg_item_radius'); ?>px;
}

<?php 
// custom CSS
echo get_option('mg_custom_css');
?>