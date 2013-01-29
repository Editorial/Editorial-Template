var regImg = /<img|iframe|video|audio\b[^>]*>/i, regEmbed = /<img|iframe\b[^>]*>/i;
/*jQuery(document)
		.ajaxSend(
				function(e, x, a) {
					var dataObj, isGallery, content, isInline, regImg = /<img|iframe|video|audio\b[^>]*>/i, regEmbed = /<img|iframe\b[^>]*>/i;

					isGallery = jQuery('#editorial-post-gallery-mode-input')
							.is(':checked');
					if (isGallery)
						isGallery = '1';
					else
						isGallery = '0';
					isInline = null;

					if (a.data) {
						a.data += '&' + jQuery.param({
							'editorial-post-gallery-mode' : isGallery
						});
						// var str = jQuery.parseJSON(a.data);
						// console.log(a.data);
						dataObj = ptq(a.data);
						if (undefined != dataObj.content)
							var content = dataObj.content;
						if (undefined != content) {
							isInline = regImg.exec(content);
							if (isGallery == '1' && isInline) {
								if (!wpCookies
										.get('wordpress_editorial_gallery_mode')) {
									wpCookies.set(
											'wordpress_editorial_gallery_mode',
											1, 1000);
									alert("We are in gallery mode, pls make sure you are not using inline code for images");
								}
							}

						}
						// console.log(dataObj.content);
					}
				});

*/
function ptq(q) {
	/* parse the query */
	var x = q.replace(/;/g, '&').split('&'), i, name, t;
	/* q changes from string version of query to object */
	for (q = {}, i = 0; i < x.length; i++) {
		t = x[i].split('=', 2);
		name = unescape(t[0]);
		if (!q[name])
			q[name] = [];
		if (t.length > 1) {
			q[name][q[name].length] = unescape(t[1]);
		}
		/* next two lines are nonstandard */
		else
			q[name][q[name].length] = true;
	}
	return q;
}
function gallerySwitch(el) {
	var checkBox = jQuery(el).closest("label").find('input'), isChecked = checkBox.is(':checked');
	if (jQuery(el).hasClass('on')) {
		jQuery(checkBox).attr('checked', 'checked');
		jQuery(checkBox).val('1');
	} else {
		jQuery(checkBox).attr('checked', null);
		jQuery(checkBox).val('0');
	}
	jQuery(el).closest('form').submit();
	return false;
}
jQuery(document)
		.on(
				'ready',
				function() {
					if (!window.wp || !window.wp.media) {
						return;
					}
					var media = window.wp.media, Attachment = media.model.Attachment, Attachments = media.model.Attachments, Query = media.model.Query, l10n = media.view.l10n = typeof _wpMediaViewsL10n === 'undefined' ? {}
							: _wpMediaViewsL10n, NewMenuItem, selectorBtn, isGalleryMode, sh;
					l10n.editGalleryTitle = '';
					if (!sh || undefined == sh ) sh = '[gallery]';
					if (undefined == isGalleryMode)
						isGalleryMode = jQuery(
								'#editorial-post-gallery-mode-input').is(
								':checked');

					jQuery('#editorial-post-gallery-mode-input').bind(
							'change',
							function() {
								if (jQuery(this).is(':checked')) {
									jQuery(document).find('.insert-media')
											.removeClass('insert-media')
											.addClass('edit-editorial');
									selectorBtn = '.edit-editorial';
									jQuery('#qt_content_img').hide();
								} else {
									selectorBtn = '.insert-media';
									jQuery(document).find('.edit-editorial')
											.removeClass('edit-editorial')
											.addClass('insert-media');
									jQuery('#qt_content_img').show();

								}
							});
					media.view.Attachment.Details = media.view.Attachment.Details
					.extend({
						template : media
								.template('attachment-details-editorial')
					});
					if (isGalleryMode) {
						selectorBtn = '.edit-editorial';
						jQuery(document).find('.insert-media').removeClass(
								'insert-media').addClass('edit-editorial');
						jQuery('#qt_content_img').hide();
					} else {
						selectorBtn = '.insert-media';
						jQuery('#qt_content_img').show();
					}

					var origBrowser = wp.media.view.AttachmentsBrowser, origFilters = media.view.AttachmentFilters,
					origFrame = media.view.MediaFrame.Select,
					origEmbed = media.view.Embed,
					EmbedUrl = media.view.EmbedUrl;

					media.view.EmbedLink = media.view.EmbedLink.extend({
						template: (isGalleryMode)?media.template('embed-link-settings-editorial'):media.template('embed-smartlink-settings-editorial')
					});
					media.view.AttachmentsBrowser = media.view.AttachmentsBrowser.extend({
						createSingle: function() {
							origBrowser.prototype.createSingle.apply( this, arguments );
							var sidebar = this.sidebar;
							sidebar.unset('compat');
							sidebar.unset('display');
						}
					});

					media.controller.GalleryEmbed = media.controller.Library.extend({
						defaults: _.defaults({
							id:           'gallery-embed',
							filterable:   'uploaded',
							multiple:     'add',
							toolbar: 	  'main-embed',
							title:        'Insert from URL', //
							priority:     100,
							url:     '',
							content: 'embed',
							toolbar: 'main-embed',
							type:    'link',
							syncSelection: true
						}, media.controller.Library.prototype.defaults ),

						initialize: function() {
							// If we haven't been provided a `library`, create a `Selection`.
							if ( ! this.get('library') )
								this.set( 'library', media.query({ type: 'image' }) );
							this.debouncedScan = _.debounce( _.bind( this.scan, this ), 200 );
							this.props = new Backbone.Model({ url: '' });
							this.props.on( 'change:url', this.debouncedScan, this );
							this.props.on( 'change:url', this.refresh, this );
							this.on( 'scan', this.scanImage, this );
							media.controller.Library.prototype.initialize.apply( this, arguments );
						},

						activate: function() {
							var library = this.get('library'),
								edit    = this.frame.state('gallery-embed').get('library');

							if ( this.editLibrary && this.editLibrary !== edit )
								library.unobserve( this.editLibrary );

							library.observe( edit );
							this.editLibrary = edit;

							media.controller.Library.prototype.activate.apply( this, arguments );
						},
						scan: function() {
							var scanners,
								embed = this,
								attributes = {
									type: 'link',
									scanners: []
								};

							// Scan is triggered with the list of `attributes` to set on the
							// state, useful for the 'type' attribute and 'scanners' attribute,
							// an array of promise objects for asynchronous scan operations.
							if ( this.props.get('url') )
								this.trigger( 'scan', attributes );

							if ( attributes.scanners.length ) {
								scanners = attributes.scanners = jQuery.when.apply( $, attributes.scanners );
								scanners.always( function() {
									if ( embed.get('scanners') === scanners )
										embed.set( 'loading', false );
								});
							} else {
								attributes.scanners = null;
							}

							attributes.loading = !! attributes.scanners;
							this.set( attributes );
						},

						scanImage: function( attributes ) {
							var frame = this.frame,
								state = this,
								url = this.props.get('url'),
								image = new Image(),
								deferred = jQuery.Deferred();

							attributes.scanners.push( deferred.promise() );

							// Try to load the image and find its width/height.
							image.onload = function() {

								deferred.resolve();

								if (/* state !== frame.state() ||*/ url !== state.props.get('url') )
									return;
								state.set({
									type: 'image'
								});

								state.props.set({
									width:  image.width,
									height: image.height
								});
							};

							image.onerror = deferred.reject;
							image.src = url;
						},

						refresh: function() {
							this.frame.toolbar.get().refresh();
						},

						reset: function() {
							this.props.clear().set({ url: '' });

							if ( this.active )
								this.refresh();
						}
					});
					if (isGalleryMode) {
						media.view.MediaFrame.Select = media.view.MediaFrame.Select
						.extend({
							bindHandlers: function() {
								this.on( 'router:create:browse', this.createRouter, this );
								this.on( 'router:render:browse', function (view){
									view.set({
										upload: {
											text:     l10n.uploadFilesTitle,
											priority: 40
										},
										browse: {
											text:    (isGalleryMode)? 'Gallery items': l10n.mediaLibraryTitle,
											priority: 20
										},
										embedd: {
											text: 'Insert from URL',
											priority: 60
										}
									});
								});

								this.on( 'content:create:embedd',
										function() {
										this.states.add([
										            new media.controller.GalleryEmbed()
									 			]);
											var model =  this.state('gallery-embed');
											model.reset();
											var view = new media.view.Embedd({
												controller:  this.state('gallery-embed'),
												model:   model
											}).render();
									this.content.set( view );
									view.url.$input.val('http://');
									view.url.focus();
								}, this );
								this.on( 'content:create:browse', this.browseContent, this );
								this.on( 'content:render:upload', this.uploadContent, this );
								this.on( 'toolbar:create:select', this.createSelectToolbar, this );
							}
						});
					}
					media.view.Settings.AttachmentDisplay = media.view.Settings.AttachmentDisplay
					.extend({
						template : media
								.template('attachment-display-settings-editorial')
					});
					media.view.Settings.Gallery = media.view.Settings.Gallery
								.extend({
									template : media
											.template('gallery-settings-editorial')
					});


					media.view.Embedd = media.view.Embed
					.extend({
						initialize: function() {
							origEmbed.prototype.initialize.apply(
									this, arguments);
							this.model.props.url = '';

						},
						loading : function() {
							this.$el.toggleClass('embed-loading',
									this.model.get('loading'));
							if (this.model.get('loading'))
								this.refresh();
						},
						refresh : function() {
							var type = this.model.get('type'), constructor;
							if ('image' === type || 'link' === type) {
								var data = this.model.props.attributes;
								var ctrl = this.controller;
								var mdl = this.model.props;
								var thisObj = this;
								if (data.url != '') {
									mdl.set('error', "Loading...");
								jQuery.when(
										jQuery.ajax({
															url : ''+ wpDir+ '/wp-admin/admin-ajax.php',
															dataType : 'json',
															type : 'POST',
															data : {
																'url' : data.url,
																'type' : type,
																'action' : "parse_embed_editorial",
																'post_ID' : media.view.settings.post.id
															},
															success : function(data) {}
														}))
										.then(
												function(data) {
													if (!data.error && data.data.type == 'video') {
														mdl.set('error', "Loading ...");
														if(isGalleryMode){
															 type = 'video';
															 mdl.set('data', data.data);
															 constructor = media.view.EmbedVideo;
															 var gallery = wp.media.gallery, Uploader = wp.Uploader, complete;

															// gallery.attachments();
															 var controller = ctrl.frame,
															  state = controller.state();
															  selection = state.get('selection');

															  var attachment = wp.media.model.Attachment.create( data );
															//  wp.media.model.Attachment.get( data.id);
															 // attachment.fetch();

															  wp.Uploader.queue.add(attachment);
															  attachment.set( _.extend( data, { uploading: false }) );
															  wp.media.model.Attachment.get( data.id, attachment );
															  attachment.fetch();
															  complete = Uploader.queue.all( function( attachment ) {
																	return ! attachment.get('uploading');
																});
															  edit = controller.state('gallery-library');
															  var library = edit.get('library');
															  library.add(attachment);
															  Uploader.queue.length = 0;
															  selection.add( attachment ? [ attachment ] : [] );
															  controller.content.mode('browse');
														} else {
															type = 'video';
															console.log(data);
															mdl.set('data', data.data);
															mdl.set('thumbnail', data.data.url);
															mdl.set('title', data.data.title);
															 var controller = ctrl.frame,
															  state = controller.state();
															  selection = state.get('selection');

															  attachment = wp.media.model.Attachment.get( data.id);
															  attachment.fetch();

															 constructor = media.view.EmbedImage;
															 var controller = ctrl.frame;
															 var a = wp.media.model.Attachment.create( attachment );
															  selection.add( a ? [ a ] : [] );
															  state.trigger('reset');
															  edit = controller.state('insert');
															  edit.get('library').add(a);
															  controller.content.mode('browse');

															 thisObj.settings(new constructor({
																	controller : controller,
																	model : mdl,
																	priority : 10
																}));
															//  state = controller.state();
															//  selection = state.get('selection');
															//  selection.add(data.data.url);
														}
													} else {
														type = 'link';
														constructor = media.view.EmbedLink;
														if (data.success != undefined && !data.success) mdl.set('error', "Sorry we can not fetch this content at this time, pls check the url and try again!");
													}

													thisObj.settings(new constructor({
																		controller : ctrl,
																		model : mdl,
																		priority : 10
																	}));


												});
								if (type == 'link')
									constructor = media.view.EmbedLink;
								else if (type == 'video')
									constructor = media.view.EmbedVideo;
								else
									return;
							}

							else
								return;
							}
							this.settings(new constructor({
								controller : this.controller,
								model : this.model.props,
								priority : 40
							}));
						}
					});
					media.view.EmbedImage = media.view.EmbedImage.extend({
						template : media
								.template('embed-image-settings-editorial')

					});

					media.controller.GalleryAdd = media.controller.GalleryAdd.extend({
						activate: function() {
							var library = this.get('library'),
								edit    = this.frame.state('gallery-edit').get('library');

							if ( this.editLibrary && this.editLibrary !== edit )
								library.unobserve( this.editLibrary );
							library.observe( edit );
							this.editLibrary = edit;

							media.controller.Library.prototype.activate.apply( this, arguments );
						}
					});

					media.view.EmbedVideo = media.view.Settings.AttachmentDisplay
							.extend({
								className : 'embed-image-settings',
								template : media
										.template('embed-video-settings-editorial'),

								initialize : function() {
									media.view.Settings.AttachmentDisplay.prototype.initialize
											.apply(this, arguments);
									//this.model.on('change:url', this.updateImageEditorial, this);
								},
								uploading: function( attachment ) {
									var content = this.frame.content;
									// If the uploader was selected, navigate to the browser.
									if ( 'embedd' === content.mode() )
										this.frame.content.mode('browse');
									// If we're in a workflow that supports multiple attachments,
									// automatically select any uploading attachments.
									if ( this.get('multiple') )
										this.get('selection').add( attachment );
								}
				});

					media.view.AttachmentFilters.Uploaded = media.view.AttachmentFilters.Uploaded
							.extend({
								initialize : function() {
									origFilters.prototype.initialize.apply(
											this, arguments);
									this.$el.val("uploaded");
									this.model
											.set({
												uploadedTo : media.view.settings.post.id,
												orderby : 'menuOrder',
												order : 'ASC'
											});

								},
								createFilters : function() {
									var type = this.model.get('type'), types = media.view.settings.mimeTypes, text;

									if (types && type)
										text = types[type];

									this.filters = {

											/*all: {
												text:  text || l10n.allMediaItems,
												props: {
													uploadedTo: null,
													orderby: 'date',
													order:   'DESC'
												},
												priority: 10
											},*/
											uploaded : {
											text : l10n.uploadedToThisPost,
											props : {
												uploadedTo : media.view.settings.post.id,
												orderby : 'menuOrder',
												order : 'ASC'
											},
											priority : 10
										}
									};
								}
							});

					jQuery(document)
							.on(
									'click',
									selectorBtn,
									function(event) {
										// Check if the `wp.media.gallery` API
										// exists.
										if (typeof wp === 'undefined'
												|| !wp.media
												|| !wp.media.gallery)
											return;
										var el = this, gallery = wp.media.gallery, frame, sh, Uploader = wp.Uploader;
										if (selectorBtn == '.edit-editorial') {

											var ids = jQuery(el).attr('gallery');
											if (!ids || undefined == ids ) sh = '[gallery]';
											else sh = '['+ids+']';
											frame = gallery.edit(sh);
											var workflow = wp.media.gallery.frame,options = workflow.options;
											frame.setState('gallery-library');
											frame.content.mode('browse');
											wp.Uploader.queue.reset();
											jQuery(".media-frame select.attachment-filters").hide();
											jQuery(".attachment.selected .check").hide();
											jQuery(".attachments-browser").addClass("gallery-editor");

											frame.state('gallery-edit').on(
													'update',
													function(selection) {
														var shortcode = gallery.shortcode( selection ).string().slice( 1, -1 );
														jQuery(el).attr('gallery', shortcode);

													});

										} else {

											media.view.MediaFrame.Select = origFrame;
										}
										jQuery('.media-router').find(
												'.media-menu-item:first')
												.addClass("active").trigger(
														'click');


									});
					// for debug : trace every event

					/*var originalTrigger = wp.media.view.MediaFrame.Post.prototype.trigger;
						wp.media.view.MediaFrame.Post.prototype.trigger = function(){
						console.log('Event Triggered:', arguments);
						originalTrigger.apply(this, Array.prototype.slice.call(arguments));
					}*/





					// custom state : this controller contains your application logic

					wp.media.controller.Custom = wp.media.controller.State.extend({



					initialize: function(){

					// this model contains all the relevant data needed for the application

					this.props = new Backbone.Model({ custom_data: '' });

					this.props.on( 'change:custom_data', this.refresh, this );

					},

					// called each time the model changes

					refresh: function() {

					// update the toolbar

					this.frame.toolbar.get().refresh();

					},

					// called when the toolbar button is clicked

					customAction: function(){

					console.log(this.props.get('custom_data'));

					}

					});



					// custom toolbar : contains the buttons at the bottom

					wp.media.view.Toolbar.Custom = wp.media.view.Toolbar.extend({

					initialize: function() {

					_.defaults( this.options, {

					event: 'custom_event',

					close: false,

					items: {

					custom_event: {

					text: wp.media.view.l10n.customButton, // added via 'media_view_strings' filter,

					style: 'primary',

					priority: 80,

					requires: false,

					click: this.customAction

					}

					}

					});



					wp.media.view.Toolbar.prototype.initialize.apply( this, arguments );

					},



					// called each time the model changes

					refresh: function() {

					// you can modify the toolbar behaviour in response to user actions here

					// disable the button if there is no custom data

					var custom_data = this.controller.state().props.get('custom_data');

					this.get('custom_event').model.set( 'disabled', ! custom_data );

					// call the parent refresh

					wp.media.view.Toolbar.prototype.refresh.apply( this, arguments );

					},

					// triggered when the button is clicked

					customAction: function(){

					this.controller.state().customAction();

					}

					});



					// custom content : this view contains the main panel UI

					wp.media.view.Custom = wp.media.View.extend({

					className: 'media-custom',

					// bind view events

					events: {

					'input': 'custom_update',

					'keyup': 'custom_update',

					'change': 'custom_update'

					},



					initialize: function() {

					// create an input

					this.input = this.make( 'input', {

					type: 'text',

					value: this.model.get('custom_data')

					});

					// insert it in the view

					this.$el.append(this.input);

					// re-render the view when the model changes

					this.model.on( 'change:custom_data', this.render, this );

					},

					render: function(){

					this.input.value = this.model.get('custom_data');

					return this;

					},

					custom_update: function( event ) {

					this.model.set( 'custom_data', event.target.value );

					}

					});





					// supersede the default MediaFrame.Post view

					var oldMediaFrame = wp.media.view.MediaFrame.Post;

					wp.media.view.MediaFrame.Post = oldMediaFrame.extend({
						galleryAddToolbar: function() {
							this.toolbar.set( new media.view.Toolbar({
								controller: this,
								items: {
									insert: {
										style:    'primary',
										text:     l10n.addToGallery,
										priority: 80,
										requires: { library: true  },

										click: function() {
											var controller = this.controller,
												state = controller.state(),
												edit = controller.state('gallery-edit');

											edit.get('library').add( state.get('selection').models );
											state.trigger('reset');
											controller.close();
											state.trigger( 'update', state.get('library') );
											edit.collection.reset();
											controller.reset();
											// @todo: Make the state activated dynamic (instead of hardcoded).
											controller.setState('upload');
											//jQuery("#post").submit();
										}
									}
								}
							}) );
						},
						mainInsertToolbar: function( view ) {
							var controller = this;

							this.selectionStatusToolbar( view );

							view.set( 'insert', {
								style:    'primary',
								priority: 80,
								text:     l10n.insertIntoPost,
								requires: { selection: false },

								click: function() {
									var state = controller.state(),
										selection = state.get('selection');
										controller.close();
										console.log(selection);
										state.trigger( 'insert', selection ).reset();
								}
							});
						}
						/*,
					initialize: function() {
					oldMediaFrame.prototype.initialize.apply( this, arguments );
					this.states.add([

					new wp.media.controller.Custom({

					id: 'my-action',

					menu: 'default', // menu event = menu:render:default

					content: 'custom',

					title: wp.media.view.l10n.customMenuTitle, // added via 'media_view_strings' filter

					priority: 200,

					toolbar: 'main-my-action', // toolbar event = toolbar:create:main-my-action

					type: 'link'

					})

					]);



					this.on( 'content:render:custom', this.customContent, this );

					this.on( 'toolbar:create:main-my-action', this.createCustomToolbar, this );

					this.on( 'toolbar:render:main-my-action', this.renderCustomToolbar, this );

					},

					createCustomToolbar: function(toolbar){

					toolbar.view = new wp.media.view.Toolbar.Custom({

					controller: this

					});

					},



					customContent: function(){

					// this view has no router

					this.$el.addClass('hide-router');



					// custom content view

					var view = new wp.media.view.Custom({

					controller: this,

					model: this.state().props

					});



					this.content.set( view );

					}

*/

					});
				});

jQuery(function(){
	var t = jQuery("#gallery-switch");
	if (t.length) {

		t.switchify().data('switch').bind('switch:slide', function(e, type) {
			e.preventDefault();

			var controls = jQuery(this).data('controls'),
					s = jQuery('div.gallery-switch').find('div.text');
			controls[type]({ silent: true });

			if (type == 'on') s.html(gallerySwVal1);
			else s.html(gallerySwVal2);

		});
	}
});
