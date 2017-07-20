(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */

	
})( jQuery );

jQuery(document).ready(function($) {

	var $media_dialog = '';
	jQuery(document).on('click', '.ftg-author-media[data-inst-mode="popup"] .ftg-media-item > a', function(e) {
		e.preventDefault();
		var current_media = jQuery(this);
		current_media.parent().addClass('dialog-opened');
		if ( !jQuery(this).parent().prev('.ftg-media-item').length ) {
			jQuery(this).parent().find('.ftg-media-item-dialog .ftg-media-item-prev').hide();
		}
		if ( !jQuery(this).parent().next('.ftg-media-item').length ) {
			jQuery(this).parent().find('.ftg-media-item-dialog .ftg-media-item-next').hide();
		}
		$media_dialog = jQuery(this).parent().find('.ftg-media-item-dialog').dialog({
			width: 1040,
			dialogClass: "feed-the-grid-dialog instagram",
			modal: true,
			resizable: false,
			draggable: false,
			close: function( event, ui ) {
				$media_dialog.removeClass('ftg-show-content');
				setTimeout(function() {
					$media_dialog.removeClass('ftg-full-width');
				}, 250);
				setTimeout(function() {
					$media_dialog.dialog('destroy');
				}, 350);
			}
		});

		$media_dialog.addClass('ftg-full-width');
		setTimeout(function() {
			$media_dialog.addClass('ftg-show-content');
		}, 150);
	});

	jQuery(document).on('click', '.ftg-media-dialog-close', function() {
		// $media_dialog.dialog('destroy');
		$media_dialog.removeClass('ftg-show-content');
		setTimeout(function() {
			$media_dialog.removeClass('ftg-full-width');
		}, 250);
		setTimeout(function() {
			$media_dialog.parent().find('.ui-dialog-titlebar button').click();
		}, 350);
	});

	jQuery(document).on('click', '.ftg-media-item-prev', function() {
		$media_dialog.dialog('destroy');
		$media_dialog.removeClass('ftg-show-content').removeClass('ftg-full-width');
		$media_dialog.parent().find('.ui-dialog-titlebar button').click();
		var current_media = jQuery('.ftg-media-item.dialog-opened');
		current_media.prev().find('a').first().click();
		current_media.removeClass('dialog-opened');
	});

	jQuery(document).on('click', '.ftg-media-item-next', function() {
		$media_dialog.dialog('destroy');
		$media_dialog.removeClass('ftg-show-content').removeClass('ftg-full-width');
		$media_dialog.parent().find('.ui-dialog-titlebar button').click();
		var current_media = jQuery('.ftg-media-item.dialog-opened');
		current_media.next().find('a').first().click();
		current_media.removeClass('dialog-opened');
	});

	if ( jQuery('#ftg-media-json').length ) {
		var insta_media_data = jQuery.parseJSON(jQuery('#ftg-media-json').html());
		var media_from = parseInt(jQuery('.ftg-author-media').attr('data-media-initial'));
		var current_html = '';
		var insta_columns = parseInt(jQuery('.ftg-author-media').attr('data-col'));
		var insta_rows = parseInt(jQuery('.ftg-author-media').attr('data-rows'));
		if ( jQuery('.ftg-main-wrapper-inner').hasClass('ftg-layout-slider') ) {
			media_from = 9999;
			jQuery('.ftg-insta-load-more').hide();
		}

		if ( jQuery('.ftg-main-wrapper-inner').hasClass('ftg-layout-slider') ) {
			current_html += '<div class="ftg-slider-wrapper"><div class="ftg-slider-container"><div class="ftg-slider-row">';
		}

		jQuery.each(insta_media_data, function(med_indx, med_value) {
			if ( med_indx < media_from ) {
				var curr_media_data = jQuery.parseJSON(med_value);
				current_html += '<div class="ftg-media-item"><a href="'+curr_media_data.link+'" target="_blank"><img src="'+curr_media_data.images.low_resolution.url+'" alt=""><div class="ftg-media-item-overlay"><div class="ftg-overlay-meta"><span class="ftg-media-item-likes ftgicon-heart-1">'+curr_media_data.likes.count+'</span><span class="ftg-media-item-comments ftgicon-comment-1">'+curr_media_data.comments.count+'</span></div></div></a><div class="ftg-media-item-dialog" style="display: none;"><span class="ftg-media-dialog-close ftgicon-cancel"></span><div class="ftg-media-dialog-image"><img src="'+curr_media_data.images.standard_resolution.url+'" alt=""></div><div class="ftg-media-dialog-side"><div class="ftg-media-dialog-side-meta"><img src="'+curr_media_data.user.profile_picture+'" class="ftg-media-dialog-profile" alt=""><a href="https://instagram.com/'+curr_media_data.user.username+'" class="ftg-media-dialog-link" target="_blank">'+curr_media_data.user.username+'</a><a href="'+curr_media_data.link+'" target="_blank" class="ftg-media-dialog-instagram">View on instagram</a><div class="clearfix"></div><span class="ftg-media-dialog-likes ftgicon-heart-empty">'+curr_media_data.likes.count+'</span><span class="ftg-media-dialog-comments ftgicon-comment-empty">'+curr_media_data.comments.count+'</span><span class="ftg-media-dialog-date">'+curr_media_data.human_date+' ago</span></div><div class="ftg-media-dialog-caption">';
				if ( curr_media_data.caption != null ) {
					var caption_text = curr_media_data.caption.text;
					caption_text = caption_text.replace(/@(\S+)/g, '<a href="https://instagram.com/$1" target="_blank">@$1</a>');
					caption_text = caption_text.replace(/#(\S+)/g, '<a href="https://instagram.com/explore/tags/$1" target="_blank">#$1</a>');
					current_html += '<a href="https://instagram.com/'+curr_media_data.caption.from.username+'" target="_blank">'+curr_media_data.caption.from.username+'</a><p>'+caption_text+'</p>';
				}

				if ( typeof curr_media_data.insta_media_comments == 'object' ) {
					current_html += '<div class="ftg-media-dialog-comments-container">';
					jQuery.each(curr_media_data.insta_media_comments, function(com_indx, com_value) {
						var comment_text = com_value.text;
						comment_text = comment_text.replace(/@(\S+)/g, '<a href="https://instagram.com/$1" target="_blank">@$1</a>');
						comment_text = comment_text.replace(/#(\S+)/g, '<a href="https://instagram.com/explore/tags/$1" target="_blank">#$1</a>');
						current_html += '<div class="ftg-media-dialog-comment-item"><a href="https://instagram.com/'+com_value.from.username+'" target="_blank">'+com_value.from.username+'</a><p>'+comment_text+'</p></div>';
					});
					current_html += '</div>';
				}

				current_html += '</div></div><a href="javascript:void(0)" class="ftg-media-item-prev ftgicon-angle-left"><a href="javascript:void(0)" class="ftg-media-item-next ftgicon-angle-right"></a><div class="clearfix"></div></div></div>';
				
				if ( med_indx == insta_media_data.length-1 ) {
					jQuery('.ftg-insta-load-more').hide();
				}
				
				if ( (med_indx+1) % (insta_columns*insta_rows) == 0 ) {
					current_html += '</div><div class="ftg-slider-row">';
				}
			}
		});

		if ( jQuery('.ftg-main-wrapper-inner').hasClass('ftg-layout-slider') ) {
			current_html += '</div></div><a href="javascript:void(0)" class="ftg-slider-next ftgicon-angle-right"></a><a href="javascript:void(0)" class="ftg-slider-prev ftgicon-angle-left"></a></div>';
		}

		jQuery('.ftg-author-media').append(current_html);
	}

	if ( jQuery('.ftg-author-media .ftg-media-item').length ) {
		setTimeout(function() {
			jQuery('.ftg-author-media .ftg-media-item').each(function() {
				if ( !jQuery(this).hasClass('show') ) {
					jQuery(this).addClass('show');
				}
			});
		}, 500);
	}

	jQuery(document).on('click', '.ftg-insta-load-more', function() {
		var media_from = parseInt(jQuery('.ftg-author-media').attr('data-media-initial'));
		var media_till = media_from+6;

		var insta_media_data = jQuery.parseJSON(jQuery('#ftg-media-json').html());
		jQuery.each(insta_media_data, function(med_indx, med_value) {
			if ( med_indx+1 > media_from && med_indx+1 < media_till ) {
				var curr_media_data = jQuery.parseJSON(med_value);
				var current_html = '<div class="ftg-media-item show"><a href="'+curr_media_data.link+'" target="_blank"><img src="'+curr_media_data.images.low_resolution.url+'" alt=""><div class="ftg-media-item-overlay"><div class="ftg-overlay-meta"><span class="ftg-media-item-likes ftgicon-heart-1">'+curr_media_data.likes.count+'</span><span class="ftg-media-item-comments ftgicon-comment-1">'+curr_media_data.comments.count+'</span></div></div></a><div class="ftg-media-item-dialog" style="display: none;"><span class="ftg-media-dialog-close ftgicon-cancel"></span><div class="ftg-media-dialog-image"><img src="'+curr_media_data.images.standard_resolution.url+'" alt=""></div><div class="ftg-media-dialog-side"><div class="ftg-media-dialog-side-meta"><img src="'+curr_media_data.user.profile_picture+'" class="ftg-media-dialog-profile" alt=""><a href="https://instagram.com/'+curr_media_data.user.username+'" class="ftg-media-dialog-link" target="_blank">'+curr_media_data.user.username+'</a><a href="'+curr_media_data.link+'" class="ftg-media-dialog-instagram" target="_blank">View on instagram</a><div class="clearfix"></div><span class="ftg-media-dialog-likes ftgicon-heart-empty">'+curr_media_data.likes.count+'</span><span class="ftg-media-dialog-comments ftgicon-comment-empty">'+curr_media_data.comments.count+'</span><span class="ftg-media-dialog-date">'+curr_media_data.human_date+' ago</span></div><div class="ftg-media-dialog-caption">';
				if ( curr_media_data.caption != null ) {
					var caption_text = curr_media_data.caption.text;
					caption_text = caption_text.replace(/@(\S+)/g, '<a href="https://instagram.com/$1" target="_blank">@$1</a>');
					caption_text = caption_text.replace(/#(\S+)/g, '<a href="https://instagram.com/explore/tags/$1" target="_blank">#$1</a>');
					current_html += '<a href="https://instagram.com/'+curr_media_data.caption.from.username+'" target="_blank">'+curr_media_data.caption.from.username+'</a><p>'+caption_text+'</p>';
				}

				if ( typeof curr_media_data.insta_media_comments == 'object' ) {
					current_html += '<div class="ftg-media-dialog-comments-container">';
					jQuery.each(curr_media_data.insta_media_comments, function(com_indx, com_value) {
						var comment_text = com_value.text;
						comment_text = comment_text.replace(/@(\S+)/g, '<a href="https://instagram.com/$1" target="_blank">@$1</a>');
						comment_text = comment_text.replace(/#(\S+)/g, '<a href="https://instagram.com/explore/tags/$1" target="_blank">#$1</a>');
						current_html += '<div class="ftg-media-dialog-comment-item"><a href="https://instagram.com/'+com_value.from.username+'" target="_blank">'+com_value.from.username+'</a><p>'+comment_text+'</p></div>';
					});
					current_html += '</div>';
				}

				current_html += '</div></div><a href="javascript:void(0)" class="ftg-media-item-prev ftgicon-angle-left"><a href="javascript:void(0)" class="ftg-media-item-next ftgicon-angle-right"></a><div class="clearfix"></div></div></div>';
				jQuery('.ftg-author-media').append(current_html);
				if ( med_indx+1 == insta_media_data.length ) {
					jQuery('.ftg-insta-load-more').hide();
				}
			}
		});

		setTimeout(function() {
			jQuery('.ftg-author-media .ftg-media-item').each(function() {
				if ( !jQuery(this).hasClass('show') ) {
					jQuery(this).addClass('show');
				}
			});
		}, 2500);

		jQuery('.ftg-author-media').attr('data-media-initial', media_till);
	});

	if ( jQuery('.ftg-main-wrapper').hasClass('ftg-layout-slider') ) {
		jQuery('.ftg-slider-container .ftg-slider-row').width(jQuery('.ftg-slider-wrapper').width());

		var $insta_media_slider = jQuery('.ftg-slider-wrapper').jcarousel({
			wrap: "circular",
			auto: 3
		});

		jQuery(document).on('click', '.ftg-slider-wrapper .ftg-slider-next', function() {
			$insta_media_slider.jcarousel('scroll', '+=1');
		});

		jQuery(document).on('click', '.ftg-slider-wrapper .ftg-slider-prev', function() {
			$insta_media_slider.jcarousel('scroll', '-=1');
		});
	}

	if ( jQuery('.ftg-channel-video-wrapper').length && jQuery('.ftg-main-wrapper').hasClass('ftg-layout-slider') ) {
		var item_width = jQuery('.ftg-channel-video-wrapper').outerWidth();
		jQuery('.ftg-channel-videos .ftg-slider-row').width(item_width);
		var $ftg_video_carousel = jQuery('.ftg-channel-video-wrapper').jcarousel({
			wrap: "circular"
		});

		jQuery(window).resize(function() {
			var item_width = jQuery('.ftg-channel-video-wrapper').outerWidth();
			jQuery('.ftg-channel-videos .ftg-slider-row').width(item_width);
		});

		jQuery(document).on('click', '.ftg-channel-video-wrapper .ftg-video-next', function() {
			$ftg_video_carousel.jcarousel('scroll', '+=1');
		});

		jQuery(document).on('click', '.ftg-channel-video-wrapper .ftg-video-prev', function() {
			$ftg_video_carousel.jcarousel('scroll', '-=1');
		});
	}

	jQuery(document).on('click', '.ftg-toggle-description', function() {
		jQuery(this).parent().toggleClass('full-content');
		if ( jQuery(this).attr('data-text') == 'Show more' ) {
			jQuery(this).attr('data-text', 'Show less');
		} else {
			jQuery(this).attr('data-text', 'Show more');
		}
	});

	var $video_top, $video_left, $video_width, $video_height, $video_right = 0;
	jQuery(document).on('click', '.ftg-video-item.ftg-mode-popup .ftg-video-image', function() {
		var current_video_element = jQuery(this).parent();
		$video_width = current_video_element.outerWidth()-20;
		$video_height = current_video_element.find('.ftg-video-image').outerHeight();
		$video_top = current_video_element.offset().top - jQuery(window).scrollTop() - 50;
		$video_left = current_video_element.offset().left+10;
		$video_right = (jQuery(window).width() - (current_video_element.offset().left + current_video_element.outerWidth()));
		if ( !jQuery('.ftg-video-left-margin').length ) {
			jQuery('body').append('<div class="ftg-video-left-margin" style="width: 850px;margin: 0 auto;"></div>');
		}

		$youtube_dialog = jQuery(this).parent().find('.ftg-video-dialog').dialog({
			width: 850,
			dialogClass: "feed-the-grid-dialog youtube",
			modal: true,
			resizable: false,
			draggable: false,
			close: function( event, ui ) {
				$youtube_dialog.removeClass('ftg-remove-height');
				$youtube_dialog.removeClass('ftg-show-content');
				setTimeout(function() {
					$youtube_dialog.removeClass('ftg-full-width');
				}, 150);
				setTimeout(function() {
					$youtube_dialog.removeClass('ftg-remove-overflow');
					$youtube_dialog.dialog("destroy");
				}, 450);
				setTimeout(function() {
					$youtube_dialog.find('iframe').first().attr('src', '');
				}, 500);
			}
		});

		if ( !current_video_element.hasClass('comments-loaded') && $youtube_dialog.find('.ftg-video-dialog-comments').length ) {
			jQuery.ajax({
				type: 'POST',
				url: ftg_main.ajaxurl,
				data: { 
					'action': 'ftg_get_comment_data',
					'ftg_video_id': current_video_element.attr('data-video')
				},
				success: function( data ) {
					if ( data == 'failed' ) {
						$youtube_dialog.find('.ftg-video-dialog-comments .ftg-comments-loading').html('There was an issue while loading the comments!');
					} else {
						$youtube_dialog.find('.ftg-video-dialog-comments .ftg-comments-loading').hide();
						var video_comments = jQuery.parseJSON(data);
						jQuery.each(video_comments, function(comment_index, comment_data) {
							var comment_content = '<div class="ftg-video-dialog-comment-item"><a href="'+comment_data.snippet.topLevelComment.snippet.authorChannelUrl+'" class="ftg-video-dialog-comment-image" target="_blank"><img src="'+comment_data.snippet.topLevelComment.snippet.authorProfileImageUrl+'" alt=""></a><div class="ftg-video-dialog-comment-side"><a href="'+comment_data.snippet.topLevelComment.snippet.authorChannelUrl+'" target="_blank">'+comment_data.snippet.topLevelComment.snippet.authorDisplayName+'</a><span class="ftg-video-dialog-date">'+comment_data.date_formatted+'</span><p class="ftg-video-dialog-comment-text">'+comment_data.snippet.topLevelComment.snippet.textDisplay+'</p></div></div>';
							$youtube_dialog.find('.ftg-video-dialog-comments').append(comment_content);
						});
					}
					current_video_element.addClass('comments-loaded');
				}
			});
		}

		// Set position from which to open video
		$youtube_dialog.width($video_width);
		$youtube_dialog.find('iframe').first().width($video_width);
		$youtube_dialog.find('iframe').first().height('478');
		$youtube_dialog.css({
			'top': $video_top,
			'margin-left': '0',
			'margin-right': '0'
		});
		if ( $video_left < ( jQuery(window).width() / 2 ) ) {
			$youtube_dialog.css({ 'left': $video_left, 'right': 'auto' });
		} else {
			$youtube_dialog.css({ 'left': 'auto', 'right': $video_right, 'position': 'absolute' });
		}

		// Start opening the video
		$youtube_dialog.addClass('ftg-full-width');
		setTimeout(function() {
			$youtube_dialog.addClass('ftg-show-content');
		}, 350);
		setTimeout(function() {
			$youtube_dialog.addClass('ftg-remove-overflow');
			$youtube_dialog.find('iframe').first().width('850');
			$youtube_dialog.find('iframe').first().height('478');
		}, 500);
		setTimeout(function() {
			$youtube_dialog.addClass('ftg-remove-height');

			// if ( typeof $youtube_dialog.find('.ftg-dialog-iframe-wrapper iframe').attr('src') == 'undefined' ) {
				var autoplay = '';
				if ( $youtube_dialog.find('.ftg-dialog-iframe-wrapper').attr('data-autoplay') == '1' ) {
					autoplay = '?autoplay=1';
				}
				$youtube_dialog.find('.ftg-dialog-iframe-wrapper iframe').attr('src', $youtube_dialog.find('.ftg-video-dialog-iframe').val()+autoplay).css('opacity', '1');
			// }

			if ( !$youtube_dialog.find('.ftg-video-dialog-subscribe').hasClass('ftg-yt-loaded') && $youtube_dialog.find('.ftg-video-dialog-subscribe').attr('data-visibility') == '1' ) {
				var container = $youtube_dialog.find('.ftg-video-dialog-subscribe .g-ytsubscribe').get(0);
				var options = {
					'channelId': $youtube_dialog.find('.ftg-video-dialog-subscribe').attr('data-channel'),
					'layout': 'full'
				};
				gapi.ytsubscribe.render(container, options);
				$youtube_dialog.find('.ftg-video-dialog-subscribe').addClass('ftg-yt-loaded');
			}
		}, 650);

		// Go to top/center of the screen
		$youtube_dialog.css({
			'-webkit-transition': 'top .3s ease-in-out, left .3s ease-in-out, right .3s ease-in-out, width .2s ease-in-out, height .3s ease-in-out',
			'-moz-transition': 'top .3s ease-in-out, left .3s ease-in-out, right .3s ease-in-out, width .2s ease-in-out, height .3s ease-in-out',
			'-ms-transition': 'top .3s ease-in-out, left .3s ease-in-out, right .3s ease-in-out, width .2s ease-in-out, height .3s ease-in-out',
			'-o-transition': 'top .3s ease-in-out, left .3s ease-in-out, right .3s ease-in-out, width .2s ease-in-out, height .3s ease-in-out',
			'transition': 'top .3s ease-in-out, left .3s ease-in-out, right .3s ease-in-out, width .2s ease-in-out, height .3s ease-in-out'
		});

		setTimeout(function() {
			$youtube_dialog.css({
				'top': '0',
				'overflow': 'visible'
			});
			if ( $video_left < ( jQuery(window).width() / 2 ) ) {
				$youtube_dialog.css({ 'left': jQuery('.ftg-video-left-margin').offset().left, 'right': 'auto' });
			} else {
				$youtube_dialog.css({ 'left': 'auto', 'right': jQuery(window).width()-(jQuery('.ftg-video-left-margin').offset().left+jQuery('.ftg-video-left-margin').outerWidth()), 'position': 'absolute' });
			}
		}, 10);
	});

	jQuery(document).on('click', '.ftg-video-item.ftg-mode-youtube .ftg-video-image', function() {
		window.open('//www.youtube.com/watch?v='+jQuery(this).parent().attr('data-video'), '_blank');
	});

	jQuery(document).on('click', '.ftg-video-item.ftg-mode-inline .ftg-video-image', function() {
		if ( jQuery(this).hasClass('ftg-inline-play') ) {
			return;
		}
		var video_image = jQuery(this).find('img');
		jQuery(this).addClass('ftg-inline-play');
		var video_iframe = '<iframe width="'+jQuery(this).find('img').outerWidth()+'" height="'+jQuery(this).find('img').outerHeight()+'" src="https://www.youtube.com/embed/'+jQuery(this).parent().attr('data-video')+'?autoplay=1" frameborder="0" allowfullscreen></iframe>';
		jQuery(this).append(video_iframe);

		setTimeout(function() {
			video_image.addClass('ftg-hide-img');
		}, 1000);
	});

	jQuery(document).on('click', '.ftg-youtube-dialog-close', function() {
		$youtube_dialog.css('width', jQuery('.ftg-video-item .ftg-video-image').outerWidth());
		$youtube_dialog.removeClass('ftg-remove-height');
		setTimeout(function() {
			$youtube_dialog.removeClass('ftg-show-content');
		}, 10);

		setTimeout(function() {
			$youtube_dialog.removeClass('ftg-full-width');

			// Go back to item
			$youtube_dialog.css({
				'top': $video_top,
				'height': $video_height,
				'margin-left': '0',
				'overflow': 'hidden'
			});

			if ( $video_left < ( jQuery(window).width() / 2 ) ) {
				$youtube_dialog.css({ 'left': $video_left, 'right': 'auto' });
			} else {
				$youtube_dialog.css({ 'left': 'auto', 'right': $video_right, 'position': 'absolute' });
			}

			// Youtube iframe height
			$youtube_dialog.find('iframe').first().height($video_height);
			$youtube_dialog.find('iframe').first().css('opacity', '0');
		}, 350);
		setTimeout(function() {
			$youtube_dialog.removeClass('ftg-remove-overflow');
			$youtube_dialog.parent().find('.ui-dialog-titlebar button').click();
		}, 650);
	});

	if ( jQuery('.ftg-main-wrapper.youtube').hasClass('ftg-layout-grid') ) {
		jQuery('.ftg-main-wrapper.youtube .ftg-slider-row').first().show();
		if ( !jQuery('.ftg-main-wrapper.youtube .ftg-slider-row').first().next().length || !jQuery('.ftg-main-wrapper.youtube .ftg-slider-row').first().next().children().length ) {
			jQuery('.ftg-youtube-load-more').parent().hide();
		}
	} else if ( jQuery('.ftg-main-wrapper.youtube').hasClass('ftg-layout-slider') ) {
		jQuery('.ftg-main-wrapper.youtube .ftg-slider-row').show();
	}

	jQuery(document).on('click', '.ftg-youtube-load-more', function() {
		jQuery('.ftg-channel-videos .ftg-slider-row').each(function(index, grid_row) {
			if ( jQuery(grid_row).css('display') == 'none' ) {
				jQuery(grid_row).css('display', 'block');
				if ( !jQuery(grid_row).next().length ) {
					jQuery('.ftg-youtube-load-more').hide();
				}
				return false;
			}
		});
	});

	jQuery(document).on('click', '.ftg-twitter-middle-head a:not(.active)', function() {
		jQuery('.ftg-twitter-middle-head a').removeClass('active');
		jQuery(this).addClass('active');

		jQuery('.ftg-twitter-status-item').removeClass('shown');
		if ( jQuery(this).attr('data-type') == 'tweet' ) {
			jQuery('.ftg-twitter-status-item[data-type="tweet"], .ftg-twitter-status-item[data-type="media"]').addClass('shown');
		} else if ( jQuery(this).attr('data-type') == 'tweet,reply' ) {
			jQuery('.ftg-twitter-status-item').addClass('shown');
		} else if ( jQuery(this).attr('data-type') == 'media' ) {
			jQuery('.ftg-twitter-status-item[data-type="media"]').addClass('shown');
		}
	});
});

jQuery(window).load(function() {

});