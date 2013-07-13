/*
  _  _  ___ ___ _   _  ___
 |_ | \  |   | / \ |_)  |   /\  |
 |_ |_/ _|_  | \_/ | \ _|_ /~~\ |_

 File: script.js
 Version: 1.1 (02/2012)
 Authors: Miha Hribar (twitter.com/mihahribar),
          Matjaz Korosec (twitter.com/matjazkorosec)

 */

var changeViewport = function () {
	if (window.orientation == 90 || window.orientation == -90) {
		$('meta[name="viewport"]').attr('content', 'height=device-width,width=device-height,initial-scale=1.0,maximum-scale=1.0');
		//$('#media-gallery').css('bottom', '50px');
		$('body').css('height', (window.outerHeight + 60) + 'px' );
	}else{
		$('meta[name="viewport"]').attr('content', 'height=device-height,width=device-width,initial-scale=1.0,maximum-scale=1.0');
		//$('#media-gallery').css('bottom', '60px');
		$('body').css('height', (window.outerHeight + 70) + 'px' );
	}
	hideAddressBar();
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
		if (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i)) {

			//iOS label fix
			$('label[for]').click(function(){
				var el = $(this).attr('for');
				if ($('#' + el + '[type=radio], #' + el + '[type=checkbox]').attr('selected', !$('#' + el).attr('selected'))) return;
				else $('#' + el)[0].focus();
			});
		}
	}

	//embed-code select
	$('#embed-code').click(function(){$(this).select();});

	//bad-comment
	if($('blockquote.bad-comment').length) {
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
					if ($('#comments').length) {
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


	//DESKTOP gallery
	if ($('#media').length) {
		$(document).focus();
		$(document).keydown(function(e){
			var key = e.keyCode || e.which, new_loc;
			//left
			if(key === 37 ){
				new_loc = $('li.previous a').attr('href');
				if(new_loc) window.location.href = new_loc;
				e.stopImmediatePropagation();
				return false;
			}
			// right
			if (key === 39) {
				new_loc = $('li.next a').attr('href');
				if(new_loc) window.location.href = new_loc;
				e.stopImmediatePropagation();
				return false;
			}
			//esc
			if (key === 27) {
				new_loc = parentPageID;
				if(new_loc) window.location.href = new_loc;
				e.stopImmediatePropagation();
				return false;
			}
		});

	}

	//MOBILE & DESKTOP gallery video
	if ($('#player').length) {
		$('#player').mediaelementplayer({alwaysShowControls:true});
	}
	
});
