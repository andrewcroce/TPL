<form id="searchform" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">

	<div class="row collapse">

		<div class="large-8 small-9 columns">
			<label class="screenreader" for="s"><?php echo __( 'Search' ); ?></label>
			<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="<?php echo __('Search','gnjumc'); ?>"/>
		</div>

		<div class="large-4 small-3 columns">
			<button type="submit" class="postfix expand"><?php echo __('Search','gnjumc'); ?></button>
		</div>

	</div>

</form>