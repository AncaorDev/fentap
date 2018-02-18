$(document).ready(function(e) {
	$(' #slider_wrap').lc_micro_slider({
		slide_fx : 'zoom-out',
		animation_time	: 3000,
		slideshow_time	: 10000,
		nav_arrows : true,  
		slideshow_cmd : true,
		nav_dots : false,
		autoplay: true,
		pause_on_hover: false
	});
	//// ken burns effect
	// get random value for random direction
	function lcms_kenburns_random_vert() {
		var vals = ["top", "botom"];
       return vals[Math.floor(Math.random() * vals.length)];
	}
	function lcms_kenburns_random_horiz() {
		var vals = ["left", "right"];
		return vals[Math.floor(Math.random() * vals.length)];
	}

	// catch event	
	$(document).on('lcms_initial_slide_shown lcms_new_active_slide', '#slider_wrap', function(e, slide_index) {	

		var time = $(this).data('lcms_settings').slideshow_time;

		var props = {};
		props[ lcms_kenburns_random_vert() ] = '-20%';
		props[ lcms_kenburns_random_horiz() ] = '-20%';

		$(this).find('.lcms_slide[rel='+slide_index+'] .lcms_bg').animate(props, time, 'linear'); 
	});

});