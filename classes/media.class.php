<?php

if( !class_exists( 'Media' ) ) {

	class Media {

    public static function load() {

			if ( shortcode_exists( 'gallery' ) ) {
				/**
				 * Modify gallery options
				 * Turn gallery into carousel if "carousel" type is selected
				 */
				add_action('print_media_templates', function(){

				  // define backbone template
				  ?>
				  <script type="text/html" id="tmpl-gallery-type-setting">
				    <label class="setting">
				      <span><?php _e('Gallery Type'); ?></span>
				      <select data-setting="type">
								<option value="default_val"> Default Gallery </option>
				        <option value="carousel"> Carousel </option>
				      </select>
				    </label>
				  </script>

				  <script>

				    jQuery(document).ready(function(){

				      // add shortcode attribute and its default value to the gallery settings list
				      _.extend(wp.media.gallery.defaults, {
				        type: 'default_val'
				      });

				      // merge default gallery settings template
				      wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
				        template: function(view){
				          return wp.media.template('gallery-settings')(view)
				               + wp.media.template('gallery-type-setting')(view);
				        }
				      });

				    });

				  </script>
				  <?php

				});

				// Custom filter function to modify default gallery shortcode output
				function modified_gallery( $output = '', $atts, $instance ) {

					// Initialize
					global $post, $wp_locale;

					// Gallery instance counter
					static $instance = 0;
					$instance++;

					// if there's a gallery type defined...
					if ( array_key_exists( 'type', $atts ) ) {

						if ($atts['type'] == 'carousel') {

							if ( ! empty( $atts['include'] ) ) {
								$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
								$attachments = array();
								foreach ( $_attachments as $key => $val ) {
									$attachments[$val->ID] = $_attachments[$key];
								}
							} elseif ( ! empty( $atts['exclude'] ) ) {
								$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
							} else {
								$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
							}
							if ( empty( $attachments ) ) {
								return '';
							}

							ob_start(); ?>
							<div class="gallery gallery-carousel">

							<?php foreach ($attachments as $attachment):

								$img_meta = wp_prepare_attachment_for_js( $attachment ); ?>

								<li>
									<figure>
										<img src="<?php echo $img_meta['url']; ?>" alt="<?php echo $img_meta['alt']; ?>" />
										<?php if ( !empty( $img_meta['caption'] ) ) { ?>
											<figcaption><?php echo $img_meta['caption']; ?></figcaption>
										<?php } ?>
									</figure>
								</li>

							<?php endforeach; ?>

							</div>

							<?php $output = ob_get_clean();

						}

					}

					return $output;

				}

				// Apply filter to default gallery shortcode
				add_filter( 'post_gallery', 'modified_gallery', 10, 4 );

			}

    }

  }

}

if( class_exists('Media') ){
  Media::load();
}