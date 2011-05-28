
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






	//devSizer
	devSizer();
	function devSizer(){

		//settings

		//Devices
		var devSizerDevices = [];
		devSizerDevices[0] = ['0','{CSS} Tamagotchi, not!'];
		devSizerDevices[1] = ['320x480','{MQ1} iPhone 3G/3GS [P] (320 x 480)'];
		devSizerDevices[2] = ['480x320','{MQ2} iPhone 3G/3GS [L] (480 x 320)'];
		devSizerDevices[3] = ['480x720','Meizu M8 [P] (480 x 720)'];
		devSizerDevices[4] = ['480x800','Google Nexus one [P] (480 x 800)'];
		devSizerDevices[5] = ['720x480','Meizu M8 [L] (720 x 480)'];
		devSizerDevices[6] = ['768x1024','{MQ3} iPad [P] (768 x 1024)'];
		devSizerDevices[7] = ['800x480','Google Nexus one [L] (800 x 480)'];
		devSizerDevices[8] = ['960x640','iPhone 4G [L] (960 x 640)'];
		devSizerDevices[9] = ['1024x768','{MQ4} iPad [L] (1024 x 768)'];
		devSizerDevices[10] = ['1024x600','Netbooks (1024 x 600'];
		devSizerDevices[11] = ['1280x800','MacBook Air (1280 x 800)'];
		devSizerDevices[12] = ['1440x900','MacBook Pro 15\'\' (1440 x 900)'];

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

