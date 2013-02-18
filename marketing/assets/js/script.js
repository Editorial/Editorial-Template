/*
  _  _  ___ ___ _   _  ___
 |_ | \  |   | / \ |_)  |   /\  |
 |_ |_/ _|_  | \_/ | \ _|_ /~~\ |_

 File: script.js
 Version: 1.0 (11/2011)
 Authors: Matjaz Korosec (twitter.com/matjazkorosec),
          Miha Hribar (twitter.com/mihahribar)

 */

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

	//Off canvas
	var page = $('body');

	$('#show-features').on('click', function(e) {
		e.preventDefault();
		changeActive();
	});

	var changeActive = function() {
		if (page.hasClass('not-active')) {
			page.removeClass('not-active').addClass('active-sidebar');
		} else if (page.hasClass('active-sidebar')) {
			page.removeClass('active-sidebar').addClass('not-active');
		}
	};

	page.removeClass('active-sidebar').addClass('not-active');

	$(window).on('resize', function() {
		page.removeClass('active-sidebar').addClass('not-active');
	});

	//iOS label fix
	if (iDevice) {
		$('label[for]').click(function(){
			var el = $(this).attr('for');
			if ($('#' + el + '[type=radio], #' + el + '[type=checkbox]').attr('selected', !$('#' + el).attr('selected'))) return;
			else $('#' + el)[0].focus();
		});
	}

	//footer tweets
	//$('.twitter').liveTwitter('editorialtheme',{mode:'user_timeline',limit:1});

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





	//price flow
	if($('ul.price-flow').length) {

		function flow(soldLicences) {
			var mobile = $('ul.pf-mobile'),
					graph = (mobile.css('display') == 'block') ? mobile : $('ul.pf-other'),
					countActiveSteps = 0,
					pass = 0,
					limits = [];

			graph.find('li').removeClass('current').removeClass('sold').each(function(){
				var t =  $(this).find('span').text();
				if(t != '') limits.push(t);
			});

			for(var i = 0; i + 1 < limits.length + 1; i++) {
				var nthI = graph.find('li:nth-child(' + i + ')'),
						nthPlus1 = graph.find('li:nth-child(' + parseInt(i + 1, 10) + ')');
				//active & current
				if (parseInt(limits[i], 10) <= soldLicences) {
					nthI.addClass('active');
					countActiveSteps++;
					if (parseInt(limits[i+1], 10) > soldLicences) {
						nthPlus1.addClass('current');
					}
				} else {
					nthI.removeClass('active');
				}
			}

			if (countActiveSteps > 2) {

				var first = 0;
				for(var j = countActiveSteps; j > 0 ; j--) {
					var nthJ = graph.find('li:nth-child(' + j + ')'),
							isStep = nthJ.attr('class').match(/step-/i) ? true : false,
							price = nthJ.find('em').text();
					if(pass === 1) {
						nthJ.removeClass('active').addClass('sold');
					}
					if (isStep) {
						pass = 1;
					}
					//update price tag's price
					//if (first == 0 && price != '') console.log(price.substr(1));
					if (first == 0 && price != '') $('#pricetag').html(price.substr(1));
					first++;
				}
			}
		}

		//onResize
		$(window).on('resize', function(){ flow($('#sold').val()); });

	}

	
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

	//regex email validation
	function isValidEmail(email) {
		var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
		return pattern.test(email);
	}

	//inquiry form
	var wpcf7 = $('form.wpcf7-form'),
			fields = $('#msg,#name,#uemail');

	if (wpcf7.length) {

		//toggle form
		wpcf7.find('h2').click(function(){
			var t = $(this);
			t.toggleClass('opened');
			t.next('div.adapt').slideToggle();
		});
		
		wpcf7.find('.cancel a').click(function() {
			var t = wpcf7.find('h2');
			t.toggleClass('opened');
			t.next('div.adapt').slideToggle();
			return false;
		});
		
		// disable submit button by default
		wpcf7.find('input[type="submit"]').attr('disabled', 'disabled');

		//green button
		fields.on('keydown keyup change', function(){
			var wpcf7s = wpcf7.find('input.wpcf7-submit'),
					invalid = 0;

			fields.each(function(){
				var t = $(this);
				if (t.hasClass('watermark') || t.val() == '') invalid++;
			});

			wpcf7s.removeClass('go-green');
			if (invalid == 0 && isValidEmail($('#uemail').val())) {
				wpcf7s.addClass('go-green');
				wpcf7.find('input[type="submit"]').attr('disabled', false);
			}

		});
	}

})();

