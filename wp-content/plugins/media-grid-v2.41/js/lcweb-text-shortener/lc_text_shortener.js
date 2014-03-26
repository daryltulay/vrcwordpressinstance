/* ------------------------------------------------------------------------
	* LCweb text shortener
	*
	* @version: 	1.0
	* @requires:	jQuery v1.5 or later
	* @author:		Luca Montanari (LCweb) (http://projects.lcweb.it)
	
	* Licensed under the MIT license
------------------------------------------------------------------------- */

(function($) {	
	$.fn.lcweb_txt_shortener = function(end_txt, max_height, to_strip, to_remove) {
		var $obj = $(this);
		
		// global array of original contents
		if(typeof(lcts_orig_texts) == 'undefined') {lcts_orig_texts = $.makeArray();}
		
		//// parameters sanitizing
		if(typeof(end_txt) == 'undefined') {end_txt = '..';}	
		///////////////////////////////////
		
		
		var lcts_shorten = function() {
			
			$obj.each(function() {
				var $subj = $(this);

				// apply parameter to store orig text in array or get the existing ID
				var uniqID = $(this).attr('lcts-id');
				
				if( typeof(uniqID) == 'undefined') {	
					uniqID = Math.round(new Date().getTime() + (Math.random() * 100));
					$(this).attr('lcts-id', uniqID);
					
					lcts_orig_texts[uniqID] = $subj.html();
				}
				else {
					// reset 	
					var orig_txt = lcts_orig_texts[uniqID];
					$subj.html(orig_txt);
					
					$subj.removeClass('lcnb_shorten');		
				}
				
				// clean empty elements
				$subj.find('*:empty').not('br, img').remove();

				// current sizes
				var txt_h = $subj.outerHeight(true);
				var wrap_h = (typeof(max_height) == 'undefined' || !max_height) ? $subj.parent().height() : parseInt(max_height); // if not set max-height - use the wrapper height
				if(typeof(max_height) != 'undefined' && parseInt(max_height) > wrap_h) {wrap_h = parseInt(max_height);}
	
				// if is higher
				if(wrap_h < txt_h) {
					var complete_contents = $subj.html();
					$subj.addClass('lcnb_shorten');
					
					// clean the attribues
					$subj.find('*').lcts_remove_all_attr();
					
					// leave only paragraphs and links to avoid slowdowns
					$subj.find('*').not('a, p, br').each(function() {
						var content = $(this).contents();
						$(this).replaceWith(content);
					});
					
					var orig_contents = $subj.html();
					var exploded = orig_contents.split(' ');
					var new_contents = '';
					var right_h_txt =  '';
					
					var txt_h = 0;
					var a = 0;
					
					while(txt_h < wrap_h && a < exploded.length) {
						if( typeof(exploded[a]) != 'undefined') {
							right_h_txt = new_contents;
							new_contents = new_contents + exploded[a] + ' ';	

							// append and clean	
							$subj.html(new_contents + ' <span class="lcts_end_txt">'+ end_txt +'</span>');	
					
							// remove BR before the "read more" text
							while( $subj.html().indexOf('<br\> <span class="lcts_end_txt">') != -1 ) {
								$subj.find('.lcts_end_txt').prev().remove();	
							}

							txt_h = $subj.outerHeight(true);
							a++;
						}
					}
					
					
					// check unclosed tags 
					var tags = ['a', 'p'];
					$.each(tags, function(i, v) {
						var open_count = right_h_txt.match('<'+v, 'g');  
						var close_count = right_h_txt.match('</'+v, 'g');
						
						if(open_count != null) {
							if(open_count != null && close_count == null || open_count.length > close_count.length) {
								right_h_txt = right_h_txt + '</'+ v +'>';
							}
						}
						
						if(i == (tags.length - 1)) {
							$subj.html(right_h_txt + '<span class="lcts_end_txt">'+ end_txt +'</span>');	
							$subj.find('*:empty').not('br').remove();

							// remove BR before the "read more" text
							while( $subj.html().indexOf('<br\> <span class="lcts_end_txt">') != -1 ) {
								$subj.find('.lcts_end_txt').prev().remove();	
							}
						}
					});

					// last P tag fix
					$subj.find('p').last().css('display', 'inline');
				}
			});
		}
		
		
		// remove all attributes from html tags
		$.fn.lcts_remove_all_attr = function() {
			return this.each(function() {
				var attributes = $.map(this.attributes, function(item) {
				  return item.name;
				});
				
				var obj = $(this);
				$.each(attributes, function(i, item) {
					if( item != "href" && item != "target") {
						obj.removeAttr(item);
					}
				});
			});
		}
		
		
		// debounce resize to trigger only once
		var lcts_debouncer = function($,cf,of, interval){
			var debounce = function (func, threshold, execAsap) {
				var timeout;
				
				return function debounced () {
					var obj = this, args = arguments;
					function delayed () {
						if (!execAsap) {func.apply(obj, args);}
						timeout = null;
					}
				
					if (timeout) {clearTimeout(timeout);}
					else if (execAsap) {func.apply(obj, args);}
					
					timeout = setTimeout(delayed, threshold || interval);
				};
			};
			jQuery.fn[cf] = function(fn){ return fn ? this.bind(of, debounce(fn)) : this.trigger(cf); };
		};
		lcts_debouncer(jQuery,'lcts_smartresize', 'resize', 50);
		
		
		// shorten also on resize
		$(window).lcts_smartresize(function(end_txt, max_height, to_strip, to_remove) {
			lcts_shorten();
		});
		
		
		return lcts_shorten();
	}; 
	
})(jQuery);