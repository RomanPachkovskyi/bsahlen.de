jQuery(document).ready(function($){
	"use strict";
	
	// jquery for show/hide option  in admin panel
	var backVideo = $('.back_video').parents('tr');
	var backImg = $('.back_img').parents('tr');
	var embedUrl = $('.embed_url').parents('tr');
	var videoTypeSel = $('.video_type_select').parents('tr');


	if($('#lasoon_background_type-unable').is(':checked')){
		videoTypeSel.hide();
		backVideo.hide();
		embedUrl.hide();
	} else {
		if($('#lasoon_background_video_type-unable').is(':checked')){
			backVideo.show();
			embedUrl.hide();
		} else {
			backVideo.hide();
			embedUrl.show();
		}
		backImg.hide();
	}
	$('input:radio[name="lasoon_background_type"]').on('change', function(){
		if($('#lasoon_background_type-unable').is(':checked')){
			backImg.show();
			videoTypeSel.hide();
			backVideo.hide();
			embedUrl.hide();
			
		} else {
			backImg.hide();
			videoTypeSel.show();
			if($('#lasoon_background_video_type-unable').is(':checked')){
				backVideo.show();
				embedUrl.hide();
			} else {
				backVideo.hide();
				embedUrl.show();
			}	

		}
	});


	$('input:radio[name="lasoon_background_video_type"]').on('change', function(){
		if($('#lasoon_background_video_type-unable').is(':checked')){
			backVideo.show();
			embedUrl.hide();
		} else {
			backVideo.hide();
			embedUrl.show();
		}
	});

	// for subheading show/hide
	var subtitle = $('#wp-lasoon_heading_subtitle-wrap').parents('tr');

	if(!$('#lasoon_subtitle_enable-unable').is(':checked')){
		subtitle.hide();
	}

	$('input:radio[name="lasoon_subtitle_enable"]').on('change', function(){
		if($('#lasoon_subtitle_enable-unable').is(':checked')){
			subtitle.show();		
		} else {
			subtitle.hide();
		}
	});

	// for countdown show/hide
	var countdown = $('#lasoon_launch_date').parents('tr');

	if(!$('#lasoon_countdown_enable-unable').is(':checked')){
		countdown.hide();
	}

	$('input:radio[name="lasoon_countdown_enable"]').on('change', function(){
		if($('#lasoon_countdown_enable-unable').is(':checked')){
			countdown.show();		
		} else {
			countdown.hide();
		}
	});

	// for about us section show/hide
	var aboutust = $('#lasoon_about_head').parents('tr');
	var aboutusd = $('#wp-lasoon_about_desc-wrap').parents('tr');

	if(!$('#lasoon_about_show-unable').is(':checked')){
		aboutust.hide();
		aboutusd.hide();
	}

	$('input:radio[name="lasoon_about_show"]').on('change', function(){
		if($('#lasoon_about_show-unable').is(':checked')){
			aboutust.show();
			aboutusd.show();
		} else {
			aboutust.hide();
			aboutusd.hide();
		}
	});

	// for get in touch section show/hide
	var githead = $('#lasoon_git_head').parents('tr');
	var gitdesc = $('#wp-lasoon_git_desc-wrap').parents('tr');
	var gitcontact = $('#lasoon_git_contact').parents('tr');
	var gitemail = $('#lasoon_git_email').parents('tr');
	var gitaddress = $('#wp-lasoon_git_address-wrap').parents('tr');

	if(!$('#lasoon_git_show-unable').is(':checked')){
		githead.hide();
		gitdesc.hide();
		gitcontact.hide();
		gitemail.hide();
		gitaddress.hide();
	}

	$('input:radio[name="lasoon_git_show"]').on('change', function(){
		if($('#lasoon_git_show-unable').is(':checked')){
			githead.show();
			gitdesc.show();
			gitcontact.show();
			gitemail.show();
			gitaddress.show();
		} else {
			githead.hide();
			gitdesc.hide();
			gitcontact.hide();
			gitemail.hide();
			gitaddress.hide();
		}
	});




	// SELECT 2 jquery
	$('.lasoon-chosen-select').select2();

	// for preview image
	$('select').on('change', function() {
		var id = $(this).attr('id')+"_preview";
		var img_url = $('option:selected', this).data('img');
		$("#"+id+' .preview_image').attr("src",img_url);
	});
	// for file uploader
	$('body').on('click', '.lasoon-upload', function(e) {
		
		e.preventDefault();

		var activeFileUploadContext = $(this).parent();

		var customFileFrame = wp.media.frames.customHeader = wp.media({
			title: $(this).data('choose'),
			button: { text: $(this).data('update') }
		});

		customFileFrame.on('select', function() {
			var attachment = customFileFrame.state().get("selection").first();
			$('input', activeFileUploadContext).val(attachment.attributes.url).trigger('change');
			$('.lasoon-upload', activeFileUploadContext).hide();
			$('.lasoon-upload-remove', activeFileUploadContext).show();
		});

		customFileFrame.open();
	});

	$('body').on('click', '.lasoon-upload-remove', function(e) {

		e.preventDefault();

		var activeFileUploadContext = $(this).parent();
		$('input', activeFileUploadContext).val('');
		$(this).prev().fadeIn('slow');
		$(this).fadeOut('slow');
	});

	$(window).on('load', function(){
		jQuery('.lasoon-settings-content .notice').remove();
	});
});

