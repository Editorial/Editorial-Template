
if (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i)) {
	var viewportmeta = document.querySelectorAll('meta[name="viewport"]')[0];
	if (viewportmeta) {
		viewportmeta.content = 'width=device-width, minimum-scale=1.0, maximum-scale=1.0';
		document.body.addEventListener('gesturestart',function() {
			viewportmeta.content = 'width=device-width, minimum-scale=0.25, maximum-scale=1.6';
		},false);
	}
}


$(function(){


//max-width IE6
/*if ($.browser.msie && $.browser.version < 7) {
	function maxWidth() {
		if ($('body').width() > 960) {$('body').css('width','960px');}
	}
	$(window).resize(function(){maxWidth();});
}*/

});

