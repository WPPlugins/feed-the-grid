(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-specific JavaScript source
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
	jQuery(document).on('click', '.ftg-primary-button.save', function() {
		if ( jQuery(this).hasClass('save-disabled') ) {
			return;
		}
		jQuery(this).addClass('save-disabled');

		var main_settings = {};
		main_settings.instagram_auth = jQuery('#ftg-instagram-auth').val();
		main_settings.youtube_key = jQuery('#ftg-youtube-key').val();
		main_settings.twitter_consumer_key = jQuery('#ftg-twitter-consumer-key').val();
		main_settings.twitter_consumer_secret = jQuery('#ftg-twitter-consumer-secret').val();
		main_settings.twitter_client_token = jQuery('#ftg-twitter-client-token').val();
		main_settings.twitter_client_secret = jQuery('#ftg-twitter-client-secret').val();

		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: { 
				'action': 'ftg_save_data',
				'ftg_settings': JSON.stringify(main_settings),
			},
			success: function() {
				location.reload();
			}
		});
	});

	var $ftg_stream_dialog = '';
	jQuery(document).on('click', '.ftg-add-new-stream', function() {
		$ftg_stream_dialog = jQuery('#ftg-new-stream-dialog').dialog({
			width: 500,
			dialogClass: "feed-the-grid",
			modal: true,
			resizable: false,
			draggable: false,
			show: {
				effect: "fadeIn",
				duration: 800
			},
			hide: {
				effect: "fadeOut",
				duration: 800
			},
			close: function( event, ui ) {
				// Clear dialog inputs
				jQuery('#ftg-new-stream-dialog input[type="text"]').val('');
				jQuery('.ftg-stream-type, .ftg-stream-types').hide();
				jQuery('.ftg-stream-type .ftg-stream-item, .ftg-stream-types .ftg-instagram-type').removeClass('active')
			}
		});
	});

	jQuery(document).on('click', '.ftg-stream-type .ftg-stream-item', function() {
		jQuery('.ftg-stream-type .ftg-stream-item, .ftg-stream-types > div').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('.ftg-stream-types > div[data-stream-type="'+jQuery(this).attr('data-type')+'"]').addClass('active');
		jQuery('.ftg-stream-types').slideDown();
	});

	jQuery(document).on('input', '#ftg-stream-name', function() {
		if ( jQuery(this).val() != '' ) {
			jQuery('.ftg-stream-type').slideDown();
			if ( jQuery('.ftg-stream-type .ftg-stream-item.active').length ) {
				jQuery('.ftg-stream-types').slideDown();
			}
		} else {
			jQuery('.ftg-stream-type, .ftg-stream-types').slideUp();
			jQuery('.ftg-stream-type .ftg-stream-item').removeClass('active');
		}
	});

	jQuery(document).on('click', '.ui-dialog.feed-the-grid .save-stream', function() {
		if ( jQuery(this).hasClass('save-disabled') ) {
			return;
		}
		jQuery(this).addClass('save-disabled');
		ftg_save_stream();
	});

	function ftg_save_stream() {
		var stream_settings = {};
		if ( jQuery('.ftg-stream-type .ftg-stream-item.active').attr('data-type') == 'instagram' ) {
			stream_settings.stream_type = 'instagram';
			stream_settings.source_list = jQuery('#ftg-instagram-username').val();
		} else if ( jQuery('.ftg-stream-type .ftg-stream-item.active').attr('data-type') == 'youtube' ) {
			stream_settings.stream_type = 'youtube';
			stream_settings.source_list = jQuery('#ftg-youtube-channel').val();
		} else if ( jQuery('.ftg-stream-type .ftg-stream-item.active').attr('data-type') == 'twitter' ) {
			stream_settings.stream_type = 'twitter';
			stream_settings.source_list = jQuery('#ftg-twitter-username').val();
		}

		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'ftg_save_stream',
				'ftg_stream_name': jQuery('#ftg-stream-name').val(),
				'ftg_stream_type': jQuery('.ftg-stream-type .ftg-stream-item.active').attr('data-type'),
				'ftg_stream_settings': JSON.stringify(stream_settings)
			},
			success: function(data) {
				if ( jQuery.isNumeric(jQuery.parseJSON(data).save_response) ) {
					location.reload();
				}
			}
		});
	}

	jQuery(document).on('click', '.ftg-setting-dialog-close', function() {
		$ftg_stream_dialog.dialog('close');
	});

	jQuery(document).on('click', '.ftg-number-minus', function() {
		if ( jQuery(this).parent().find('input').val() == '' ) {
			jQuery(this).parent().find('input').val('0');
		}
		jQuery(this).parent().find('input').val(parseInt(jQuery(this).parent().find('input').val())-1).trigger('input');
	});

	var $ftg_number_minus;
	jQuery(document).on('mousedown', '.ftg-number-minus', function() {
		var current_element = jQuery(this);
		$ftg_number_minus = setInterval(function() {
			if ( current_element.parent().find('input').val() == '' ) {
				current_element.parent().find('input').val('0');
			}
			current_element.parent().find('input').val(parseInt(current_element.parent().find('input').val())-1).trigger('input');
		}, 100);
	}).on('mouseup mouseout', '.ftg-number-minus', function() {
		clearInterval($ftg_number_minus);
	});

	jQuery(document).on('click', '.ftg-number-plus', function() {
		if ( jQuery(this).parent().find('input').val() == '' ) {
			jQuery(this).parent().find('input').val('0');
		}
		jQuery(this).parent().find('input').val(parseInt(jQuery(this).parent().find('input').val())+1).trigger('input');
	});

	var $ftg_number_plus;
	jQuery(document).on('mousedown', '.ftg-number-plus', function() {
		var current_element = jQuery(this);
		$ftg_number_plus = setInterval(function() {
			if ( current_element.parent().find('input').val() == '' ) {
				current_element.parent().find('input').val('0');
			}
			current_element.parent().find('input').val(parseInt(current_element.parent().find('input').val())+1).trigger('input');
		}, 100);
	}).on('mouseup mouseout', '.ftg-number-plus', function() {
		clearInterval($ftg_number_plus);
	});

	jQuery(document).on('click', '.ftg-save-main-settings', function() {
		var save_button = jQuery(this);
		if ( save_button.hasClass('save-disabled') ) {
			return;
		}
		save_button.addClass('save-disabled');

		var stream_type = jQuery('.ftg-side-settings').attr('data-type');
		var stream_id = jQuery('.ftg-side-settings').attr('data-id');
		var stream_settings = {};

		if ( stream_type == 'instagram' ) {
			stream_settings.instagram_limit = jQuery('#ftg-instagram-limit').val();
			if ( jQuery('#ftg-instagram-source-list').length ) {
				var inst_source_list = '';
				jQuery('#ftg-instagram-source-list .tagit-hidden-field').each(function() {
					inst_source_list += jQuery(this).val()+'||';
				});
				inst_source_list = inst_source_list.slice(0,-2);
				stream_settings.source_list = inst_source_list;
			} else {
				stream_settings.source_list = jQuery('#ftg-instagram-source').val();
			}

			if ( jQuery('#ftg-instagram-source-filters').length ) {
				var source_filters = {};
				jQuery('.ftg-filter-left').each(function(indx) {
					var filter_type = jQuery(this).find('select').val();
					var filter_options = '';
					jQuery(this).next().find('.tagit-hidden-field').each(function() {
						filter_options += jQuery(this).val()+'||';
					});
					filter_options = filter_options.slice(0,-2);

					if ( filter_options != '' ) {
						source_filters[indx] = filter_type+'::'+filter_options;
					}
				});

				stream_settings.source_filters = JSON.stringify(source_filters);
			}

			if ( jQuery('#ftg-instagram-cache').length ) {
				stream_settings.instagram_cache = jQuery('#ftg-instagram-cache').val();
			}

			if ( jQuery('#ftg-instagram-width').length ) {
				stream_settings.instagram_width = jQuery('#ftg-instagram-width').val();
				stream_settings.instagram_width_resp = jQuery('#ftg-instagram-width-resp').prop('checked');
				stream_settings.instagram_height = jQuery('#ftg-instagram-height').val();
				stream_settings.instagram_height_resp = jQuery('#ftg-instagram-height-resp').prop('checked');
				stream_settings.instagram_columns = jQuery('#ftg-instagram-columns').val();
				stream_settings.instagram_rows = jQuery('#ftg-instagram-rows').val();
				stream_settings.instagram_gutter = jQuery('#ftg-instagram-gutter').val();
			}

			if ( jQuery('.ftg-other-breakpoint-wrapper').length ) {
				var ftg_breakpoints = {};
				jQuery('.ftg-breakpoint-wrapper').each(function() {
					var breakpoint_width = jQuery(this).find('.ftg-instagram-breakpoint-width').val();
					ftg_breakpoints[breakpoint_width] = jQuery(this).find('.ftg-instagram-breakpoint-columns').val()+'||'+jQuery(this).find('.ftg-instagram-breakpoint-rows').val()+'||'+jQuery(this).find('.ftg-instagram-breakpoint-gutter').val()
				});
				stream_settings.breakpoints = JSON.stringify(ftg_breakpoints);
			}

			if ( jQuery('#ftg-instagram-gallery-likes').length ) {
				stream_settings.instagram_gallery_likes = jQuery('#ftg-instagram-gallery-likes').prop('checked');
				stream_settings.instagram_gallery_comments = jQuery('#ftg-instagram-gallery-comments').prop('checked');

				stream_settings.instagram_popup_username = jQuery('#ftg-instagram-popup-username').prop('checked');
				stream_settings.instagram_popup_link = jQuery('#ftg-instagram-popup-link').prop('checked');
				stream_settings.instagram_popup_likes = jQuery('#ftg-instagram-popup-likes').prop('checked');
				stream_settings.instagram_popup_comments = jQuery('#ftg-instagram-popup-comments').prop('checked');
				stream_settings.instagram_popup_location = jQuery('#ftg-instagram-popup-location').prop('checked');
				stream_settings.instagram_popup_time = jQuery('#ftg-instagram-popup-time').prop('checked');
				stream_settings.instagram_popup_description = jQuery('#ftg-instagram-popup-description').prop('checked');
				stream_settings.instagram_popup_real_comments = jQuery('#ftg-instagram-popup-real-comments').prop('checked');
			}

			if ( jQuery('#ftg-instagram-gallery-mode').length ) {
				stream_settings.instagram_gallery_mode = jQuery('#ftg-instagram-gallery-mode').val();
			}

			if ( jQuery('#ftg-instagram-gallery-layout').length ) {
				stream_settings.instagram_gallery_layout = jQuery('#ftg-instagram-gallery-layout').val();
			}
		} else if ( stream_type == 'youtube' ) {
			if ( jQuery('#ftg-youtube-source').length ) {
				stream_settings.source_list = jQuery('#ftg-youtube-source').val();
			}
			
			if ( jQuery('#ftg-youtube-cache').length ) {
				stream_settings.youtube_cache = jQuery('#ftg-youtube-cache').val();
			}

			if ( jQuery('#ftg-youtube-video-layout').length ) {
				stream_settings.youtube_video_layout = jQuery('#ftg-youtube-video-layout').val();
			}

			if ( jQuery('#ftg-instagram-width').length ) {
				stream_settings.youtube_width = jQuery('#ftg-instagram-width').val();
				stream_settings.youtube_width_resp = jQuery('#ftg-instagram-width-resp').prop('checked');
				stream_settings.youtube_height = jQuery('#ftg-instagram-height').val();
				stream_settings.youtube_height_resp = jQuery('#ftg-instagram-height-resp').prop('checked');
				stream_settings.youtube_columns = jQuery('#ftg-instagram-columns').val();
				stream_settings.youtube_rows = jQuery('#ftg-instagram-rows').val();
				stream_settings.youtube_gutter = jQuery('#ftg-instagram-gutter').val();
			}

			if ( jQuery('.ftg-other-breakpoint-wrapper').length ) {
				var ftg_breakpoints = {};
				jQuery('.ftg-breakpoint-wrapper').each(function() {
					var breakpoint_width = jQuery(this).find('.ftg-instagram-breakpoint-width').val();
					ftg_breakpoints[breakpoint_width] = jQuery(this).find('.ftg-instagram-breakpoint-columns').val()+'||'+jQuery(this).find('.ftg-instagram-breakpoint-rows').val()+'||'+jQuery(this).find('.ftg-instagram-breakpoint-gutter').val()
				});
				stream_settings.breakpoints = JSON.stringify(ftg_breakpoints);
			}

			if ( jQuery('#ftg-youtube-header-style').length ) {
				stream_settings.youtube_header_style = jQuery('#ftg-youtube-header-style').val();
			}

			stream_settings.youtube_limit = jQuery('#ftg-youtube-limit').val();

			if ( jQuery('#ftg-youtube-header-logo').length ) {
				stream_settings.youtube_header_logo = jQuery('#ftg-youtube-header-logo').prop('checked');
				stream_settings.youtube_header_banner = jQuery('#ftg-youtube-header-banner').prop('checked');
				stream_settings.youtube_header_channel_name = jQuery('#ftg-youtube-header-channel-name').prop('checked');
				stream_settings.youtube_header_channel_desc = jQuery('#ftg-youtube-header-channel-desc').prop('checked');
				stream_settings.youtube_header_videos = jQuery('#ftg-youtube-header-videos').prop('checked');
				stream_settings.youtube_header_subscribers = jQuery('#ftg-youtube-header-subscribers').prop('checked');
				stream_settings.youtube_header_views = jQuery('#ftg-youtube-header-views').prop('checked');
				stream_settings.youtube_header_sub_button = jQuery('#ftg-youtube-header-sub-button').prop('checked');
			}

			if ( jQuery('#ftg-youtube-video-mode').length ) {
				stream_settings.youtube_video_mode = jQuery('#ftg-youtube-video-mode').val();
			}

			if ( jQuery('#ftg-youtube-header-logo').length ) {
				stream_settings.youtube_video_play = jQuery('#ftg-youtube-video-play').prop('checked');
				stream_settings.youtube_video_duration = jQuery('#ftg-youtube-video-duration').prop('checked');
				stream_settings.youtube_video_title = jQuery('#ftg-youtube-video-title').prop('checked');
				stream_settings.youtube_video_date = jQuery('#ftg-youtube-video-date').prop('checked');
				stream_settings.youtube_video_desc = jQuery('#ftg-youtube-video-desc').prop('checked');
				stream_settings.youtube_video_views = jQuery('#ftg-youtube-video-views').prop('checked');
				stream_settings.youtube_video_likes = jQuery('#ftg-youtube-video-likes').prop('checked');
				stream_settings.youtube_video_comments = jQuery('#ftg-youtube-video-comments').prop('checked');
			}

			if ( jQuery('#ftg-youtube-popup-autoplay').length ) {
				stream_settings.youtube_popup_autoplay = jQuery('#ftg-youtube-popup-autoplay').prop('checked');
				stream_settings.youtube_popup_title = jQuery('#ftg-youtube-popup-title').prop('checked');
				stream_settings.youtube_popup_subscribe = jQuery('#ftg-youtube-popup-subscribe').prop('checked');
				stream_settings.youtube_popup_views = jQuery('#ftg-youtube-popup-views').prop('checked');
				stream_settings.youtube_popup_likes = jQuery('#ftg-youtube-popup-likes').prop('checked');
				stream_settings.youtube_popup_dislikes = jQuery('#ftg-youtube-popup-dislikes').prop('checked');
				stream_settings.youtube_popup_ratio = jQuery('#ftg-youtube-popup-ratio').prop('checked');
				stream_settings.youtube_popup_date = jQuery('#ftg-youtube-popup-date').prop('checked');
				stream_settings.youtube_popup_desc = jQuery('#ftg-youtube-popup-desc').prop('checked');
				stream_settings.youtube_popup_desc_more = jQuery('#ftg-youtube-popup-desc-more').prop('checked');
				stream_settings.youtube_popup_comments = jQuery('#ftg-youtube-popup-comments').prop('checked');
			}
		} else if ( stream_type == 'twitter' ) {
			stream_settings.source_list = jQuery('#ftg-twitter-source').val();
			stream_settings.twitter_cache = jQuery('#ftg-twitter-cache').val();
			stream_settings.twitter_limit = jQuery('#ftg-twitter-limit').val();

			if ( jQuery('#ftg-twitter-status-fullname').length ) {
				stream_settings.twitter_status_image = jQuery('#ftg-twitter-status-image').prop('checked');
				stream_settings.twitter_status_fullname = jQuery('#ftg-twitter-status-fullname').prop('checked');
				stream_settings.twitter_status_name = jQuery('#ftg-twitter-status-name').prop('checked');
				stream_settings.twitter_status_lock = jQuery('#ftg-twitter-status-lock').prop('checked');
				stream_settings.twitter_status_date = jQuery('#ftg-twitter-status-date').prop('checked');
				stream_settings.twitter_status_text = jQuery('#ftg-twitter-status-text').prop('checked');
				stream_settings.twitter_status_retweet = jQuery('#ftg-twitter-status-retweet').prop('checked');
				stream_settings.twitter_status_heart = jQuery('#ftg-twitter-status-heart').prop('checked');
			}

			if ( jQuery('#ftg-twitter-header-tweets').length ) {
				stream_settings.twitter_header_tweets = jQuery('#ftg-twitter-header-tweets').prop('checked');
				stream_settings.twitter_header_following = jQuery('#ftg-twitter-header-following').prop('checked');
				stream_settings.twitter_header_followers = jQuery('#ftg-twitter-header-followers').prop('checked');
				stream_settings.twitter_header_likes = jQuery('#ftg-twitter-header-likes').prop('checked');
			}

			if ( jQuery('#ftg-twitter-header-style').length ) {
				stream_settings.twitter_header_style = jQuery('#ftg-twitter-header-style').val();
			}
		}

		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'ftg_save_stream_settings',
				'ftg_stream_id': stream_id,
				'ftg_stream_settings': JSON.stringify(stream_settings)
			},
			success: function(data) {
				if ( jQuery.isNumeric(jQuery.parseJSON(data).save_response) ) {
					save_button.hide();
					jQuery('.ftg-save-main-settings-message').show();
					setTimeout(function() {
						save_button.removeClass('save-disabled');
						save_button.show();
						jQuery('.ftg-save-main-settings-message').hide();
					}, 3000);
				}
			}
		});
	});

	jQuery(document).on('click', '.ftg-delete-stream', function() {
		if ( confirm('Are you sure you want to delete this stream?') ) {
			var stream_id = jQuery(this).parent().attr('data-id');
			var stream_div = jQuery(this).parent().parent();
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					'action': 'ftg_delete_stream',
					'ftg_stream_id': stream_id,
				},
				success: function(data) {
					if ( jQuery.isNumeric(jQuery.parseJSON(data).save_response) ) {
						stream_div.remove();
					}
				}
			});
		}
	});

	jQuery(document).on('click', '.ftg-tab-item', function() {
		if ( jQuery(this).hasClass('active') ) {
			return;
		}
		jQuery('.ftg-tab-item').removeClass('active');
		jQuery(this).addClass('active');

		var active_setting = jQuery(this).attr('data-tab');
		jQuery('.ftg-tab-item-content').removeClass('active');
		jQuery('.ftg-tab-item-content[data-tab-item="'+active_setting+'"]').addClass('active');
	});

	jQuery(document).on('change', '.ftg-checkbox input', function() {
		if ( jQuery(this).prop('checked') ) {
			jQuery(this).parent().addClass('active');
		} else {
			jQuery(this).parent().removeClass('active');
		}
	});

	jQuery(document).on('input', '#ftg-instagram-width', function() {
		if ( jQuery(this).val() != '' ) {
			jQuery('.ftg-author-media').css('width', jQuery(this).val()+'px');
		} else {
			jQuery('.ftg-author-media').css('width', '');
		}
	});

	jQuery(document).on('input', '#ftg-instagram-height', function() {
		if ( jQuery(this).val() != '' ) {
			jQuery('.ftg-author-media').css('height', jQuery(this).val()+'px');
		} else {
			jQuery('.ftg-author-media').css('height', '');
		}
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

	jQuery(document).on('click', '#ftg-twitter-status-image', function() {
		if ( jQuery(this).prop('checked') ) { jQuery('.ftg-twitter-status-image').show(); } else { jQuery('.ftg-twitter-status-image').hide(); }
	});

	jQuery(document).on('click', '#ftg-twitter-status-fullname', function() {
		if ( jQuery(this).prop('checked') ) { jQuery('.ftg-twitter-content-user-name').show(); } else { jQuery('.ftg-twitter-content-user-name').hide(); }
	});

	jQuery(document).on('click', '#ftg-twitter-status-name', function() {
		if ( jQuery(this).prop('checked') ) { jQuery('.ftg-twitter-content-screenname').show(); } else { jQuery('.ftg-twitter-content-screenname').hide(); }
	});

	jQuery(document).on('click', '#ftg-twitter-status-lock', function() {
		if ( jQuery(this).prop('checked') ) { jQuery('.ftg-twitter-status-protected').show(); } else { jQuery('.ftg-twitter-status-protected').hide(); }
	});

	jQuery(document).on('click', '#ftg-twitter-status-date', function() {
		if ( jQuery(this).prop('checked') ) { jQuery('.ftg-twitter-status-date').show(); } else { jQuery('.ftg-twitter-status-date').hide(); }
	});

	jQuery(document).on('click', '#ftg-twitter-status-text', function() {
		if ( jQuery(this).prop('checked') ) { jQuery('.ftg-twitter-status-text').show(); } else { jQuery('.ftg-twitter-status-text').hide(); }
	});

	jQuery(document).on('click', '#ftg-twitter-status-retweet', function() {
		if ( jQuery(this).prop('checked') ) { jQuery('.ftg-twitter-status-retweets').show(); } else { jQuery('.ftg-twitter-status-retweets').hide(); }
	});

	jQuery(document).on('click', '#ftg-twitter-status-heart', function() {
		if ( jQuery(this).prop('checked') ) { jQuery('.ftg-twitter-status-favorites').show(); } else { jQuery('.ftg-twitter-status-favorites').hide(); }
	});

	jQuery(document).on('click', '#ftg-twitter-header-tweets', function() {
		if ( jQuery(this).prop('checked') ) { jQuery('.ftg-twitter-header-info-item.tweets').show(); } else { jQuery('.ftg-twitter-header-info-item.tweets').hide(); }
	});

	jQuery(document).on('click', '#ftg-twitter-header-following', function() {
		if ( jQuery(this).prop('checked') ) { jQuery('.ftg-twitter-header-info-item.following').show(); } else { jQuery('.ftg-twitter-header-info-item.following').hide(); }
	});

	jQuery(document).on('click', '#ftg-twitter-header-followers', function() {
		if ( jQuery(this).prop('checked') ) { jQuery('.ftg-twitter-header-info-item.followers').show(); } else { jQuery('.ftg-twitter-header-info-item.followers').hide(); }
	});

	jQuery(document).on('click', '#ftg-twitter-header-likes', function() {
		if ( jQuery(this).prop('checked') ) { jQuery('.ftg-twitter-header-info-item.likes').show(); } else { jQuery('.ftg-twitter-header-info-item.likes').hide(); }
	});

	jQuery(document).on('change', '#ftg-twitter-header-style', function() {
		if ( jQuery(this).val() == 'full' ) {
			jQuery('.ftg-twitter-wrapper').removeClass('header-sidebar').addClass('header-full');
			jQuery('.ftg-twitter-sidebar').removeClass('header-at-sidebar');
			jQuery('.ftg-twitter-user-image img').attr('src', jQuery('.ftg-twitter-user-image img').attr('src').replace('_bigger', '_400x400'));
		} else {
			jQuery('.ftg-twitter-wrapper').removeClass('header-full').addClass('header-sidebar');
			jQuery('.ftg-twitter-sidebar').addClass('header-at-sidebar');
			jQuery('.ftg-twitter-user-image img').attr('src', jQuery('.ftg-twitter-user-image img').attr('src').replace('_400x400', '_bigger'));
		}
	});
});

jQuery(window).load(function() {
	
});