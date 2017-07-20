/**
 * main.js
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2015, Codrops
 * http://www.codrops.com
 */
jQuery(document).ready(function($) {

	jQuery(document).on('click', '#ftg-reset-cache', function() {
		var stream_reset = jQuery(this);
		if ( stream_reset.hasClass('cache-disabled') ) {
			return;
		}

		stream_reset.addClass('cache-disabled');

		var stream_id = jQuery(this).attr('data-id');
		var stream_type = jQuery(this).attr('data-type');
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'ftg_reset_cache',
				'ftg_stream_id': stream_id,
				'ftg_stream_type': stream_type
			},
			success: function(data) {
				if ( jQuery.isNumeric(jQuery.parseJSON(data).save_response) ) {
					stream_reset.removeClass('cache-disabled');
					ftg_get_content(stream_id);
				} else {
					stream_reset.removeClass('cache-disabled');
					alert(jQuery.parseJSON(data).save_response);
				}
			}
		});
	});

	jQuery(document).on('blur', '#ftg-youtube-source, #ftg-instagram-source, #ftg-twitter-source', function() {
		if ( jQuery(this).val() != '' ) {
			jQuery('.ftg-save-main-settings').click();
			setTimeout(function() {
				jQuery('#ftg-reset-cache').click();
			}, 1000);
		}
	});

	function ftg_get_content( stream_id ) {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: { 
				'action': 'ftg_load_stream_content',
				'ftg_stream_id': stream_id,
			},
			success: function(data) {
				jQuery('.ftg-side-preview').html('');
				jQuery('.ftg-side-preview').html(data);
				ftg_load_content_data();
			}
		});
	}

	function ftg_load_content_data() {
		if ( jQuery('#ftg-instagram-source-list').length ) {
			jQuery('#ftg-instagram-source-list, #ftg-instagram-source-filters').tagit({
				preprocessTag: function( val ) {
					if ( val && val.length > 3 && ( val.slice(0,1) == '#' || val.slice(0,1) == '@' || val.slice(0,4) == 'http' ) ) {
						if ( val.slice(0,4) == 'http' && /^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(val) ) {
							
							return val;
						} else if ( val.slice(0,1) == '#' || val.slice(0,1) == '@' ) {
							if ( val.slice(0,1) == '@' ) {
								jQuery('.ftg-media-item[data-user="'+val+'"]').removeClass('show');
							} else if ( val.slice(0,1) == '#' ) {
								jQuery('.ftg-media-item').each(function() {
									var current_media = jQuery(this);
									var media_tags = jQuery(this).attr('data-tag').split('|')
									jQuery.each(media_tags, function(ind, tag_val) {
										if ( val == '#'+tag_val ) {
											current_media.removeClass('show');
										}
									});
								});
							}
							return val;
						} else {
							return '';
						}
					} else {
						return '';
					}
				},
				afterTagAdded: function(event, ui) {
					if ( ui.tag.parent().attr('id') == 'ftg-instagram-source-filters' ) {
						ftgParseMediaJSON();
					}

					// if ( ui.tag.parent().attr('id') == 'ftg-instagram-source-list' ) {
					// 	jQuery('.ftg-save-main-settings').click();
					// 	setTimeout(function() {
					// 		jQuery('#ftg-reset-cache').click();
					// 	}, 1000);
					// }
				},
				afterTagRemoved: function(event, ui) {
					ftgParseMediaJSON();
	
					// if ( jQuery(event.target).attr('id') == 'ftg-instagram-source-filters' ) {
					// 	jQuery('.ftg-save-main-settings').click();
					// 	setTimeout(function() {
					// 		jQuery('#ftg-reset-cache').click();
					// 	}, 1000);
					// }
				}
			});
		}

		if ( jQuery('#ftg-instagram-columns').length ) {
			var ftg_initial_load = jQuery('#ftg-instagram-columns').val()*jQuery('#ftg-instagram-rows').val();
			jQuery('.ftg-author-media').attr('data-media-initial', ftg_initial_load);
		}

		// Parse preview media
		ftgParseMediaJSON();

		if ( jQuery('.ftg-insta-load-more').length ) {
			jQuery('.ftg-insta-load-more').parent().hide();
		}

		if ( jQuery('.ftg-main-wrapper').hasClass('ftg-layout-slider') ) {
			setTimeout(function() {
				jQuery('.ftg-slider-row').width(jQuery('.ftg-channel-info').width());
			}, 1000)
		}

		if ( jQuery('.ftg-main-wrapper.youtube').hasClass('ftg-layout-grid') ) {
			jQuery('.ftg-main-wrapper.youtube .ftg-slider-row').first().show();
		} else if ( jQuery('.ftg-main-wrapper.youtube').hasClass('ftg-layout-slider') ) {
			jQuery('.ftg-main-wrapper.youtube .ftg-slider-row').show();
		}

		if ( jQuery('#ftg-youtube-video-layout').val() == 'slider' ) {
			jQuery('.ftg-channel-video-wrapper .ftg-video-next, .ftg-channel-video-wrapper .ftg-video-prev').show();
			jQuery('.ftg-main-wrapper .ftg-youtube-load-more').hide();
		} else {
			jQuery('.ftg-channel-video-wrapper .ftg-video-next, .ftg-channel-video-wrapper .ftg-video-prev').hide();
			jQuery('.ftg-main-wrapper .ftg-youtube-load-more').show();
		}
	}

	if ( jQuery('#ftg-setting-sidebar').length ) {
		var bodyEl = document.body,
			docElem = window.document.documentElement,
			support = { transitions: Modernizr.csstransitions },
			// transition end event name
			transEndEventNames = { 'WebkitTransition': 'webkitTransitionEnd', 'MozTransition': 'transitionend', 'OTransition': 'oTransitionEnd', 'msTransition': 'MSTransitionEnd', 'transition': 'transitionend' },
			transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ],
			onEndTransition = function( el, callback ) {
				var onEndCallbackFn = function( ev ) {
					if( support.transitions ) {
						if( ev.target != this ) return;
						this.removeEventListener( transEndEventName, onEndCallbackFn );
					}
					if( callback && typeof callback === 'function' ) { callback.call(this); }
				};
				if( support.transitions ) {
					el.addEventListener( transEndEventName, onEndCallbackFn );
				}
				else {
					onEndCallbackFn();
				}
			},
			gridEl = document.getElementById('ftg-main-wrapper'),
			sidebarEl = document.getElementById('ftg-setting-sidebar'),
			gridItemsContainer = gridEl.querySelector('.ftg-stream-element-wrapper'),
			contentItemsContainer = gridEl.querySelector('section.content'),
			gridItems = gridItemsContainer.querySelectorAll('.ftg-stream-element-container'),
			contentItems = contentItemsContainer.querySelectorAll('.content__item'),
			closeCtrl = contentItemsContainer.querySelector('.close-button'),
			current = -1,
			lockScroll = false, xscroll, yscroll,
			isAnimating = false,
			menuCtrl = document.getElementById('menu-toggle'),
			menuCloseCtrl = sidebarEl.querySelector('.close-button');		
		}

	/**
	 * gets the viewport width and height
	 * based on http://responsejs.com/labs/dimensions/
	 */
	function getViewport( axis ) {
		var client, inner;
		if( axis === 'x' ) {
			client = docElem['clientWidth'];
			inner = window['innerWidth'];
		}
		else if( axis === 'y' ) {
			client = docElem['clientHeight'];
			inner = window['innerHeight'];
		}
		
		return client < inner ? inner : client;
	}
	function scrollX() { return window.pageXOffset || docElem.scrollLeft; }
	function scrollY() { return window.pageYOffset || docElem.scrollTop; }

	function init() {
		initEvents();
	}

	function initEvents() {
		[].slice.call(gridItems).forEach(function(item, pos) {
			// grid item click event
			item.addEventListener('click', function(ev) {
				ev.preventDefault();
				var selected_text = '';
				if (window.getSelection) {
					selected_text = window.getSelection().toString();
				}
				if(isAnimating || current === pos || selected_text != '' || jQuery(ev.target.outerHTML).hasClass('ftg-delete-stream') ) {
					return false;
				}
				isAnimating = true;
				// index of current item
				current = pos;
				// simulate loading time..
				classie.add(item, 'grid__item--loading');
				classie.add(item, 'grid__item--animate');
				// reveal/load content after the last element animates out (todo: wait for the last transition to finish)
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: { 
						'action': 'ftg_load_stream',
						'ftg_stream_id': jQuery(item).find('.ftg-stream-element').attr('data-id'),
					},
					success: function(data) {
						jQuery('.content .content__item').html(data);
						loadContent(item);
						ftg_load_content_data();
					}
				});
			});
		});

		closeCtrl.addEventListener('click', function() {
			// hide content
			hideContent();
		});

		// keyboard esc - hide content
		document.addEventListener('keydown', function(ev) {
			if(!isAnimating && current !== -1) {
				var keyCode = ev.keyCode || ev.which;
				if( keyCode === 27 ) {
					ev.preventDefault();
					if ("activeElement" in document)
    					document.activeElement.blur();
					hideContent();
				}
			}
		} );
	}

	jQuery(document).on('input', '#ftg-instagram-limit', function() {
		jQuery('.ftg-author-media').attr('data-limit', jQuery(this).val());
		ftgParseMediaJSON();

		if ( jQuery('.ftg-main-wrapper-inner').hasClass('ftg-layout-slider') ) {
			jQuery('.ftg-slider-container .ftg-slider-row').width(jQuery('.ftg-slider-wrapper').width());
		}
	});

	jQuery(document).on('input', '#ftg-instagram-columns, #ftg-instagram-rows', function() {
		jQuery('.ftg-author-media').attr('data-media-initial', jQuery('#ftg-instagram-columns').val()*jQuery('#ftg-instagram-rows').val());
		jQuery('.ftg-author-media').attr('data-col', jQuery('#ftg-instagram-columns').val());
		jQuery('.ftg-author-media').attr('data-rows', jQuery('#ftg-instagram-rows').val());
		ftgParseMediaJSON();

		if ( jQuery('.ftg-main-wrapper-inner').hasClass('ftg-layout-slider') ) {
			jQuery('.ftg-slider-container .ftg-slider-row').width(jQuery('.ftg-slider-wrapper').width());
		}
	});

	jQuery(document).on('change', '#ftg-instagram-gallery-likes', function() {
		if ( jQuery(this).prop('checked') ) {
			jQuery('.ftg-overlay-meta .ftg-media-item-likes').show();
		} else {
			jQuery('.ftg-overlay-meta .ftg-media-item-likes').hide();
		}
	});

	jQuery(document).on('change', '#ftg-instagram-gallery-comments', function() {
		if ( jQuery(this).prop('checked') ) {
			jQuery('.ftg-overlay-meta .ftg-media-item-comments').show();
		} else {
			jQuery('.ftg-overlay-meta .ftg-media-item-comments').hide();
		}
	});

	jQuery(document).on('click', '.ftg-media-item > a', function() {
		e.preventDefault();
	});

	jQuery(document).on('change', '#ftg-instagram-gallery-layout', function() {
		jQuery('.ftg-main-wrapper-inner').removeClass('ftg-layout-grid, ftg-layout-slider');
		jQuery('.ftg-main-wrapper-inner').addClass('ftg-layout-'+jQuery(this).val());

		ftgParseMediaJSON();

		if ( jQuery('.ftg-main-wrapper-inner').hasClass('ftg-layout-slider') ) {
			jQuery('.ftg-slider-container .ftg-slider-row').width(jQuery('.ftg-slider-wrapper').width());
		}
	});

	jQuery(document).on('input', '#ftg-youtube-limit', function() {
		var video_limit = jQuery(this).val();
		if ( video_limit >= 0 ) {
			jQuery('.ftg-video-item').each(function(indx) {
				if (indx <= video_limit) {
					jQuery(this).show();
				} else {
					jQuery(this).hide();
				}
			});
		} else {
			jQuery('.ftg-video-item').show();
		}
	});

	jQuery(document).on('input', '#ftg-twitter-limit', function() {
		var tweet_limit = jQuery(this).val();

		if ( tweet_limit > 0 ) {
			jQuery('.ftg-twitter-status-item').each(function(indx) {
				if (indx+1 <= tweet_limit) {
					jQuery(this).show();
				} else {
					jQuery(this).hide();
				}
			});
		} else {
			jQuery('.ftg-video-item').show();
		}
	});

	jQuery(document).on('input', '#ftg-instagram-width', function() {
		if ( jQuery(this).val() != '' ) {
			jQuery('.ftg-main-wrapper').css('width', jQuery(this).val()+'px');
		} else {
			jQuery('.ftg-main-wrapper').css('width', '');
		}

		if ( jQuery('.ftg-main-wrapper').hasClass('ftg-layout-slider') ) {
			jQuery('.ftg-slider-row').width(jQuery('.ftg-channel-info').width());
		}
	});

	jQuery(document).on('input', '#ftg-instagram-columns, #ftg-instagram-rows', function() {
		var all_videos = jQuery.parseJSON(jQuery('#ftg-youtube-cache-json').html());
		jQuery('.ftg-channel-videos').html('');

		var parsed_videos = '<div class="ftg-slider-row">';
		jQuery.each(all_videos.video_data, function(vid_index, vid_html) {
			parsed_videos += '<div class="ftg-video-item ftg-mode-popup" data-video="'+vid_html.id+'">';
				parsed_videos += '<div class="ftg-video-image ftgicon-play">';
					parsed_videos += '<img src="'+vid_html.snippet.thumbnails.medium.url+'" alt="">';
					if ( vid_html.contentDetails.duration) {
						parsed_videos += '<span class="ftg-video-duration">'+ftg_convert_duration(vid_html.contentDetails.duration)+'</span>';
					}
				parsed_videos += '</div>';
				parsed_videos += '<div class="ftg-video-info">';
					parsed_videos += '<a href="https://www.youtube.com/watch?v='+vid_html.id+'" class="ftg-video-link" target="_blank">'+vid_html.snippet.title+'</a>';
					var ftg_parsed_date = new Date(vid_html.snippet.publishedAt);
					var month = ftg_parsed_date.getMonth()+1;
					if ( month < 10 ) {
						month = '0'+month;
					}
					var day = ftg_parsed_date.getDate();
					if ( day < 10 ) {
						day = '0'+day;
					}
					parsed_videos += '<span class="ftg-video-date">'+month+'/'+day+'/'+ftg_parsed_date.getFullYear()+'</span>';
					parsed_videos += '<div class="ftg-video-desc">'+vid_html.snippet.description.substr(0,200)+'...</div>';
					parsed_videos += '<div class="ftg-video-meta clearfix"><span class="ftg-video-meta-item ftgicon-eye">'+ftg_format_numbers(vid_html.statistics.viewCount)+'</span><span class="ftg-video-meta-item ftgicon-thumbs-up-alt">'+ftg_format_numbers(vid_html.statistics.likeCount)+'</span><span class="ftg-video-meta-item ftgicon-comment-1">'+ftg_format_numbers(vid_html.statistics.commentCount)+'</span></div>';
				parsed_videos += '</div>';
			parsed_videos += '</div>';

			if ( (vid_index+1) % (jQuery('#ftg-instagram-columns').val()*jQuery('#ftg-instagram-rows').val()) == 0 ) {
				parsed_videos += '</div><div class="ftg-slider-row" style="display: none;">';
			}
		});
		parsed_videos += '</div>';

		jQuery('.ftg-channel-videos').append(parsed_videos);

		jQuery('.ftg-channel-videos .ftg-slider-row').css('width', jQuery('.ftg-channel-video-wrapper').outerWidth());
		jQuery('.ftg-slider-row .ftg-video-item').css('width', (100/jQuery('#ftg-instagram-columns').val())+'%');
	});

	function ftg_format_numbers( number ) {
		var number_format = 0;

		if (number < 1000) {
	        // Anything less than a thousand
	       number_format = parseInt(number);
	    } else if (number < 1000000) {
	        // Anything less than a million
	       number_format = parseInt(number / 1000) + 'k';
	    } else if (number < 1000000000) {
	        // Anything less than a billion
	        number_format = parseInt(number / 1000000) + 'm';
	    } else {
	        // At least a billion
	        number_format = parseInt(number / 1000000000) + 'b';
	    }

	    return number_format;
	}

	function ftg_convert_duration( youtube_time ) {
		youtube_time = youtube_time.replace('PT', '');
		var parsed_time = {};
		var parsed_time_value = '';
		if ( youtube_time.indexOf('H') >= 0 && youtube_time.indexOf('M') >= 0 && youtube_time.indexOf('S') >= 0 ) {
			youtube_time = youtube_time.replace('H', '==').replace('M', '==').replace('S', '');
			youtube_time = youtube_time.split('==');
			parsed_time['hours'] = youtube_time['0'];
			parsed_time['minutes'] = youtube_time['1'];
			parsed_time['seconds'] = youtube_time['2'];
		} else if ( youtube_time.indexOf('M') >= 0 && youtube_time.indexOf('S') >= 0 ) {
			youtube_time = youtube_time.replace('M', '==').replace('S', '');
			youtube_time = youtube_time.split('==');
			parsed_time['hours'] = '0';
			parsed_time['minutes'] = youtube_time['0'];
			parsed_time['seconds'] = youtube_time['1'];
		} else if ( youtube_time.indexOf('M') >= 0 ) {
			youtube_time = youtube_time.replace('M', '');
			youtube_time = youtube_time.split('==');
			parsed_time['hours'] = '0';
			parsed_time['minutes'] = youtube_time['0'];
			parsed_time['seconds'] = '0';
		} else if ( youtube_time.indexOf('S') >= 0 ) {
			youtube_time = youtube_time.replace('S', '');
			youtube_time = youtube_time.split('==');
			parsed_time['hours'] = '0';
			parsed_time['minutes'] = '0';
			parsed_time['seconds'] = youtube_time['0'];
		}

		jQuery.each(parsed_time, function(indx, time_value) {
			if ( time_value < 10 ) {
				parsed_time[indx] = '0'+time_value;
			}
		});

		jQuery.each(parsed_time, function(indx, time_value) {
			if ( time_value != '00' ) {
				parsed_time_value += time_value+':';
			}
		});
		
		return parsed_time_value.slice(0, -1);
	}

	jQuery(document).on('input', '#ftg-instagram-gutter', function() {
		jQuery('.ftg-slider-row .ftg-video-item').css('padding', (jQuery(this).val()/2)+'px');
	});

	jQuery(document).on('change', '#ftg-youtube-header-logo, #ftg-youtube-header-banner, #ftg-youtube-header-channel-name, #ftg-youtube-header-channel-desc, #ftg-youtube-header-videos, #ftg-youtube-header-subscribers, #ftg-youtube-header-views, #ftg-youtube-header-sub-button', function() {
		if ( jQuery('#ftg-youtube-header-logo').prop('checked') ) {
			jQuery('.ftg-channel-info .ftg-channel-header img').show();
		} else {
			jQuery('.ftg-channel-info .ftg-channel-header img').hide();
		}

		if ( jQuery('#ftg-youtube-header-banner').prop('checked') ) {
			jQuery('.ftg-channel-info .ftg-channel-header').removeClass('ftg-hide-bg');
		} else {
			jQuery('.ftg-channel-info .ftg-channel-header').addClass('ftg-hide-bg');
		}

		if ( jQuery('#ftg-youtube-header-channel-name').prop('checked') ) {
			jQuery('.ftg-channel-info .ftg-channel-name').show();
		} else {
			jQuery('.ftg-channel-info .ftg-channel-name').hide();
		}

		if ( jQuery('#ftg-youtube-header-channel-desc').prop('checked') ) {
			jQuery('.ftg-channel-info .ftg-channel-description').show();
		} else {
			jQuery('.ftg-channel-info .ftg-channel-description').hide();
		}

		if ( jQuery('#ftg-youtube-header-videos').prop('checked') ) {
			jQuery('.ftg-channel-header-meta .ftg-channel-header-meta-item.ftgicon-youtube-play').show();
		} else {
			jQuery('.ftg-channel-header-meta .ftg-channel-header-meta-item.ftgicon-youtube-play').hide();
		}

		if ( jQuery('#ftg-youtube-header-subscribers').prop('checked') ) {
			jQuery('.ftg-channel-header-meta .ftg-channel-header-meta-item.ftgicon-user').show();
		} else {
			jQuery('.ftg-channel-header-meta .ftg-channel-header-meta-item.ftgicon-user').hide();
		}

		if ( jQuery('#ftg-youtube-header-views').prop('checked') ) {
			jQuery('.ftg-channel-header-meta .ftg-channel-header-meta-item.ftgicon-eye').show();
		} else {
			jQuery('.ftg-channel-header-meta .ftg-channel-header-meta-item.ftgicon-eye').hide();
		}

		if ( jQuery('#ftg-youtube-header-sub-button').prop('checked') ) {
			jQuery('.ftg-channel-info .ftg-channel-subscribe').show();
		} else {
			jQuery('.ftg-channel-info .ftg-channel-subscribe').hide();
		}
	});

	jQuery(document).on('change', '#ftg-youtube-video-play, #ftg-youtube-video-duration, #ftg-youtube-video-title, #ftg-youtube-video-date, #ftg-youtube-video-desc, #ftg-youtube-video-views, #ftg-youtube-video-likes, #ftg-youtube-video-comments', function() {
		if ( jQuery('#ftg-youtube-video-play').prop('checked') ) {
			jQuery('.ftg-video-item .ftg-video-image').addClass('ftgicon-play');
		} else {
			jQuery('.ftg-video-item .ftg-video-image').removeClass('ftgicon-play');
		}

		if ( jQuery('#ftg-youtube-video-duration').prop('checked') ) {
			jQuery('.ftg-video-image .ftg-video-duration').show();
		} else {
			jQuery('.ftg-video-image .ftg-video-duration').hide();
		}

		if ( jQuery('#ftg-youtube-video-title').prop('checked') ) {
			jQuery('.ftg-video-item .ftg-video-link').show();
		} else {
			jQuery('.ftg-video-item .ftg-video-link').hide();
		}

		if ( jQuery('#ftg-youtube-video-date').prop('checked') ) {
			jQuery('.ftg-video-info .ftg-video-date').show();
		} else {
			jQuery('.ftg-video-info .ftg-video-date').hide();
		}

		if ( jQuery('#ftg-youtube-video-desc').prop('checked') ) {
			jQuery('.ftg-video-info .ftg-video-desc').show();
		} else {
			jQuery('.ftg-video-info .ftg-video-desc').hide();
		}

		if ( jQuery('#ftg-youtube-video-views').prop('checked') ) {
			jQuery('.ftg-video-meta .ftg-video-meta-item.ftgicon-eye').show();
		} else {
			jQuery('.ftg-video-meta .ftg-video-meta-item.ftgicon-eye').hide();
		}

		if ( jQuery('#ftg-youtube-video-likes').prop('checked') ) {
			jQuery('.ftg-video-meta .ftg-video-meta-item.ftgicon-thumbs-up-alt').show();
		} else {
			jQuery('.ftg-video-meta .ftg-video-meta-item.ftgicon-thumbs-up-alt').hide();
		}

		if ( jQuery('#ftg-youtube-video-comments').prop('checked') ) {
			jQuery('.ftg-video-meta .ftg-video-meta-item.ftgicon-comment-1').show();
		} else {
			jQuery('.ftg-video-meta .ftg-video-meta-item.ftgicon-comment-1').hide();
		}

	});

	jQuery(document).on('change', '#ftg-youtube-video-layout', function() {
		jQuery('.ftg-main-wrapper.youtube').removeClass('ftg-layout-slider').removeClass('ftg-layout-grid');
		jQuery('.ftg-main-wrapper.youtube').addClass('ftg-layout-'+jQuery(this).val());
		jQuery('.ftg-main-wrapper.youtube .ftg-slider-row').hide();
		if ( jQuery(this).val() == 'slider' ) {
			jQuery('.ftg-channel-videos .ftg-slider-row').css('width', jQuery('.ftg-channel-video-wrapper').outerWidth());
			jQuery('.ftg-channel-video-wrapper .ftg-video-next, .ftg-channel-video-wrapper .ftg-video-prev').show();
			jQuery('.ftg-main-wrapper .ftg-youtube-load-more').hide();
			jQuery('.ftg-main-wrapper.youtube .ftg-slider-row').show();
		} else {
			jQuery('.ftg-channel-video-wrapper .ftg-video-next, .ftg-channel-video-wrapper .ftg-video-prev').hide();
			jQuery('.ftg-main-wrapper .ftg-youtube-load-more').show();
			jQuery('.ftg-main-wrapper.youtube .ftg-slider-row').first().show();
		}
	});

	jQuery(document).on('change', '#ftg-youtube-header-style', function() {
		jQuery('.ftg-channel-info').removeClass('ftg-style-minimal').removeClass('ftg-style-full');
		jQuery('.ftg-channel-info').addClass('ftg-style-'+jQuery(this).val());
	});

	function ftgFilterMedia( tag_type, tag_list, media_data ) {
		var show_image = false;
		if ( tag_type == 'only' ) {
			if ( ftg_check_if_url_present(tag_list) !== false ) {
				var valid_urls = ftg_check_if_url_present(tag_list);
				jQuery.each(valid_urls, function(index, url_value) {
					if ( media_data.link.indexOf(url_value) >= 0 ) {
						// Correct media URL
						if ( ftg_check_if_username_present(tag_list) !== false ) {
							var valid_users = ftg_check_if_username_present(tag_list);
							jQuery.each(valid_users, function(index, user_value) {
								if ( '@'+media_data.user.username == user_value ) {
									// Correct media username
									if ( ftg_check_if_tag_present(tag_list) !== false ) {
										var valid_tags = ftg_check_if_tag_present(tag_list);
										jQuery.each(valid_tags, function(index, tag_value) {
											if ( jQuery.inArray(tag_value.replace('#', ''), media_data.tags) !== -1 ) {
												// Correct media tag
												show_image = true;
												return false;
											}
										});
									} else {
										show_image = true;
									}
								}
							});
						} else if ( ftg_check_if_tag_present(tag_list) !== false ) {
							var valid_users = ftg_check_if_username_present(tag_list);
							jQuery.each(valid_users, function(index, tag_value) {
								if ( jQuery.inArray(tag_value.replace('#', ''), media_data.tags) !== -1 ) {
									show_image = true;
									return false;
								}
							});
						} else {
							show_image = true;
						}
					}
				});
			} else if ( ftg_check_if_username_present(tag_list) !== false ) {
				var valid_users = ftg_check_if_username_present(tag_list);
				jQuery.each(valid_users, function(index, user_value) {
					if ( '@'+media_data.user.username == user_value ) {
						// Correct media username
						if ( ftg_check_if_tag_present(tag_list) !== false ) {
							var valid_tags = ftg_check_if_tag_present(tag_list);
							jQuery.each(valid_tags, function(index, tag_value) {
								if ( jQuery.inArray(tag_value.replace('#', ''), media_data.tags) !== -1 ) {
									// Correct media tag
									show_image = true;
									return false;
								}
							});
						} else {
							show_image = true;
						}
					}
				});
			} else if ( ftg_check_if_tag_present(tag_list) !== false ) {
				var valid_tags = ftg_check_if_tag_present(tag_list);
				jQuery.each(valid_tags, function(index, tag_value) {
					if ( jQuery.inArray(tag_value.replace('#', ''), media_data.tags) !== -1 ) {
						// Correct media tag
						show_image = true;
						return false;
					}
				});
			}
		} else if ( tag_type == 'except' ) {
			show_image = true;
			if ( ftg_check_if_url_present(tag_list) !== false ) {
				var valid_urls = ftg_check_if_url_present(tag_list);
				jQuery.each(valid_urls, function(index, url_value) {
					if ( media_data.link.indexOf(url_value) >= 0 ) {
						// Correct media URL
						if ( ftg_check_if_username_present(tag_list) !== false ) {
							var valid_users = ftg_check_if_username_present(tag_list);
							jQuery.each(valid_users, function(index, user_value) {
								if ( '@'+media_data.user.username == user_value ) {
									// Correct media username
									if ( ftg_check_if_tag_present(tag_list) !== false ) {
										var valid_tags = ftg_check_if_tag_present(tag_list);
										jQuery.each(valid_tags, function(index, tag_value) {
											if ( jQuery.inArray(tag_value.replace('#', ''), media_data.tags) !== -1 ) {
												// Correct media tag
												show_image = false;
												return false;
											}
										});
									} else {
										show_image = false;
									}
								}
							});
						} else if ( ftg_check_if_tag_present(tag_list) !== false ) {
							var valid_users = ftg_check_if_username_present(tag_list);
							jQuery.each(valid_users, function(index, tag_value) {
								if ( jQuery.inArray(tag_value.replace('#', ''), media_data.tags) !== -1 ) {
									show_image = false;
									return false;
								}
							});
						} else {
							show_image = false;
						}
					}
				});
			} else if ( ftg_check_if_username_present(tag_list) !== false ) {
				var valid_users = ftg_check_if_username_present(tag_list);
				jQuery.each(valid_users, function(index, user_value) {
					if ( '@'+media_data.user.username == user_value ) {
						// Correct media username
						if ( ftg_check_if_tag_present(tag_list) !== false ) {
							var valid_tags = ftg_check_if_tag_present(tag_list);
							jQuery.each(valid_tags, function(index, tag_value) {
								if ( jQuery.inArray(tag_value.replace('#', ''), media_data.tags) !== -1 ) {
									// Correct media tag
									show_image = false;
									return false;
								}
							});
						} else {
							show_image = false;
						}
					}
				});
			} else if ( ftg_check_if_tag_present(tag_list) !== false ) {
				var valid_tags = ftg_check_if_tag_present(tag_list);

				jQuery.each(valid_tags, function(index, tag_value) {
					if ( jQuery.inArray(tag_value.replace('#', ''), media_data.tags) !== -1 ) {
						// Correct media tag
						show_image = false;
						return false;
					}
				});
			}
		}

		return show_image;
	}

	function ftg_check_if_url_present( filter_list ) {
		var filter_url = false;
		var url_array = [];
		if ( filter_list.length > 1 ) {
			jQuery.each(filter_list, function(indx,filter_var) {
				if ( /^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(filter_var) ) {
					filter_url = true;
					url_array.push(filter_var);
				}
			});
		}

		if ( filter_url === false ) {
			return false;
		} else {
			return url_array;
		}
	}

	function ftg_check_if_username_present( filter_list ) {
		var filter_user = false;
		var user_array = [];
		if ( filter_list.length > 1 ) {
			jQuery.each(filter_list, function(indx,filter_var) {
				if ( filter_var.slice(0,1) == '@' ) {
					filter_user = true;
					user_array.push(filter_var);
				}
			});
		}

		if ( filter_user === false ) {
			return false;
		} else {
			return user_array;
		}
	}

	function ftg_check_if_tag_present( filter_list ) {
		var filter_tag = false;
		var tag_array = [];
		if ( filter_list.length > 1 ) {
			jQuery.each(filter_list, function(indx,filter_var) {
				if ( filter_var.slice(0,1) == '#' ) {
					filter_tag = true;
					tag_array.push(filter_var);
				}
			});
		}

		if ( filter_tag === false ) {
			return false;
		} else {
			return tag_array;
		}
	}

	function ftgParseMediaJSON() {
		if ( jQuery('#ftg-media-json').length ) {
			jQuery('.ftg-author-media .ftg-media-item, .ftg-author-media .ftg-slider-row').remove();
			var insta_media_data = jQuery.parseJSON(jQuery('#ftg-media-json').html());

			var current_html = '';
			var insta_columns = parseInt(jQuery('.ftg-author-media').attr('data-col'));
			var insta_rows = parseInt(jQuery('.ftg-author-media').attr('data-rows'));
			var media_from = parseInt(jQuery('.ftg-author-media').attr('data-media-initial'));
			var insta_limit = parseInt(jQuery('.ftg-author-media').attr('data-limit'));
			if ( jQuery('.ftg-main-wrapper-inner').hasClass('ftg-layout-slider') ) {
				media_from = 9999;
				jQuery('.ftg-insta-load-more').hide();
			}
			if ( jQuery('.ftg-main-wrapper-inner').hasClass('ftg-layout-slider') ) {
				current_html += '<div class="ftg-slider-wrapper"><div class="ftg-slider-container"><div class="ftg-slider-row">';
			}
			var displayed_images = 0;
			jQuery.each(insta_media_data, function(med_indx, med_value) {
				if ( insta_limit != 0 && displayed_images == insta_limit ) {
					return false;
				}
				var curr_media_data = jQuery.parseJSON(med_value);

				var show_image = true;
				jQuery('.ftg-filter-right').each(function(indx, filter_content) {
					var filter_type = jQuery(this).parent().find('.ftg-filter-left select').val();
					var filter_elements = [];
					jQuery(filter_content).find('li').each(function(indx, filter_val) {
						filter_elements.push(jQuery(filter_val).find('.tagit-label').text());
					});

					if ( filter_elements.length > 1 ) {
						show_image = ftgFilterMedia( filter_type, filter_elements, curr_media_data );
					}
				});

				if ( show_image === false ) {
					return true;
				}

				if ( med_indx < media_from ) {
					var media_tags = '';
					if ( curr_media_data.tags.length ) {
						jQuery.each( curr_media_data.tags, function(indx, tag_val) {
							media_tags += tag_val+'|';
						});
						media_tags = media_tags.slice(0,-1);
					}
					current_html += '<div class="ftg-media-item show" data-tag="'+media_tags+'" data-user="@'+curr_media_data.user.username+'"><a href="'+curr_media_data.link+'" target="_blank"><img src="'+curr_media_data.images.low_resolution.url+'" alt=""><div class="ftg-media-item-overlay"><div class="ftg-overlay-meta"><span class="ftg-media-item-likes ftgicon-heart-1">'+curr_media_data.likes.count+'</span><span class="ftg-media-item-comments ftgicon-comment-1">'+curr_media_data.comments.count+'</span></div></div></a><div class="ftg-media-item-dialog" style="display: none;"><span class="ftg-media-dialog-close ftgicon-cancel"></span><div class="ftg-media-dialog-image"><img src="'+curr_media_data.images.standard_resolution.url+'" alt=""></div><div class="ftg-media-dialog-side"><div class="ftg-media-dialog-side-meta"><img src="'+curr_media_data.user.profile_picture+'" class="ftg-media-dialog-profile" alt=""><a href="https://instagram.com/'+curr_media_data.user.username+'" class="ftg-media-dialog-link" target="_blank">'+curr_media_data.user.username+'</a><a href="'+curr_media_data.link+'" target="_blank" class="ftg-media-dialog-instagram">View on instagram</a><div class="clearfix"></div><span class="ftg-media-dialog-likes ftgicon-heart-empty">'+curr_media_data.likes.count+'</span><span class="ftg-media-dialog-comments ftgicon-comment-empty">'+curr_media_data.comments.count+'</span><span class="ftg-media-dialog-date">'+curr_media_data.human_date+' ago</span></div><div class="ftg-media-dialog-caption">';
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
					displayed_images++;
				}
			});

			if ( jQuery('.ftg-main-wrapper-inner').hasClass('ftg-layout-slider') ) {
				current_html += '</div></div><a href="javascript:void(0)" class="ftg-slider-next ftgicon-angle-right"></a><a href="javascript:void(0)" class="ftg-slider-prev ftgicon-angle-left"></a></div>';
			}

			jQuery('.ftg-author-media').append(current_html);
		}
	}

	function loadContent(item) {
		// add expanding element/placeholder 
		var dummy = document.createElement('div');
		dummy.className = 'placeholder';

		// set the width/heigth and position
		dummy.style.WebkitTransform = 'translate3d(' + (item.offsetLeft - 5) + 'px, ' + (item.offsetTop - 5) + 'px, 0px) scale3d(' + item.offsetWidth/gridItemsContainer.offsetWidth + ',' + item.offsetHeight/getViewport('y') + ',1)';
		dummy.style.transform = 'translate3d(' + (item.offsetLeft - 5) + 'px, ' + (item.offsetTop - 5) + 'px, 0px) scale3d(' + item.offsetWidth/gridItemsContainer.offsetWidth + ',' + item.offsetHeight/getViewport('y') + ',1)';

		// add transition class 
		classie.add(dummy, 'placeholder--trans-in');

		// insert it after all the grid items
		gridItemsContainer.appendChild(dummy);
		
		// body overlay
		classie.add(bodyEl, 'view-single');

		setTimeout(function() {
			// expands the placeholder
			dummy.style.WebkitTransform = 'translate3d(-5px, ' + (scrollY() - 5) + 'px, 0px)';
			dummy.style.transform = 'translate3d(-5px, ' + (scrollY() - 5) + 'px, 0px)';
			// disallow scroll
			window.addEventListener('scroll', noscroll);
		}, 25);

		onEndTransition(dummy, function() {
			// add transition class 
			classie.remove(dummy, 'placeholder--trans-in');
			classie.add(dummy, 'placeholder--trans-out');
			// position the content container
			contentItemsContainer.style.top = scrollY() + 'px';
			// show the main content container
			classie.add(contentItemsContainer, 'content--show');
			// show content item:
			classie.add(contentItems[0], 'content__item--show');
			// show close control
			classie.add(closeCtrl, 'close-button--show');
			// sets overflow hidden to the body and allows the switch to the content scroll
			classie.addClass(bodyEl, 'noscroll');

			isAnimating = false;
		});
	}

	function hideContent() {
		var gridItem = gridItems[current], contentItem = contentItems[0];

		classie.remove(contentItem, 'content__item--show');
		classie.remove(contentItemsContainer, 'content--show');
		classie.remove(closeCtrl, 'close-button--show');
		classie.remove(bodyEl, 'view-single');

		setTimeout(function() {
			var dummy = gridItemsContainer.querySelector('.placeholder');

			classie.removeClass(bodyEl, 'noscroll');

			dummy.style.WebkitTransform = 'translate3d(' + gridItem.offsetLeft + 'px, ' + gridItem.offsetTop + 'px, 0px) scale3d(' + gridItem.offsetWidth/gridItemsContainer.offsetWidth + ',' + gridItem.offsetHeight/getViewport('y') + ',1)';
			dummy.style.transform = 'translate3d(' + gridItem.offsetLeft + 'px, ' + gridItem.offsetTop + 'px, 0px) scale3d(' + gridItem.offsetWidth/gridItemsContainer.offsetWidth + ',' + gridItem.offsetHeight/getViewport('y') + ',1)';

			onEndTransition(dummy, function() {
				// reset content scroll..
				contentItem.parentNode.scrollTop = 0;
				gridItemsContainer.removeChild(dummy);
				classie.remove(gridItem, 'grid__item--loading');
				classie.remove(gridItem, 'grid__item--animate');
				lockScroll = false;
				window.removeEventListener( 'scroll', noscroll );
			});
			
			// reset current
			current = -1;
		}, 25);
	}

	function noscroll() {
		if(!lockScroll) {
			lockScroll = true;
			xscroll = scrollX();
			yscroll = scrollY();
		}
		window.scrollTo(xscroll, yscroll);
	}

	if ( jQuery('#ftg-setting-sidebar').length ) {
		init();
	}

});