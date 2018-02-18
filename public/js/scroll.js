function _id(a) {
	return $('#'+a);
}
$(document).ready(function(){
	$('a[href*="#"]').click(function() {
     	if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length && target || $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                var targetOffset = target.offset().top;
                $('html,body').animate({scrollTop: targetOffset}, 500);
                return false;
            }
       }
   });
});

var topGlobal = $('#nav-anc').offset().top;
window.onscroll =  function(){
	var scroll  = $(document).scrollTop();
	var sec     = [];
	var cont    = 0;
	var nav     = $('#nav-anc');
	var navc    = $('#navbarColor01').find('ul');
	if (scroll >= topGlobal) { 
		nav.addClass('fixed');
		navc.addClass('enfixed')
	} else {
		nav.removeClass('fixed');
		navc.removeClass('enfixed');
	};
	$('section').each(function() {
		sec[cont] = {top: _id(this.id).offset().top , id : this.id };
		cont++;
	});
	for (var i = 0; i < sec.length; i++) {
		if (scroll >= sec[i].top) {
			$('.fonty').addClass('link_anc')
			$('.fonty').removeClass('fonty');
			if (i != 0) {
				var a = $('a[href$="'+sec[i].id+'"]');
				a.removeClass('link_anc');
				a.addClass('fonty');
				$('#logo-body').removeClass('animate-bottom animate-left')
			} else {
				$('#logo-body').addClass('animate-left');
			}
		} 
	}
}