(function($){
$(window).load(function(){
	
	// inputs
	
	tmls_form_width = $('#tmls_form_width');
	tmls_notificationEmail = $('#tmls_notificationEmail');
	tmls_emailTo = $('#tmls_emailTo');
	tmls_emailSubject = $('#tmls_emailSubject');
	tmls_emailMessage = $('#tmls_emailMessage');
	tmls_success_message = $('#tmls_success_message');
	tmls_namerequired_message = $('#tmls_namerequired_message');
	tmls_emailrequired_message = $('#tmls_emailrequired_message');
	tmls_testimonialrequired_message = $('#tmls_testimonialrequired_message');
	tmls_invalidemail_message = $('#tmls_invalidemail_message');
	tmls_invalidcompanywebsite_message = $('#tmls_invalidcompanywebsite_message');
	tmls_captchaanswerrequired_message = $('#tmls_captchaanswerrequired_message');
	tmls_invalidcaptchaanswer_message = $('#tmls_invalidcaptchaanswer_message');
	tmls_alreadysent_message = $('#tmls_alreadysent_message');
	tmls_label_fontcolor = $('#tmls_label_fontcolor');
	tmls_label_fontsize = $('#tmls_label_fontsize');
	tmls_label_fontweight = $('#tmls_label_fontweight');
	tmls_label_fontfamily = $('#tmls_label_fontfamily');
	tmls_validationmessage_fontcolor = $('#tmls_validationmessage_fontcolor');
	tmls_validationmessage_fontsize = $('#tmls_validationmessage_fontsize');
	tmls_validationmessage_fontweight = $('#tmls_validationmessage_fontweight');
	tmls_validationmessage_fontfamily = $('#tmls_validationmessage_fontfamily');
	tmls_successmessage_fontcolor = $('#tmls_successmessage_fontcolor');
	tmls_successmessage_fontsize = $('#tmls_successmessage_fontsize');
	tmls_successmessage_fontweight = $('#tmls_successmessage_fontweight');
	tmls_successmessage_fontfamily = $('#tmls_successmessage_fontfamily');
	tmls_inputs_fontcolor = $('#tmls_inputs_fontcolor');
	tmls_inputs_fontsize = $('#tmls_inputs_fontsize');
	tmls_inputs_fontweight = $('#tmls_inputs_fontweight');
	tmls_inputs_fontfamily = $('#tmls_inputs_fontfamily');
	tmls_inputs_bordercolor = $('#tmls_inputs_bordercolor');
	tmls_inputs_bgcolor = $('#tmls_inputs_bgcolor');
	tmls_inputs_borderradius = $('#tmls_inputs_borderradius');
	tmls_name_label_text = $('#tmls_name_label_text');
	tmls_position_label_text = $('#tmls_position_label_text');
	tmls_companyname_label_text = $('#tmls_companyname_label_text');
	tmls_companywebsite_label_text = $('#tmls_companywebsite_label_text');
	tmls_email_label_text = $('#tmls_email_label_text');
	tmls_rating_label_text = $('#tmls_rating_label_text');
	tmls_testimonial_label_text = $('#tmls_testimonial_label_text');
	tmls_captcha_label_text = $('#tmls_captcha_label_text');
	tmls_button_text = $('#tmls_button_text');
	tmls_button_fontcolor = $('#tmls_button_fontcolor');
	tmls_button_fontsize = $('#tmls_button_fontsize');
	tmls_button_fontweight = $('#tmls_button_fontweight');
	tmls_button_fontfamily = $('#tmls_button_fontfamily');
	tmls_button_bordercolor = $('#tmls_button_bordercolor');
	tmls_button_bgcolor = $('#tmls_button_bgcolor');
	tmls_button_borderradius = $('#tmls_button_borderradius');
	tmls_button_hover_fontcolor = $('#tmls_button_hover_fontcolor');
	tmls_button_hover_bordercolor = $('#tmls_button_hover_bordercolor');
	tmls_button_hover_bgcolor = $('#tmls_button_hover_bgcolor');
	tmls_captcha_encryption_key = $('#tmls_captcha_encryption_key');
	
	
	tmls_controls = $('input,select');
	tmls_buttons = $('.button-primary');
	
	// containers
	
	tmls_form_div_shortcode = $('#tmls_form_div_shortcode');
	
	tmls_form_gene_short_preview = $('#tmls_form_gene_short_preview');
	
	// Sections titles and rows containers
	tmls_sectionsTitles = $('.tmls_sectionTitle');
	tmls_rowsContainers = $('.tmls_rowsContainer');
	
	// options rows
	notificationEmail_options = $('.notificationEmail_options');
	
	notificationEmail_options.slideUp('slow');
	
	generate_shortcode();
	
	
	tmls_controls.change(function(){
		generate_shortcode();
	});
	
	tmls_buttons.click(function(){
		generate_shortcode();
	});
	
	// Notification Email
	tmls_notificationEmail.change(function(){
	
		if( tmls_notificationEmail.val() == 'disabled' ) {
			notificationEmail_options.slideUp('slow');
		}
		else {
			notificationEmail_options.slideDown('slow');
		}
		
	});
	
	
	
	tmls_sectionsTitles.click(function(){
		tmls_rowsContainers.removeClass('tmls_rowsContainerOpend');
		$(this).next().addClass('tmls_rowsContainerOpend');
	});
	

});

function generate_shortcode() {
	
	var postarray = {};
	var shortcode='[tmls_form ';
	
	postarray['form_width'] = tmls_form_width.val();
	shortcode+='form_width="'+tmls_form_width.val()+'" ';
	
	if( tmls_notificationEmail.val() == 'enabled' ) {
	
		postarray['notificationemail'] = tmls_notificationEmail.val();
		shortcode+='notificationemail="'+tmls_notificationEmail.val()+'" ';
		
		postarray['emailto'] = tmls_emailTo.val();
		shortcode+='emailto="'+tmls_emailTo.val()+'" ';
		
		postarray['emailsubject'] = tmls_emailSubject.val();
		shortcode+='emailsubject="'+tmls_emailSubject.val()+'" ';
		
		postarray['emailmessage'] = tmls_emailMessage.val();
		shortcode+='emailmessage="'+tmls_emailMessage.val()+'" ';
		
	}
	
	postarray['success_message'] = tmls_success_message.val();
	shortcode+='success_message="'+tmls_success_message.val()+'" ';
	
	postarray['namerequired_message'] = tmls_namerequired_message.val();
	shortcode+='namerequired_message="'+tmls_namerequired_message.val()+'" ';
	
	postarray['emailrequired_message'] = tmls_emailrequired_message.val();
	shortcode+='emailrequired_message="'+tmls_emailrequired_message.val()+'" ';
	
	postarray['testimonialrequired_message'] = tmls_testimonialrequired_message.val();
	shortcode+='testimonialrequired_message="'+tmls_testimonialrequired_message.val()+'" ';
	
	postarray['invalidemail_message'] = tmls_invalidemail_message.val();
	shortcode+='invalidemail_message="'+tmls_invalidemail_message.val()+'" ';
	
	postarray['invalidcompanywebsite_message'] = tmls_invalidcompanywebsite_message.val();
	shortcode+='invalidcompanywebsite_message="'+tmls_invalidcompanywebsite_message.val()+'" ';
	
	postarray['captchaanswerrequired_message'] = tmls_captchaanswerrequired_message.val();
	shortcode+='captchaanswerrequired_message="'+tmls_captchaanswerrequired_message.val()+'" ';
	
	postarray['invalidcaptchaanswer_message'] = tmls_invalidcaptchaanswer_message.val();
	shortcode+='invalidcaptchaanswer_message="'+tmls_invalidcaptchaanswer_message.val()+'" ';
	
	postarray['alreadysent_message'] = tmls_alreadysent_message.val();
	shortcode+='alreadysent_message="'+tmls_alreadysent_message.val()+'" ';
	
	
	postarray['label_fontcolor'] = tmls_label_fontcolor.val();
	shortcode+='label_fontcolor="'+tmls_label_fontcolor.val()+'" ';
	
	postarray['label_fontsize'] = tmls_label_fontsize.val();
	shortcode+='label_fontsize="'+tmls_label_fontsize.val()+'" ';
	
	postarray['label_fontweight'] = tmls_label_fontweight.val();
	shortcode+='label_fontweight="'+tmls_label_fontweight.val()+'" ';
	
	postarray['label_fontfamily'] = tmls_label_fontfamily.val();
	shortcode+='label_fontfamily="'+tmls_label_fontfamily.val()+'" ';
	
	postarray['validationmessage_fontcolor'] = tmls_validationmessage_fontcolor.val();
	shortcode+='validationmessage_fontcolor="'+tmls_validationmessage_fontcolor.val()+'" ';
	
	postarray['validationmessage_fontsize'] = tmls_validationmessage_fontsize.val();
	shortcode+='validationmessage_fontsize="'+tmls_validationmessage_fontsize.val()+'" ';
	
	postarray['validationmessage_fontweight'] = tmls_validationmessage_fontweight.val();
	shortcode+='validationmessage_fontweight="'+tmls_validationmessage_fontweight.val()+'" ';
	
	postarray['validationmessage_fontfamily'] = tmls_validationmessage_fontfamily.val();
	shortcode+='validationmessage_fontfamily="'+tmls_validationmessage_fontfamily.val()+'" ';
	
	postarray['successmessage_fontcolor'] = tmls_successmessage_fontcolor.val();
	shortcode+='successmessage_fontcolor="'+tmls_successmessage_fontcolor.val()+'" ';
	
	postarray['successmessage_fontsize'] = tmls_successmessage_fontsize.val();
	shortcode+='successmessage_fontsize="'+tmls_successmessage_fontsize.val()+'" ';
	
	postarray['successmessage_fontweight'] = tmls_successmessage_fontweight.val();
	shortcode+='successmessage_fontweight="'+tmls_successmessage_fontweight.val()+'" ';
	
	postarray['successmessage_fontfamily'] = tmls_successmessage_fontfamily.val();
	shortcode+='successmessage_fontfamily="'+tmls_successmessage_fontfamily.val()+'" ';
	
	postarray['inputs_fontcolor'] = tmls_inputs_fontcolor.val();
	shortcode+='inputs_fontcolor="'+tmls_inputs_fontcolor.val()+'" ';
	
	postarray['inputs_fontsize'] = tmls_inputs_fontsize.val();
	shortcode+='inputs_fontsize="'+tmls_inputs_fontsize.val()+'" ';
	
	postarray['inputs_fontweight'] = tmls_inputs_fontweight.val();
	shortcode+='inputs_fontweight="'+tmls_inputs_fontweight.val()+'" ';
	
	postarray['inputs_fontfamily'] = tmls_inputs_fontfamily.val();
	shortcode+='inputs_fontfamily="'+tmls_inputs_fontfamily.val()+'" ';
	
	postarray['inputs_bordercolor'] = tmls_inputs_bordercolor.val();
	shortcode+='inputs_bordercolor="'+tmls_inputs_bordercolor.val()+'" ';
	
	postarray['inputs_bgcolor'] = tmls_inputs_bgcolor.val();
	shortcode+='inputs_bgcolor="'+tmls_inputs_bgcolor.val()+'" ';
	
	postarray['inputs_borderradius'] = tmls_inputs_borderradius.val();
	shortcode+='inputs_borderradius="'+tmls_inputs_borderradius.val()+'" ';
	
	postarray['name_label_text'] = tmls_name_label_text.val();
	shortcode+='name_label_text="'+tmls_name_label_text.val()+'" ';
	
	postarray['position_label_text'] = tmls_position_label_text.val();
	shortcode+='position_label_text="'+tmls_position_label_text.val()+'" ';
	
	postarray['companyname_label_text'] = tmls_companyname_label_text.val();
	shortcode+='companyname_label_text="'+tmls_companyname_label_text.val()+'" ';
	
	postarray['companywebsite_label_text'] = tmls_companywebsite_label_text.val();
	shortcode+='companywebsite_label_text="'+tmls_companywebsite_label_text.val()+'" ';
	
	postarray['email_label_text'] = tmls_email_label_text.val();
	shortcode+='email_label_text="'+tmls_email_label_text.val()+'" ';
	
	postarray['rating_label_text'] = tmls_rating_label_text.val();
	shortcode+='rating_label_text="'+tmls_rating_label_text.val()+'" ';
	
	postarray['testimonial_label_text'] = tmls_testimonial_label_text.val();
	shortcode+='testimonial_label_text="'+tmls_testimonial_label_text.val()+'" ';
	
	postarray['captcha_label_text'] = tmls_captcha_label_text.val();
	shortcode+='captcha_label_text="'+tmls_captcha_label_text.val()+'" ';
	
	postarray['button_text'] = tmls_button_text.val();
	shortcode+='button_text="'+tmls_button_text.val()+'" ';
	
	postarray['button_fontcolor'] = tmls_button_fontcolor.val();
	shortcode+='button_fontcolor="'+tmls_button_fontcolor.val()+'" ';
	
	postarray['button_fontsize'] = tmls_button_fontsize.val();
	shortcode+='button_fontsize="'+tmls_button_fontsize.val()+'" ';
	
	postarray['button_fontweight'] = tmls_button_fontweight.val();
	shortcode+='button_fontweight="'+tmls_button_fontweight.val()+'" ';
	
	postarray['button_fontfamily'] = tmls_button_fontfamily.val();
	shortcode+='button_fontfamily="'+tmls_button_fontfamily.val()+'" ';
	
	postarray['button_bordercolor'] = tmls_button_bordercolor.val();
	shortcode+='button_bordercolor="'+tmls_button_bordercolor.val()+'" ';
	
	postarray['button_bgcolor'] = tmls_button_bgcolor.val();
	shortcode+='button_bgcolor="'+tmls_button_bgcolor.val()+'" ';
	
	postarray['button_borderradius'] = tmls_button_borderradius.val();
	shortcode+='button_borderradius="'+tmls_button_borderradius.val()+'" ';
	
	postarray['button_hover_fontcolor'] = tmls_button_hover_fontcolor.val();
	shortcode+='button_hover_fontcolor="'+tmls_button_hover_fontcolor.val()+'" ';
	
	postarray['button_hover_bordercolor'] = tmls_button_hover_bordercolor.val();
	shortcode+='button_hover_bordercolor="'+tmls_button_hover_bordercolor.val()+'" ';
	
	postarray['button_hover_bgcolor'] = tmls_button_hover_bgcolor.val();
	shortcode+='button_hover_bgcolor="'+tmls_button_hover_bgcolor.val()+'" ';
	
	postarray['captcha_encryption_key'] = tmls_captcha_encryption_key.val();
	shortcode+='captcha_encryption_key="'+tmls_captcha_encryption_key.val()+'" ';
	
	shortcode+=']';
	
	tmls_form_div_shortcode.html(shortcode);
	
	tmls_form_gene_short_preview.html('<p>Loading ...</p>');
	
	tmls_form_gene_short_preview.load('../wp-content/plugins/tmls_testimonials/inc/form_generate_shortcode/do_shortcode.php', postarray , function(){
		
		$.getScript('../wp-content/plugins/tmls_testimonials/js/testimonials.js');
		
		if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){

			jQuery( '#tmls_label_fontcolor' ).wpColorPicker();
			jQuery( '#tmls_inputs_fontcolor' ).wpColorPicker();
			jQuery( '#tmls_inputs_bordercolor' ).wpColorPicker();
			jQuery( '#tmls_inputs_bgcolor' ).wpColorPicker();
			jQuery( '#tmls_validationmessage_fontcolor' ).wpColorPicker();
			jQuery( '#tmls_successmessage_fontcolor' ).wpColorPicker();
			jQuery( '#tmls_button_fontcolor' ).wpColorPicker();
			jQuery( '#tmls_button_bordercolor' ).wpColorPicker();
			jQuery( '#tmls_button_bgcolor' ).wpColorPicker();
			jQuery( '#tmls_button_hover_fontcolor' ).wpColorPicker();
			jQuery( '#tmls_button_hover_bordercolor' ).wpColorPicker();
			jQuery( '#tmls_button_hover_bgcolor' ).wpColorPicker();

		}
		else {
			//We use farbtastic if the WordPress color picker widget doesn't exist
			jQuery('#tmls_label_fontcolor_colorpicker').farbtastic('#tmls_label_fontcolor');
			jQuery('#tmls_inputs_fontcolor_colorpicker').farbtastic('#tmls_inputs_fontcolor');
			jQuery('#tmls_inputs_bordercolor_colorpicker').farbtastic('#tmls_inputs_bordercolor');
			jQuery('#tmls_inputs_bgcolor_colorpicker').farbtastic('#tmls_inputs_bgcolor');
			jQuery('#tmls_validationmessage_fontcolor_colorpicker').farbtastic('#tmls_validationmessage_fontcolor');
			jQuery('#tmls_successmessage_fontcolor_colorpicker').farbtastic('#tmls_successmessage_fontcolor');
			jQuery('#tmls_button_fontcolor_colorpicker').farbtastic('#tmls_button_fontcolor');
			jQuery('#tmls_button_bordercolor_colorpicker').farbtastic('#tmls_button_bordercolor');
			jQuery('#tmls_button_bgcolor_colorpicker').farbtastic('#tmls_button_bgcolor');
			jQuery('#tmls_button_hover_fontcolor_colorpicker').farbtastic('#tmls_button_hover_fontcolor');
			jQuery('#tmls_button_hover_bordercolor_colorpicker').farbtastic('#tmls_button_hover_bordercolor');
			jQuery('#tmls_button_hover_bgcolor_colorpicker').farbtastic('#tmls_button_hover_bgcolor');
			
			tmls_farbtastic_inputs = $('#tmls_label_fontcolor,#tmls_inputs_fontcolor,#tmls_inputs_bordercolor,#tmls_inputs_bgcolor,#tmls_validationmessage_fontcolor,#tmls_successmessage_fontcolor,#tmls_button_fontcolor,#tmls_button_bordercolor,#tmls_button_bgcolor,#tmls_button_hover_fontcolor,#tmls_button_hover_bordercolor,#tmls_button_hover_bgcolor');
			
			tmls_farbtastic_inputs.focus(function(){
				$(this).parent().children('.tmls_farbtastic').slideDown();
			});
			
			tmls_farbtastic_inputs.focusout(function(){
				$(this).parent().children('.tmls_farbtastic').slideUp();
			});
		}		
				
	
	});
	
	
}


})(jQuery);