
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

	//embed-code select
	$('#embed-code').click(function(){$(this).select();});

	//iOS label click


	//demonav
	demoNav();

	function demoNav() {

		//open tabs
		$('.demonav a').click(function(e) {
			e.preventDefault();
			e.stopPropagation();
			var t = $(this);
			var tp = t.parent('li');
			var close = tp.hasClass('selected');
			var ah = t.attr('href');
			var goID = ah.substr(ah.lastIndexOf('#'));

			if (ah.lastIndexOf('#') == -1) location.href = t.attr('href');

			if (close == false) {
				//$('.demonav').addClass('opened');
				var first = t.parents('ul').find('li').hasClass('selected');
				t.parents('ul').find('li').removeClass('selected');
				tp.addClass('selected');
				$('#dashboard>article').hide();
				if (first == false) $(goID).stop().removeAttr('style').addClass('active').fadeIn(300);
				else $(goID).stop().removeAttr('style').addClass('active').show();
			}

			else {
				tp.removeClass('selected');
				$(goID).fadeOut(300,function() {
					$(this).removeClass('active');
				});
			}

		});

		$('#dashboard').click(function(e) {
			e.stopPropagation();
		});

		//close demonav / click outer
		$('html').click(function() {
			$('.demonav li').removeClass('selected');
			$('#dashboard>article').fadeOut(300,function(){
				$(this).removeClass('active');
			});
		});

		/* open tabs via param index.html?open=1
		function getUrlVars() {
			var vars = [],hash;
			var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
			for(var i = 0; i < hashes.length; i++) {
				hash = hashes[i].split('=');
				vars.push(hash[0]);
				vars[hash[0]] = hash[1];
			}
			return vars;
		}
		if (getUrlVars() == 'open') {
			$('#d-orientation').addClass('selected');
			$('#demo-orientation').addClass('active');
		}
		*/

		//toggle buttons

		//#demo-orientation

		$('#demo-orientation .buttons h4').click(function(){
			var notsel = $(this).next('ul').find('li:not(.selected)>a').attr('href');
			console.log($(this).next('ul').find('li:not(.selected)>a'));
			location.href = notsel;
		});

		//#demo-reading toggles
		function buttonSwitch(t){
			t.parent('li').parent('ul').find('li').removeClass('selected');
			t.parent('li').addClass('selected');
		}


		//line width count off
		$('#measure-off').click(function()
		{
			buttonSwitch($(this));
			measureOff();
			return false;
		});

		//line width count on
		$('#measure-on').click(function()
		{
			buttonSwitch($(this));
			measureOn();
			return false;
		});


		//font size optimal
		$('#font-optimal').click(function(e)
		{
			buttonSwitch($(this));
			//measureOff();
			e.preventDefault();
		});

		//font size average
		$('#font-average').click(function(e)
		{
			buttonSwitch($(this));
			//measureOn();
			e.preventDefault();
		});


		//Leading & vertical rythm hidden
		$('#rythm-hidden').click(function(e)
		{
			buttonSwitch($(this));
			//measureOn();
			e.preventDefault();
		});

		//Leading & vertical rythm visible
		$('#rythm-visible').click(function(e)
		{
			buttonSwitch($(this));
			//measureOn();
			e.preventDefault();
		});


		//optimal contrast
		$('#contrast-optimal').click(function(e)
		{
			buttonSwitch($(this));
			//measureOn();
			e.preventDefault();
		});


		//hyper contrast
		$('#contrast-hyper').click(function(e)
		{
			buttonSwitch($(this));
			//measureOn();
			e.preventDefault();
		});

	}



	function measureOn()
	{
		// text container's width
		var containerWidth = $('.entry-content').width(),
				stat           = new Array(),
				j              = 0; // array iterator

		// handle each paragraph
			$('.entry-content p').each(function()
			{
				// just in case she is trigger happy
				if ( $(this).hasClass('measure') )
				{
					return;
				}
				// replace all double spaces and new lines with a single space
				var words    = String($(this).text().replace(/\s+/gi, ' ')).split(" "),
				    i        = 0,
				    span     = "",
				    p        = $('<p class="measure">');

				// hide current one
                                $(this).replaceWith(p);

				// one word at a time
				while ( i < words.length )
				{
					// new span instance, hide until fully ready
					span = $('<span>').hide();
					// append it to DOM
					p.append(span);
					// add one word at a time until span's width exceeds container's width
					while ( span.width() <= containerWidth )
					{
						// append word and a space
						span.append(words[i] + " ");
						// lastly added word renders span wider than container
						if ( span.width() > containerWidth)
						{
							// remove lastly added word, -2 removes last space as well
							span.text(span.text().substr(0, span.text().length - words[i].length - 2));
							// decrease iterator, last word will be added to the next span
							--i;
							// and break
							break;
						}
						// we ran out of words!
						if ( ++i >= words.length )
						{
							break;
						}
					}
					// add class "line" to span (do not do it earlier since it contains display: block)
					// add span with number of characters
					// and finally, show it
					span.addClass('line')
							.append('<span class="num">' + $.trim(span.text()).length + '</span>')
							.show();
					// add current value to statistics
					stat[j] = $.trim(span.text()).length;
					// increase iterator
					++i;
					++j;
				}
			});
			// handle statistics
			var total = 0;
			$.each(stat, function()
			{
				 total += this;
			});

			$('.entry-content')
			.append(
					$('<p class="measure">')
					.append(
							$('<span class="line">')
							.append('Average characters per line')
							.append(
									$('<span class="num">')
									.append(Math.round(total/j))
							)
					)
			);
	}

	function measureOff()
	{
		// remove all measure ones
		$('.entry-content p.measure:last-child').remove();
		$('.entry-content p.measure').each(function()
                {
                	// remove numbers
			$(this).find('span.num').remove();
			// remove
			$(this).removeClass('measure')
			       .html($(this).html().replace(/(<span([^>]+)>)/ig, '')
			       .replace(/(<\/span>)/ig, ' '));
		});
	}






	//bad-comment
	if($('blockquote.bad-comment').length > 0) {
		var b = 'blockquote.bad-comment>p';
		var s = 'p.show>a';
		$(b).hide();
		$(s + '>span').text('Show hidden');
		$(s).click(function(e){
			e.preventDefault();
			if($(b).css('display') == 'block') {
				$(b).fadeOut('fast');
				$(s + '>span').text('Show hidden');
			}
			else {
				$(b).fadeIn('fast');
				$(s + '>span').text('Hide shown');
			}
		})
	}

	//media gallery
	if ($('#media-gallery').length > 0) {

		//init slideshow (serverside = no blink)
		//$('html').addClass('slideshow');

		//vertical center element
		function centerMedia(which) {

			var w = (which) ? 1 : 0;
			if (w == 1) var active = $('#media-elements>figure');
			else var active = $('#media-elements>.active');

			active.find('img').height('auto');

			var maxH = $(window).height()/2;
			var imgH = active.find('img').height()/2;
			var margin = maxH - imgH;

			if (margin > 0) {
				active.css('marginTop',margin);
			}
			else {
				active.css('marginTop',0);
				active.find('img').height(maxH*2);
			}
		}
		centerMedia(1);

		//toggle image description
		$('a.m-toggle').click(function(e) {
			e.preventDefault();
			$(this).toggleClass('pressed').prev('p').slideToggle(100);
		});

		//remote control
		$('#m-prev').click(function(e) {
			e.preventDefault();
			slideShow(-1);
		});
		$('#m-slide').click(function(e) {
			e.preventDefault();
			$(this).toggleClass('running');
			slideShow(0);
		});
		$('#m-next').click(function(e) {
			e.preventDefault();
			slideShow(1);
		});

		//embed button
		$('a.m-embed').click(function(e) {
			e.preventDefault();
			$(this).toggleClass('pressed');
		});

		//sldieshow
		function slideShow(go) {
			var el = $('#media-elements>.active');
			switch (go) {
				case (0):looping(el);
				break;
				case (1):goNext(el);
				break;
				case (-1):goPrev(el);
				break;
			}
		}

		//looping elements
		var loop = setTimeout('',1);
		function looping(el) {
			//goNext(el);
			//loop = setTimeout($(this),3000);
			//clearTimeout(loop);
		}

		//next element
		function goNext(el) {
			if (el.next().length > 0) {
				el.removeClass('active').css('display','');
				el.next().fadeIn(function() {
					$(this).addClass('active');
				});
			}
		}

		//previous element
		function goPrev(el) {
			if (el.prev().length > 0) {
				el.removeClass('active').css('display','');
				el.prev().fadeIn(function() {
					$(this).addClass('active');
				});
			}
		}

		$(window).resize(function(){centerMedia();});
	}




	//devSizer
	devSizer();
	function devSizer(){

		//settings

		//Devices
		var devSizerDevices = [];
//		devSizerDevices[0] = ['0','Tamagotchi, not! {CSS all devices}'];
		devSizerDevices[0] = ['0','CSS for all devices'];
		devSizerDevices[1] = ['320x480','iPhone 3G/3GS [P] (320 x 480)'];
		devSizerDevices[2] = ['480x320','iPhone 3G/3GS [L] (480 x 320)'];
		devSizerDevices[3] = ['480x720','Meizu M8 [P] (480 x 720)'];
		devSizerDevices[4] = ['480x800','Google Nexus one [P] (480 x 800)'];
		devSizerDevices[5] = ['640x960','iPhone 4G [P] (640 x 960)'];
		devSizerDevices[6] = ['720x480','Meizu M8 [L] (720 x 480)'];
		devSizerDevices[7] = ['768x1024','iPad [P] (768 x 1024)'];
		devSizerDevices[8] = ['800x480','Google Nexus one [L] (800 x 480)'];
		devSizerDevices[9] = ['960x640','iPhone 4G [L] (960 x 640)'];
		devSizerDevices[10] = ['1024x768','iPad [L] (1024 x 768)'];
		devSizerDevices[11] = ['1024x600','Netbooks (1024 x 600'];
		devSizerDevices[12] = ['1280x800','MacBook Air (1280 x 800)'];
		devSizerDevices[13] = ['1440x900','MacBook Pro 15\'\' (1440 x 900)'];
		devSizerDevices[14] = ['1600x900','Desktop - various (1600 x 900 +)'];

		//generated options
		var options = '';
		for(var i=0; i<devSizerDevices.length; i++) {
			options += '<option value="' + devSizerDevices[i][0] + '">' + devSizerDevices[i][1] + '</option>';
		}

		//var currentDevW = devSizerDevices[0];

		//calculate With and height
		function w(){return $(window).width();}
		function h(){return $(window).height();}

		//create control panel
		/*
		$('body').append(
			'<div id="devSizer" style="padding:5px;text-align:center;background:black;position:fixed;top:0;right:0;z-index:9001;">' +
			'	<a href="#" id="devSizerClose" title="Hide devSizer" style="padding:5px;background:black;color:#fff;position:absolute;top:0;right:0;">O</a>' +
			'	<div id="devSizerWrap" style="display:none;">' +
			'		<div style="font:20px/25px sans-serif;color:rgba(255,15,0,.6);">w: <span id="devSizerW" style="color:red;">' + w() + '</span> px</div>' +
			'		<div style="font:20px/25px sans-serif;color:rgba(255,15,0,.6);">h: <span id="devSizerH" style="color:red;">' + h() + '</span> px</div>' +
			'		<select id="devSizerDevices">' + options + '</select>' +
			'		<a href="#" id="devSizerScrolls" style="padding:6px 0;font:12px sans-serif;color:#fff;display:block;">Hide scrollers</a> ' +
			'	</div>' +
			'</div>'
		);
		*/
		$('#manualy').after(
			'<label for="devSizerDevices">Select a device (<span id="devSizerW">' + w() + '</span>px x <span id="devSizerH">' + h() + '</span>px)</label>' +
			'<select id="devSizerDevices">' + options + '</select>'
		);

		//show / hide devSizer
		/*
		$('#devSizerClose').click(function(e){
			e.preventDefault();
			var t = $(this);
			var c = $('#devSizerWrap');
			var s = (c.css('display') == 'none') ? 'show' : 'hide';
			if (s == 'hide') {t.html('O').attr('title','Show devSizer');c.fadeOut('fast');}
			else {t.html('X').attr('title','Hide devSizer');c.fadeIn('fast');}
		});
		*/

		//get value from devSizerDevices
		function resizeW(val){return parseInt(val.substr(0,val.lastIndexOf('x')));}
		function resizeH(val){return parseInt(val.substr(val.lastIndexOf('x')+1));}

		//update devSizer changes
		function updatedevSizer(){
			//update page width & height
			$('#devSizerW').html(w());
			$('#devSizerH').html(h());
			//select device
			var first = 0;
			//var currentD = 'T';
			$('#devSizerDevices>option').each(function(){
				if (first == 0) {
					if (w() < resizeW($(this).attr('value'))){
						//currentD = $(this).prev().text();
						//currentDevW = resizeW($(this).prev().val());
						$(this).prev().attr('selected','selected');
						first = 1;
					}
				}
				//console.log('smo tu:' + w() + ' kar pomeni da smo na devajsu: ' + currentD);
			});
		}
		//onload
		updatedevSizer();

		//on window resize
		$(window).resize(function(){updatedevSizer();});


		//change Device
		$('#devSizerDevices').change(function(){
			var val = $(this).val();
			if (val != 0) {
				var toolbars = 0;
				var sh = screen.availHeight;
				if (sh <= h()) toolbars = parseInt(sh - h());

				alert(sh + ' - ' + toolbars);


				/*
				if (resizeW(val) < 960){
					devSizerScrolls(0);
				}
				else devSizerScrolls(1);
				*/


				//alert('resizeW: ' + resizeW(val) + ' resizeH: ' + resizeH(val) + '\nW-razlika: ' + parseInt(resizeW(val) - w()) + ' <> H-razlika: ' + parseInt(resizeH(val) - h()));
				//pri meni vedno 15px (scroll) in 128px (visina orodij na sranjo)

				//z toolbarji
				//alert(screen.availHeight);

				var rw = parseInt(resizeW(val) + 15);
				//var rh = parseInt(resizeH(val) + parseInt(screen.availHeight - resizeH(val)));
				var rh = parseInt(resizeH(val) + toolbars);
				//var rh = resizeH(val);

				window.resizeTo(rw,rh);


				//console.log('currentW: ' + currentW + ' resizeW: ' + resizeW(val));
				//nothing resizes, open popup
				/*
				if (currentW != resizeW(val)) {
					var newW = window.open(window.location.href, 'newSizer', "width=350,height=350");
					newW.moveTo(0,0);
					newW.resizeTo(resizeW(val),resizeH(val));
					newW.focus();
				}*/

				//nW.resizeTo(rW,parseInt(rH + 128));

					//alert(w());

				//$('#devSizerScrolls').html('Show scrollers');

				//$('html').css('overflow','hidden');
			}
		});

	}





	//max-width IE6
	/*if ($.browser.msie && $.browser.version < 7) {
		function maxWidth() {
			if ($('body').width() > 960) {$('body').css('width','960px');}
		}
		$(window).resize(function(){maxWidth();});
	}*/

});

