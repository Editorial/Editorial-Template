/*
  _  _  ___ ___ _   _  ___
 |_ | \  |   | / \ |_)  |   /\  |
 |_ |_/ _|_  | \_/ | \ _|_ /~~\ |_

 File: script.js
 Version: 1.1 (02/2012)
 Authors: Miha Hribar (twitter.com/mihahribar),
          Matjaz Korosec (twitter.com/matjazkorosec)

 */


//LOCAL DEV
//document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>');

var changeViewport = function () {
	if (window.orientation == 90 || window.orientation == -90) {
		$('meta[name="viewport"]').attr('content', 'height=device-width,width=device-height,initial-scale=1.0,maximum-scale=1.0');
		$('#media-elements figcaption').css('bottom', '50px');
	}else{
		$('meta[name="viewport"]').attr('content', 'height=device-height,width=device-width,initial-scale=1.0,maximum-scale=1.0');
		$('#media-elements figcaption').css('bottom', '60px');
	}
	scrollTo(0,0,1);
};

var iDevice = (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/iPad/i)) ? true : false;
if (iDevice) {
	var viewportmeta = document.querySelectorAll('meta[name="viewport"]')[0];
	if (viewportmeta) {
		viewportmeta.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0';
		document.body.addEventListener('gesturestart',function() {
			viewportmeta.content = 'width=device-width,minimum-scale=0.25,maximum-scale=1.6';
		},false);
	}
}

$(function(){

	if (iDevice) {

		window.addEventListener('orientationchange', changeViewport, true);
		try { changeViewport(); } catch (err) { }
		//remove address bar
		//@see http://davidwalsh.name/hide-address-bar
		//!!! na iPadu naredi belo crto
		if (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i)) {
			window.addEventListener('load', function() {
			  setTimeout(scrollTo, 0, 0, 1);}, false);
			}

		//iOS label fix
		$('label[for]').click(function(){
			var el = $(this).attr('for');
			if ($('#' + el + '[type=radio], #' + el + '[type=checkbox]').attr('selected', !$('#' + el).attr('selected'))) return;
			else $('#' + el)[0].focus();
		});
	}

	//embed-code select
	$('#embed-code').click(function(){$(this).select();});

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
		});
	}

	// ajax comment post
	$('#comments-form').submit(function() {
		// use ajax to submit form
		dataString = 'name='+$('#name').val()+'&email='+$('#email').val()+'&url='+$('#url').val()+'&comment='+$('#comment').val()+'&riddle='+$('#riddle').val()+'&comment_post_ID='+$('#comment_post_ID').val();
		$.ajax({
			type: 'POST',
			url: $('#comments-form').attr('action'),
			data: dataString,
			complete: function(msg) {
				var response = $.parseJSON(msg.responseText);

				// remove errors notice, if present
				$('#errors').remove();
				
				// remove error fields
				$('#comment').parent().removeClass('error');
				$('#name').parent().removeClass('error');
				$('#email').parent().removeClass('error');
				$('#url').parent().removeClass('error');
				$('#riddle').parent().removeClass('error');

				if (response.errors) {
					// show errors
					$(response.html).insertBefore('#comments-form');
					$('html,body').animate({scrollTop: $("#errors").offset().top},'slow');
					// add error fields
					for (error in response.error_fields) {
						var id = response.error_fields[error];
						$('#'+id).parent().addClass('error');
					}
				}
				else {
					// remove old success if already there
					$('#success').remove();
					// add success notice
					$(response.success).insertBefore('#comments-form');
					// add new comment to html
					if ($('#comments').length > 0) {
						// add to list
						$(response.html).insertBefore('#comments article:first-child');
					}
					else {
						// replace no comments notice & add comment
						$('#single .notice').html(response.notice)
						.after('<section id="comments">'+response.html+'</section>');
					}

					// scroll to success notice
					$('html,body').animate({scrollTop: $("#success").offset().top},'slow');

					// make success dissapear in 5 seconds
					setTimeout(function() {
						$('#success').fadeOut(500);
					}, 5000);

					// reset form
					$('#comment').val('');
					$('#name').val('');
					$('#email').val('');
					$('#url').val('');
					$('#riddle').val('');
				}

				// set new riddle
				$('#comments-form .captcha label[for="riddle"]').html(response.riddle.notice);
				$('#comments-form .qa span').html(response.riddle.riddle);
				// reset riddle
				$('#riddle').val('');
			}
		});
		return false;
	});

	//media gallery
	if ($('#media-gallery').length > 0) {

		//quick hide menus
		/*
		var m = $('#media-gallery');
		var s = '#media-gallery>header,#media-elements figcaption';
		$(s).delay(300).fadeOut(function(){m.addClass('fullscreen');});
		$('#media-elements').find('.active').find('img').click(function(){
			if(m.hasClass('fullscreen')) $(s).fadeIn(function(){m.removeClass('fullscreen');});
			else $(s).fadeOut(function(){m.addClass('fullscreen');});
		});*/


		//vertical center element
		function centerMedia(w) {
			//console.log( $(window).height());
			//console.log( $('#media-gallery header').height());
			if (w) var active = $('#media-elements').find('figure');
						else var active = $('#media-elements').find('.active');
			
						a = active.find('img');
						// if(a.length == 0) {
						// 			vid = active.find('.mejs-container');
						// 			vid.css('position', 'inherit');
						// 		}
						a.height('auto');
			
						var maxH = $(window).height()/2;
						var imgH = a.height()/2;
						var margin = maxH - imgH;
			
						if (margin > 0) {
							active.css('marginTop',margin);
						}
						else {
							active.css('marginTop',0);
							a.height(maxH*2);
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
			hideEmbed();
			slideShow(-1);
		});
		$('#m-slide').click(function(e) {
			e.preventDefault();
			slideShow(0);
		});
		$('#m-next').click(function(e) {
			e.preventDefault();
			hideEmbed();
			slideShow(1);
		});

		//embed button
		$('a.m-embed').click(function(e) {
			e.preventDefault();
			var butt = $(this).toggleClass('pressed'),
			curr_el = butt.prev('div.mobile-embed');
			curr_el.slideToggle(100);
			$('input',curr_el).css('width', '100%');
			
			$('input', curr_el).blur(
				function(e){
					if(butt.hasClass('pressed')){
						scrollTo(0,0,1);
						butt.toggleClass('pressed');
						curr_el.slideToggle(100);
					}
				}
			);
		});

		//hide embed button
		function hideEmbed() {
			$('a.m-embed').removeClass('pressed');
			$('div.mobile-embed').hide();
		}

		//slideshow
		function slideShow(go) {
			var el = $('#media-elements').find('.active');
			switch (go) {
				case (0): looping(el);
			break;
				case (1): goNext(el);
			break;
				case (-1): goPrev(el);
			break;
			}
		}

		//enable & disable state
		function enable(el) {
			if (el.next().length) $('#m-prev').removeClass('disabled');
			if (el.prev().length) $('#m-next').removeClass('disabled');
		}
		function disable(el) {
			centerMedia();
			if (!el.next().length) $('#m-next').addClass('disabled');
			if (!el.prev().length) $('#m-prev').addClass('disabled');
		}

		//looping elements
		var loop = setTimeout('',1);
		function looping(el) {
			$('#m-slide').addClass('running');
			loop = setTimeout(function(){goNext(el)},100);
			//goNext(el);
			//loop = setTimeout($(this),3000);
			//clearTimeout(loop);
		}

		//next element
		function goNext(el) {
			if (el.next().length) {
				enable(el);
				el.removeClass('active').css('display','');
				el.next().fadeIn(function() {
					var t = $(this);
					t.addClass('active');
					disable(t);
				});
			}
		}

		//previous element
		function goPrev(el) {
			if (el.prev().length) {
				enable(el);
				el.removeClass('active').css('display','');
				el.prev().fadeIn(function() {
					var t = $(this);
					t.addClass('active');
					disable(t);
				});
			}
		}

		// window
		$(window).resize(function(){centerMedia();});
		$(window).bind("orientationchange",function(){
			centerMedia(); 
		});
		$("body").on('touchmove', function (event) {
		    event.preventDefault();
		});
	}

	// comment karma
	$('form.favorize input:radio').each(function() {
		// on change submit form
		$(this).change(function() {
			$(this).parent().submit();
		});
	});

	// capture form submits
	$('form.favorize').each(function() {
		// catch submits and do ajax instead
		//create coins clones
		var score = $('strong.score', $(this));
		score.after(score.clone().removeClass('score').addClass('coin'));
		var coin = score.next();
		$(this).submit(function(e) {
			// prevent form from posting
			e.preventDefault();
			var selectedInput = $('input:radio:checked', $(this));
			var value = selectedInput.val();
			var key   = selectedInput.attr('name');
			var vote = {}; vote[key] = value;
			// post with ajax instead
			$.post($(this).attr('action'), vote, function(msg) {
				var response = $.parseJSON(msg);
				if (response.ok)
				{
					// set count
					$('#score-'+response.id).html(response.votes);
					//score animation
					var scorePlus = (selectedInput.attr('id').lastIndexOf('vote-for') == 0) ? true : false;
					var bgr = scorePlus ? '#79a500' : '#d00';
					if (scorePlus) coin.removeClass('negative').text('+1').show().animate({top:'-20px',opacity:0},300);
					else coin.addClass('negative').text('-1').show().animate({top:'-20px',opacity:0},300);
					//satus after vote
					var posScoreNum = (score.text() >= 0) ? true : false;
					if (posScoreNum) score.removeClass('negative');
					else score.addClass('negative');
				}
				else
				{
					// show error
				}
			});
			// disable form (visually and formally)
			$('input', this).attr('disabled', true);
			$('fieldset:first-child', this).addClass('disabled');
		});
	});

	// more to load?
	function paging() {
		$('#paging a').click(function() {
			// fetch more from same url
			$.ajax({
				url: $(this).attr('href'),
				success: function(data) {
					// remove paging
					$('#paging').remove();
					// add to content
					$('div.content').append(data);
					// bind paging again
					paging();
				}
			});
			return false;
		});
	}

	paging();

	// keyboard navigation
	// if ($('#gallery').length) {
	// 	$(document).keydown(function(e){
	// 		var key = e.keyCode || e.which;
	// 		var el = $('#media-elements>.active');
	// 		// left
	// 		if (key === 37) {
	// 			alert('left');
	// 			e.stopImmediatePropagation();
	// 			return false;
	// 		}
	// 		// right
	// 		if (key === 39) {
	// 			alert('right');
	// 			e.stopImmediatePropagation();
	// 			return false;
	// 		}
	// 	});
	// }
	
	
});