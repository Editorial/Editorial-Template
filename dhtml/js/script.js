
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
	function demoNav() {

		$('#dashboard').click(function(e) {
			e.stopPropagation();
		});

		$('.demonav a').click(function(e) {
			e.preventDefault();
			e.stopPropagation();
			var t = $(this);
			var tp = t.parent('li');
			var close = tp.hasClass('selected');
			var ah = t.attr('href');
			var goID = ah.substr(ah.lastIndexOf('#'));

			if (close == false) {
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

		//close demonav click outer
		$('html').click(function() {
			$('.demonav li').removeClass('selected');
			$('#dashboard>article').fadeOut(300,function(){
				$(this).removeClass('active');
			});
		});

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

	}

	demoNav();


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
				case (0): looping(el);
				break;
				case (1): goNext(el);
				break;
				case (-1): goPrev(el);
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
		$('#devSizerClose').click(function(e){
			e.preventDefault();
			var t = $(this);
			var c = $('#devSizerWrap');
			var s = (c.css('display') == 'none') ? 'show' : 'hide';
			if (s == 'hide') {t.html('O').attr('title','Show devSizer');c.fadeOut('fast');}
			else {t.html('X').attr('title','Hide devSizer');c.fadeIn('fast');}
		});

		//get value from devSizerDevices
		function resizeW(val){return val.substr(0,val.lastIndexOf('x'));}
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
		updatedevSizer();

		//enable / disable scrolls
		var scrollW = 15;
		function devSizerScrolls(force){
			var l = $('#devSizerScrolls');
			var o = $('html').css('overflow');
			if(force) {
				if (force == 0) o = 'visible';
				else o = 'hidden';
			}
			var s = (o == 'hidden') ? 'show' : 'hide';
			if (s == 'show'){$('html').css('overflow','visible');l.html('Hide scrollers');}
			else {$('html').css('overflow','hidden');l.html('Show scrollers');scrollW = scrollW * -1;}

			//console.log(w());
			//console.log(parseInt(w() + scrollW)+ ',' + h());
			//window.resizeTo(parseInt(w() + scrollW),h());
			updatedevSizer();
		}

		//cliks

		//scrolls
		$('#devSizerScrolls').click(function(e){
			e.preventDefault();
			devSizerScrolls();
		});


		//on window resize
		$(window).resize(function(){updatedevSizer();});


		//change Device
		$('#devSizerDevices').change(function(){
			var val = $(this).val();
			if (val != 0) {
				//$('html').css('overflow','hidden');
				//alert('na: ' + v.substr(0,v.lastIndexOf('x')) + ',' + parseInt(parseInt(v.substr(v.lastIndexOf('x')+1)) + 128));

				//console.log('inner height: ' + document.documentElement.clientHeight);
				//dobit dimenzijo z toolbari
				//console.log('resize: ' + resizeW(val) + ' x ' + resizeH(val));

				//alert(resizeW(val));
				if (resizeW(val) < 960){
					devSizerScrolls(0);
				}
				else devSizerScrolls(1);

				window.resizeTo(resizeW(val),resizeH(val));
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

