// Plugins

// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
log.history = log.history || [];   // store logs to an array for reference
log.history.push(arguments);
arguments.callee = arguments.callee.caller; 
if(this.console) console.log( Array.prototype.slice.call(arguments) );
};

// make it safe to use console.log always
(function(b){function c(){}for(var d="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info, log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),a;a=d.pop();)b[a]=b[a]||c})(window.console=window.console||{});

// jQuery/helper plugins

/*! http://mths.be/placeholder v1.8.5 by @mathias */
(function(g,a,$){var f='placeholder' in a.createElement('input'),b='placeholder' in a.createElement('textarea');if(f&&b){$.fn.placeholder=function(){return this};$.fn.placeholder.input=$.fn.placeholder.textarea=true}else{$.fn.placeholder=function(){return this.filter((f?'textarea':':input')+'[placeholder]').bind('focus.placeholder',c).bind('blur.placeholder',e).trigger('blur.placeholder').end()};$.fn.placeholder.input=f;$.fn.placeholder.textarea=b;$(function(){$('form').bind('submit.placeholder',function(){var h=$('.placeholder',this).each(c);setTimeout(function(){h.each(e)},10)})});$(g).bind('unload.placeholder',function(){$('.placeholder').val('')})}function d(i){var h={},j=/^jQuery\d+$/;$.each(i.attributes,function(l,k){if(k.specified&&!j.test(k.name)){h[k.name]=k.value}});return h}function c(){var h=$(this);if(h.val()===h.attr('placeholder')&&h.hasClass('placeholder')){if(h.data('placeholder-password')){h.hide().next().show().focus().attr('id',h.removeAttr('id').data('placeholder-id'))}else{h.val('').removeClass('placeholder')}}}function e(){var l,k=$(this),h=k,j=this.id;if(k.val()===''){if(k.is(':password')){if(!k.data('placeholder-textinput')){try{l=k.clone().attr({type:'text'})}catch(i){l=$('<input>').attr($.extend(d(this),{type:'text'}))}l.removeAttr('name').data('placeholder-password',true).data('placeholder-id',j).bind('focus.placeholder',c);k.data('placeholder-textinput',l).data('placeholder-id',j).before(l)}k=k.removeAttr('id').hide().prev().attr('id',j).show()}k.addClass('placeholder').val(k.attr('placeholder'))}else{k.removeClass('placeholder')}}}(this,document,jQuery));
// Invoke the plugin
$('input,textarea').placeholder();

// social media icons / original: https://gist.github.com/1025811
if ($('ul.social').length) {
	// Modernizr loads only if it's not mobile
	if (matchMedia('screen and (min-width:640px)').matches) {
		(function(doc, script) {
			var js,
			fjs = doc.getElementsByTagName(script)[0],
			frag = doc.createDocumentFragment(),
			add = function(url, id) {
				if (doc.getElementById(id)) {return;}
				js = doc.createElement(script);
				js.src = url;
				id && (js.id = id);
				frag.appendChild( js );
			};

			// Twitter
			if($('li.twitter').length) {
				$('li.twitter').html('<a class="twitter-share-button" data-count="horizontal" data-via="editorialtheme"></a>');
				add('//platform.twitter.com/widgets.js');
			}

			// Google+
			if($('li.gplus').length) {
				$('li.gplus').html('<g:plusone size="medium" width="65"></g:plusone>');
				add('https://apis.google.com/js/plusone.js');
			}

			// Facebook
			if($('li.facebook').length) {
				//$('li.facebook').html('<div id="fb-root"></div><div class="fb-like" data-send="false" data-layout="button_count" data-width="74" data-height="20" data-show-faces="false"></div>');
				//add('//connect.facebook.net/en_US/all.js#xfbml=1&appId=1234567890', 'facebook-jssdk');
				var url = this.location.href;
				var params = 'send=false&amp;layout=button_count&amp;width=80&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;width=74&amp;height=20'
				$('li.facebook').html('<iframe src="http://www.facebook.com/plugins/like.php?href=' + url + '&amp;' + params +'" scrolling="no" frameborder="0" style="border:none;overflow:hidden;width:74px;height:20px;" allowTransparency="true"></iframe>');

			}

			fjs.parentNode.insertBefore(frag, fjs);
		}(document, 'script'));

	} /* end Modernizr polyfill */
}