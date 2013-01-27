<?php
function wp_print_editorial_media_templates() {
    global $is_IE;
    $class = 'media-modal wp-core-ui';
    if ( $is_IE && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7') !== false )
        $class .= ' ie7';
    ?>
    <script type="text/html" id="tmpl-gallery-settings-editorial">
		<!--h3><?php _e('Gallery Settings'); ?></h3>

		<label class="setting">
			<span><?php _e('Link To'); ?></span>
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
			<span><?php _e('Columns'); ?></span>
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
			<span><?php _e( 'Random Order' ); ?></span>
			<input type="checkbox" data-setting="_orderbyRandom" />
		</label-->
	</script>

    <script type="text/html" id="tmpl-attachment-details-editorial">
		<h3>
			<?php _e('Attachment Details'); ?>
			<span class="settings-save-status">
				<span class="spinner"></span>
				<span class="saved"><?php esc_html_e('Saved.'); ?></span>
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
						<a class="edit-attachment" href="{{ data.editLink }}&amp;image-editor" target="_blank"><?php _e( 'Edit Image' ); ?></a>
						<a class="refresh-attachment" href="#"><?php _e( 'Refresh' ); ?></a>
					<# } #>
				<# } #>

				<# if ( ! data.uploading && data.can.remove ) { #>
					<a class="delete-attachment" href="#"><?php _e( 'Delete Permanently' ); ?></a>
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
				<span><?php _e('Title'); ?></span>
				<input type="text" value="{{ data.title }}" {{ maybeReadOnly }} />
			</label>
			<!--label class="setting" data-setting="caption">
				<span><?php _e('Caption'); ?></span>
				<textarea {{ maybeReadOnly }}>{{ data.caption }}</textarea>
			</label-->
		<# if ( 'image' === data.type ) { #>
			<!--label class="setting" data-setting="alt">
				<span><?php _e('Alt Text'); ?></span>
				<input type="text" value="{{ data.alt }}" {{ maybeReadOnly }} />
			</label-->
		<# } #>
			<label class="setting" data-setting="description">
				<span><?php _e('Description'); ?></span>
				<textarea {{ maybeReadOnly }}>{{ data.description }}</textarea>
			</label>
	</script>
	<script type="text/html" id="tmpl-attachment-display-settings-editorial">
		<h3><?php _e('Attachment Display Settings'); ?></h3>

		<# if ( 'image' === data.type ) { #>
			<!--label class="setting">
				<span><?php _e('Alignment'); ?></span>
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
			</label-->
		<# } #>

		<div class="setting">
			<label>
				<span><?php _e('Link To'); ?></span>
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
				<span><?php _e('Size'); ?></span>
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
						'full'      => __('Full Size'),
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
				<span><?php _e('Caption'); ?></span>
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
			<span><?php _e('Title'); ?></span>
			<input type="text" class="alignment" data-setting="title" value="{{ data.model.title }}" />
		</label>

	</script>
	<script type="text/html" id="tmpl-embed-video-settings-editorial">
		<div class="thumbnail video">
			<img src="{{ data.model.data.url }}" draggable="false" />
		</div>
		<div class="message-editorial"></div>
		<?php if ( ! apply_filters( 'disable_captions', '' ) ) : ?>
			<label class="setting caption video">
				<span><?php _e('Caption'); ?></span>
				<textarea data-setting="caption" >{{ data.model.data.title }}</textarea>
			</label>
		<?php endif; ?>

	</script>
	<script type="text/html" id="tmpl-embed-link-settings-editorial">
		<div class="thumbnail link">
			<img src="{{ data.model.url }}" draggable="false" />
		</div>
		<div class="editorial-message">{{ data.model.error }}</div>
	</script>
	<?php }?>
