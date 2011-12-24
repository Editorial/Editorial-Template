/*
  _  _  ___ ___ _   _  ___
 |_ | \  |   | / \ |_)  |   /\  |
 |_ |_/ _|_  | \_/ | \ _|_ /~~\ |_

 File: script.js
 Version: 1.0 (11/2011)
 Authors: Matjaz Korosec (twitter.com/matjazkorosec),
          Miha Hribar (twitter.com/mihahribar)

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
			$('#domains').append('<li><label for="domain-'+i+'">Domain '+i+'</label><input type="text" name="domain[]" id="domain-'+i+'" value="http://"></li>');
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
	
	// purchase discount check
	$('#promo').change(function(e){
		var code = $.trim($(this).val());
		if (code.length) {
			// send ajax request
			$.get(
				"/purchase/",
				{promo: code},
				function(data){
					var promo = $.parseJSON(data);
					// update price
					var currency = $('#price-c').val().substr(0,1);
					$('#price-c').val(currency + promo.price);
					// update total
					$('#licenses-c').change();
				}
			);
		}
		return;
	});

});

