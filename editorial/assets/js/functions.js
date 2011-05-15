/**
 * Common functions
 *
 * @project L&F
 * @author matjazkorosec.com
 * @author miha hribar
 * @created Feb 2010
 * @version 0.3 / Jun 2010
 *
**/

$(function() {

	// external links open in new window
	$('a.external').click(function(e){e.preventDefault();window.open($(this).attr('href'));});

	// prefilled classes on input mean the content disapears when focused
	$('input.prefilled').focus(function() {
		var value = $(this).attr('value');
		$(this).attr('value', '');
		$(this).blur(function() {
			if ($(this).attr('value') == '') {
				// set back to original value
				$(this).attr('value', value);
			}
		});
	});

	// setup for "image border" hover
	$('li.exposed img').each(
		function()
		{
			var $t = $(this).parents('li');
			var img = $t.find('img');
			var bdr = 10;
			if($.browser.msie && $.browser.version == "6.0") bdr = 0;
			var w = img.width()- bdr;
			var h = img.height() - bdr;
			var style = 'width:'+ w + 'px;height:'+ h +'px;';
			if($.browser.msie) style += 'background:url(../images/bgr/ie-fix.gif)';
			var $a = img.parent();
			$a.after('<a href="'+ $a.attr('href') +'" class="frame" style="'+ style +'" />');
		}
	);
	
	// image & text hovers
	$('li.exposed h2>a,li.exposed a.frame').hover(
		function()
		{
			var $t = $(this).parents('li');
			$t.find('h2>a').addClass('hover').parent().addClass('hover');
			$t.find('a.frame').addClass('shown');
		},
		function()
		{
			var $t = $(this).parents('li');
			$t.find('h2>a').removeClass('hover').parent().removeClass('hover');
			$t.find('a.frame').removeClass('shown');
		}
	);

	// gallery
	if ($('div.photo').length >= 1)
	{
		$('div.photo>ul').ceebox();
		$('a.show-all').click(
			function(e)
			{
				e.preventDefault();
				$('div.photo>ul>li:first-child>a').trigger('click');
			}
		);
	}

});
