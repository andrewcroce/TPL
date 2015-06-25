				</div><!-- end #main-content -->

				<footer id="main-footer">

					<div class="row">
						
						<div class="large-12 columns">

							<ul class="inline-list secondary-text">
							
								<li><a href="<?php echo home_url(); ?>">©<?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?></a></li>

								<?php if( is_user_logged_in() ) : ?>
									
									<li><a href="<?php echo wp_logout_url(); ?>"><?php echo __('Logout','theme'); ?></a></li>

									<li><a href="<?php echo home_url('profile'); ?>"><?php echo __('Profile','theme'); ?></a></li>
								
								<?php else : ?>
									
									<li><a href="<?php echo home_url('login'); ?>"><?php echo __('Login','theme'); ?></a></li>

								<?php endif; ?>

							</ul>
							
						</div>

					</div>

				</footer>

				<a class="exit-off-canvas"></a>

			</main>

		</div><!-- end .inner-wrap -->
	</div><!-- end .off-canvas-wrap -->

	

	<?php wp_footer(); ?>

</body>
</html>