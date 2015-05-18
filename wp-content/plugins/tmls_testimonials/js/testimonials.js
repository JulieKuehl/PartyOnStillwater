(function($){
$(window).ready(function(){
	
	/*======================== Slider ========================*/
	
	tmls_sliders = $('.tmls_slider, .tmls_slider2');
	tmls_style3_names = $('.style3 .tmls_name');
	tmls_visible_slider_buttons = $('.tmls_next_prev.tmls_visible');
	tmls_form_submit = $('.tmls_form_submit');
	
	if (tmls_form_submit.length )
	{
		tmls_form_submit.mouseover(function(){
			$(this).css('color',$(this).attr('data-hoverfontcolor'));
			$(this).css('border-color',$(this).attr('data-hoverbordercolor'));
			$(this).css('background-color',$(this).attr('data-hoverbgcolor'));
		});
		
		tmls_form_submit.mouseleave(function(){
			$(this).css('color',$(this).attr('data-fontcolor'));
			$(this).css('border-color',$(this).attr('data-bordercolor'));
			$(this).css('background-color',$(this).attr('data-bgcolor'));
		});
	}
	
	if (tmls_sliders.length )
	{
		tmls_sliders.each(function(){
			
			tmls_slider_play($(this));
			
			$(this).parent().parent().mouseenter(function(){
				$(this).children('.tmls_show_on_hover').slideToggle();
			});
			
			$(this).parent().parent().mouseleave(function(){
				$(this).children('.tmls_show_on_hover').slideToggle();
			});
			
		});
		
		tmls_visible_slider_buttons.fadeIn();
		
		$(window).resize(function() {
			tmls_sliders.each(function(){
				tmls_slider_play($(this));
			});
			
			
			if(tmls_style3_names.length) {
				tmls_style3_names.each(function(){
					
					tmls_style3_infos_height_sum = ($(this).height()+ 2.5 + $(this).parent().children('.tmls_position').height()+$(this).parent().children('.tmls_rating').height()+5);
					tmls_style3_img_height = $(this).parent().children('.tmls_image').height();
					
					if(tmls_style3_infos_height_sum < tmls_style3_img_height && $(this).parent().children('.tmls_image').css('display')!='none' ) {
						$(this).css('padding-top', (tmls_style3_img_height/2) - (tmls_style3_infos_height_sum/2));
					}
					else {
						$(this).css('padding-top',0);
					}
					
				});
			}
			
			
		});

		
	}
	
	
	
	if(tmls_style3_names.length) {
		tmls_style3_names.each(function(){
			
			tmls_style3_infos_height_sum = ($(this).height()+ 2.5 + $(this).parent().children('.tmls_position').height()+$(this).parent().children('.tmls_rating').height()+5);
			tmls_style3_img_height = $(this).parent().children('.tmls_image').height();
			
			if(tmls_style3_infos_height_sum < tmls_style3_img_height && $(this).parent().children('.tmls_image').css('display')!='none' ) {
				$(this).css('padding-top', (tmls_style3_img_height/2) - (tmls_style3_infos_height_sum/2));
			}
			else {
				$(this).css('padding-top',0);
			}
			
		});
	}
	
	
		
});


$(window).load(function(){
	tmls_sliders.each(function(){
		tmls_slider_play($(this));
	});
});


function tmls_slider_play(tmls_slider) {
	
	tmls_slider.carouFredSel({
		responsive: true,
		width:'variable',
		height:'variable',
		prev: {
			button: function() {
				return $(this).parents().children(".tmls_next_prev").children(".tmls_prev");
			}
		},
		next: {
			button: function() {
				return $(this).parents().children(".tmls_next_prev").children(".tmls_next");
			}
		},
		pagination: {
			container: function() {
				return $(this).parents().next().children('.tmls_paginationContainer');
			},
			anchorBuilder	: function(nr) {
				return "<div class='tmls_image_container "+$(this).attr('data-imageradius')+"'><div class='tmls_image' style='"+$(this).attr('data-bgimg')+"'></div><div class='tmls_image_overlay' style='background-color:"+$(this).parent().attr('data-slider2unselectedoverlaybgcolor')+"'></div></div>";
			}
		},
		scroll: {
			items:1,          
			duration: tmls_slider.data('scrollduration'),
			fx: tmls_slider.data('transitioneffect')
		},
		auto: {
			play: tmls_slider.data('autoplay'),
			timeoutDuration:tmls_slider.data('pauseduration'),
			pauseOnHover:tmls_slider.data('pauseonhover')
		},
		items: {
			width:700
		},
		swipe: {
			onMouse: false,
			onTouch: true
		}
				
	});
			
}

}) (jQuery);