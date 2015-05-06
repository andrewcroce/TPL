<?php get_header();

tpl_content_wrapper_start();

if( have_posts() ){

	while( have_posts() ){ the_post();

		if( file_exists( dirname(__FILE__) . '/tpl_pages/page-' . $post->post_name . '.php' ) ) {

			get_template_part( 'tpl_pages/page', $post->post_name );

		} else {

			get_template_part( 'tpl_pages/page', 'default' );

		}

	}

}

tpl_content_wrapper_end();

get_footer(); ?>