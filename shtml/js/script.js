/*
  _  _  ___ ___ _   _  ___
 |_ | \  |   | / \ |_)  |   /\  |
 |_ |_/ _|_  | \_/ | \ _|_ /--\ |_

 File: script.js
 Version: 1.0
 Authors: Matjaž Korošec, Miha Hribar (ko bos kaj dodal se ta komentar zbrise)

 */

var iDevice = (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/iPad/i)) ? true : false;
if (iDevice) {
	var viewportmeta = document.querySelectorAll('meta[name="viewport"]')[0];
	if (viewportmeta) {
		viewportmeta.content = 'width=device-width,minimum-scale=1.0,maximum-scale=1.0';
		document.body.addEventListener('gesturestart',function() {
			viewportmeta.content = 'width=device-width,minimum-scale=0.25,maximum-scale=1.6';
		},false);
	}
}


$(function(){

	//iOS label fix
	if (iDevice) {
		$('label[for]').click(function(){
			var el = $(this).attr('for');
			if ($('#' + el + '[type=radio], #' + el + '[type=checkbox]').attr('selected', !$('#' + el).attr('selected'))) return;
			else $('#' + el)[0].focus();
		});
	}

	//about us mailto
	if($('#mailto').length) {
		var m = $('#mailto');
		m.find('span:first-child').html('@').next('span').html('.');
		var t = m.text().replace(/ /g,'');
		m.html('<a href="mailto:' + t + '">' + t + '</a>');
	}

	//hidding iframe scrolls (invalid attr)
	$('#follow').attr('scrolling','no');

	
//max-width IE6
/*if ($.browser.msie && $.browser.version < 7) {
	function maxWidth() {
		if ($('body').width() > 960) {$('body').css('width','960px');}
	}
	$(window).resize(function(){maxWidth();});
}*/

});

