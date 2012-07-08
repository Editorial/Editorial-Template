/*
  _  _  ___ ___ _   _  ___
 |_ | \  |   | / \ |_)  |   /\  |
 |_ |_/ _|_  | \_/ | \ _|_ /~~\ |_

 File: script.js
 Version: 1.0 (11/2011)
 Authors: Matjaz Korosec (twitter.com/matjazkorosec),
          Miha Hribar (twitter.com/mihahribar)

 */

// open / close features nav
var featuresNav = function() {
	var b = $('body'),
			fBar = $('#features-bar'),
			fBarWidth = fBar.width();
	fBar.stop();
	if (b.hasClass('active-sidebar')) {
		fBar.animate({right: '-' + fBarWidth + 'px'}, 100);
		b.removeClass('active-sidebar');
	}
	else {
		fBar.animate({right:'0'}, 150);
		b.addClass('active-sidebar');
	}
};

(function() {

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

	// add/remove body classes based on viewport width
	var bodyViewportClasses = function() {
	// testing the display property on the element
	var b = $('body'),
			nav_display = $('#features-links').css('display');
		// testing for display:block (changed in css via media queries)
		if (nav_display === 'block') {
			b.removeClass('big-screen').addClass('small-screen');
		}
		// testing for display:none (changed in css via media queries)
		if (nav_display === 'none') {
			b.removeClass('active-sidebar small-screen').addClass('big-screen');
		}
	};

	//off-canvas
	if ($('body').hasClass('features-new')) {

		bodyViewportClasses();

		$(window).resize(function(){
			bodyViewportClasses();
		});

		//features nav
		$('#show-features').on('click',function(e){
			e.preventDefault();
			featuresNav();
		});

		//geastures
		function log(event_, obj) {
		// ignore bubbled handlers
		//		if ( obj.originalEvent.currentTarget !== obj.originalEvent.target ) { return; }
			obj.originalEvent.preventDefault();
			//console.log(obj.description);
			if (obj.description.lastIndexOf('swipe') != -1 && obj.description.lastIndexOf('left') != -1) featuresNav();
			if (obj.description == 'tapone') {
				if(parseInt($('#features-bar').css('right')) == 0) {
					featuresNav();
				}
			}
		}

		$('.main').on('swipeone',log);
		$('.main').on('tapone',log);

	}

	//iOS label fix
	if (iDevice) {
		$('label[for]').click(function(){
			var el = $(this).attr('for');
			if ($('#' + el + '[type=radio], #' + el + '[type=checkbox]').attr('selected', !$('#' + el).attr('selected'))) return;
			else $('#' + el)[0].focus();
		});
	}

	//footer tweets
	$('.twitter').liveTwitter('editorialtheme',{mode:'user_timeline',limit:1});

	//redirect button
	$('#checkout').click(function(e){
		e.preventDefault();
		var redirect = function(){$('#buy-form').submit();};
		$(this).val('Redirecting').addClass('redirecting');
		$(this).after('<div id="checkout-loading" />');
		var cl = new CanvasLoader('checkout-loading');
		cl.setColor('#999999');
		cl.setDiameter(25);
		cl.setDensity(31);
		cl.setRange(1);
		cl.setFPS(30);
		cl.show();
		var r = setTimeout(redirect,500);
	});

	//transaction loading
	if($('#transaction-loading').length) {
		var cl = new CanvasLoader('transaction-loading');
		cl.setColor('#999999');
		cl.setDiameter(25);
		cl.setDensity(31);
		cl.setRange(1);
		cl.setFPS(30);
		cl.show();
	}

	//about us mailto
	if($('#mailto').length) {
		var m = $('#mailto');
		m.find('span:first-child').html('@').next('span').html('.');
		var t = m.text().replace(/ /g,'');
		m.html('<a href="mailto:' + t + '">' + t + '</a>');
	}

	// buy form add/remove domains
	$('#licenses-c').change(function(e) {
		// add domain input field
		function addDomain(i) {
			$('#domains').append('<li><label for="domain-'+i+'">Domain '+i+'</label><input type="text" name="domain[]" id="domain-'+i+'"></li>');
		}
		var domains = parseInt($(this).val());
		if (domains > 0) {
			var entered = $('#domains input').length;
			if (domains > entered) {
				// add domains
				for (var i = entered+1; i <= domains; i++) {
					addDomain(i);
				}
			}
			else if (domains < entered) {
				// remove domains
				for (var i = domains+1; i <= entered; i++) {
					$('#domain-'+i).parent().remove();
				}
			}
			// update total price
			var currency = $('#price-c').val().substr(0,1);
			$('#total').val(currency+domains*parseFloat($('#price-c').val().substr(1)));
		}
	});

})();

