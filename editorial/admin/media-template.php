<?php
function wp_print_editorial_media_templates() {
    global $is_IE;
    $class = 'media-modal wp-core-ui';
    if ( $is_IE && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7') !== false )
        $class .= ' ie7';
    ?>
    <script type="text/html" id="tmpl-gallery-settings-editorial">
		<!--h3><?php _e('Gallery Settings', 'Editorial'); ?></h3>

		<label class="setting">
			<span><?php _e('Link To', 'Editorial'); ?></span>
			<select class="link-to"
				data-setting="link"
				<# if ( data.userSettings ) { #>
					data-user-setting="urlbutton"
				<# } #>>

				<option value="post" selected>
					<?php esc_attr_e('Attachment Page'); ?>
				</option>
				<option value="file">
					<?php esc_attr_e('Media File'); ?>
				</option>
			</select>
		</label>

		<label class="setting">
			<span><?php _e('Columns', 'Editorial'); ?></span>
			<select class="columns" name="columns"
				data-setting="columns">
				<?php for ( $i = 1; $i <= 9; $i++ ) : ?>
					<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $i, 3 ); ?>>
						<?php echo esc_html( $i ); ?>
					</option>
				<?php endfor; ?>
			</select>
		</label>

		<label class="setting">
			<span><?php _e( 'Random Order', 'Editorial' ); ?></span>
			<input type="checkbox" data-setting="_orderbyRandom" />
		</label-->
	</script>
    <script type="text/html" id="tmpl-attachment-details-editorial">
		<h3>
			<?php _e('Attachment Details', 'Editorial'); ?>
			<span class="settings-save-status">
				<span class="spinner"></span>
				<span class="saved"><?php esc_html_e('Saved.', 'Editorial'); ?></span>
			</span>
		</h3>
		<div class="attachment-info">
			<div class="thumbnail">
				<# if ( data.uploading ) { #>
					<div class="media-progress-bar"><div></div></div>
				<# } else if ( 'image' === data.type ) { #>
					<img src="{{ data.size.url }}" draggable="false" />
				<# } else { #>
					<img src="{{ data.icon }}" class="icon" draggable="false" />
				<# } #>
			</div>
			<div class="details">
				<div class="filename">{{ data.filename }}</div>
				<div class="uploaded">{{ data.dateFormatted }}</div>

				<# if ( 'image' === data.type && ! data.uploading ) { #>
					<# if ( data.width && data.height ) { #>
						<div class="dimensions">{{ data.width }} &times; {{ data.height }}</div>
					<# } #>

					<# if ( data.can.save ) { #>
						<a class="edit-attachment" href="{{ data.editLink }}&amp;image-editor" target="_blank"><?php _e( 'Edit Image', 'Editorial' ); ?></a>
						<a class="refresh-attachment" href="#"><?php _e( 'Refresh', 'Editorial' ); ?></a>
					<# } #>
				<# } #>

				<# if ( ! data.uploading && data.can.remove ) { #>
					<a class="delete-attachment" href="#"><?php _e( 'Delete Permanently', 'Editorial' ); ?></a>
				<# } #>

				<div class="compat-meta">
					<# if ( data.compat && data.compat.meta ) { #>
						{{{ data.compat.meta }}}
					<# } #>
				</div>
			</div>
		</div>

		<# var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly'; #>
			<label class="setting" data-setting="title">
				<span><?php _e('Title', 'Editorial'); ?></span>
				<input type="text" value="{{ data.title }}" {{ maybeReadOnly }} />
			</label>
			<label class="setting" data-setting="caption">
				<span><?php _e('Caption', 'Editorial'); ?></span>
				<textarea {{ maybeReadOnly }}>{{ data.caption }}</textarea>
			</label>
		<# if ( 'image' === data.type ) { #>
			<!--label class="setting" data-setting="alt">
				<span><?php _e('Alt Text', 'Editorial'); ?></span>
				<input type="text" value="{{ data.alt }}" {{ maybeReadOnly }} />
			</label-->
		<# } #>
		<# if ( 'image' === data.type ) { #>
			<!--label class="setting" data-setting="description">
				<span><?php _e('Content', 'Editorial'); ?></span>
				<textarea readonly="readonly">{{ data.description }}</textarea>
			</label-->
		<# } #>
	</script>
	<script type="text/html" id="tmpl-attachment-display-settings-editorial">
		<h3><?php _e('Attachment Display Settings', 'Editorial'); ?></h3>

		<# if ( 'image' === data.type ) { #>
			<label class="setting">
				<span><?php _e('Alignment', 'Editorial'); ?></span>
				<select class="alignment"
					data-setting="align"
					<# if ( data.userSettings ) { #>
						data-user-setting="align"
					<# } #>>

					<option value="left">
						<?php esc_attr_e('Left'); ?>
					</option>
					<option value="center">
						<?php esc_attr_e('Center'); ?>
					</option>
					<option value="right">
						<?php esc_attr_e('Right'); ?>
					</option>
					<option value="none" selected>
						<?php esc_attr_e('None'); ?>
					</option>
				</select>
			</label>
		<# } #>

		<div class="setting">
			<label>
				<span><?php _e('Link To', 'Editorial'); ?></span>
				<select class="link-to"
					data-setting="link"
					<# if ( data.userSettings ) { #>
						data-user-setting="urlbutton"
					<# } #>>

					<option value="custom">
						<?php esc_attr_e('Custom URL'); ?>
					</option>
					<option value="post" selected>
						<?php esc_attr_e('Attachment Page'); ?>
					</option>
					<option value="file">
						<?php esc_attr_e('Media File'); ?>
					</option>
					<option value="none">
						<?php esc_attr_e('None'); ?>
					</option>
				</select>
			</label>
			<input type="text" class="link-to-custom" data-setting="linkUrl" />
		</div>

		<# if ( 'undefined' !== typeof data.sizes ) { #>
			<label class="setting">
				<span><?php _e('Size', 'Editorial'); ?></span>
				<select class="size" name="size"
					data-setting="size"
					<# if ( data.userSettings ) { #>
						data-user-setting="imgsize"
					<# } #>>
					<?php

					$sizes = apply_filters( 'image_size_names_choose', array(
						//'thumbnail' => __('Thumbnail'),
					//	'medium'    => __('Medium'),
						//'large'     => __('Large'),
						'full'      => __('Full Size', 'Editorial'),
					) );

					foreach ( $sizes as $value => $name ) : ?>
						<#
						var size = data.sizes['<?php echo esc_js( $value ); ?>'];
						if ( size ) { #>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, 'full' ); ?>>
								<?php echo esc_html( $name ); ?>
							</option>
						<# } #>
					<?php endforeach; ?>
				</select>
			</label>
		<# } #>

	</script>
	<script type="text/html" id="tmpl-embed-image-settings-editorial">
		<div class="thumbnail">
			<img src="{{ data.model.thumbnail }}" draggable="false" />
		</div>
		<div class="message-editorial"></div>
		<?php if ( ! apply_filters( 'disable_captions', '' ) ) : ?>
			<label class="setting caption">
				<span><?php _e('Caption', 'Editorial'); ?></span>
				<textarea data-setting="caption" >{{ data.model.title }}</textarea>
			</label>
		<?php endif; ?>

	</script>
	<script type="text/html" id="tmpl-embed-smartlink-settings-editorial">
		<div class="thumbnail">
			<img src="{{ data.model.url }}" draggable="false" />
		</div>
		<div class="message-editorial"></div>
		<label class="setting">
			<span><?php _e('Title', 'Editorial'); ?></span>
			<input type="text" class="alignment" data-setting="title" value="{{ data.model.title }}" />
		</label>

	</script>
	<script type="text/html" id="tmpl-embed-video-settings-editorial">
	<div class="message-editorial"></div>
	<br>
	<div class="thumbnail video">
			<img src="{{ data.model.data.url }}" draggable="false" />
		</div>
		<input type="hidden" name="media-type" value="video" class="media-type"/>

		<?php if ( ! apply_filters( 'disable_captions', '' ) ) : ?>
			<label class="setting caption video">
				<span><?php _e('Caption', 'Editorial'); ?></span>
				<textarea data-setting="caption" >{{ data.model.data.title }}</textarea>
			</label>
		<?php endif; ?>

	</script>
	<script type="text/html" id="tmpl-embed-link-settings-editorial">

		<div class="editorial-message">{{ data.model.error }}</div>
	</script>
	<script type="text/html" id="tmpl-attachment-editorial">
		<div class="attachment-preview type-{{ data.type }} subtype-{{ data.subtype }} {{ data.orientation }}">
			<# if ( data.uploading ) { #>
				<div class="media-progress-bar"><div></div></div>
			<# } else if ( 'image' === data.type ) { #>

				<div class="thumbnail">
					<div class="centered">
					<# if (data.is_embed_video){ #><img src="<?php bloginfo('template_directory') ?>/images/video-icon.png" class="icon" draggable="false" style="z-index:10" /><# } #>
						<img src="{{ data.size.url }}" draggable="false" />
					</div>
				</div>
			<# } else { #>
				<img src="{{ data.icon }}" class="icon" draggable="false" />
				<div class="filename">
					<div>{{ data.filename }}</div>
				</div>
			<# } #>

			<# if ( data.buttons.close ) { #>
				<a class="close media-modal-icon" href="#" title="<?php _e('Remove', 'Editorial'); ?>"></a>
			<# } #>

			<# if ( data.buttons.check ) { #>
				<a class="check" href="#" title="<?php _e('Deselect', 'Editorial'); ?>"><div class="media-modal-icon"></div></a>
			<# } #>
		</div>
		<#
		var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly';
		if ( data.describe ) { #>
			<# if ( 'image' === data.type ) { #>
				<input type="text" value="{{ data.caption }}" class="describe" data-setting="caption"
					placeholder="<?php esc_attr_e('Caption this image&hellip;'); ?>" {{ maybeReadOnly }} />
			<# } else { #>
				<input type="text" value="{{ data.title }}" class="describe" data-setting="title"
					<# if ( 'video' === data.type ) { #>
						placeholder="<?php esc_attr_e('Describe this video&hellip;'); ?>"
					<# } else if ( 'audio' === data.type ) { #>
						placeholder="<?php esc_attr_e('Describe this audio file&hellip;'); ?>"
					<# } else { #>
						placeholder="<?php esc_attr_e('Describe this media file&hellip;'); ?>"
					<# } #> {{ maybeReadOnly }} />
			<# } #>
		<# } #>
	</script>
	<?php }?>