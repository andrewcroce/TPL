				</div><!-- end #main-content -->

				<footer id="main-footer">

					<div class="row">
						
						<div class="large-12 columns">

							<ul class="inline-list secondary-text">
							
								<li><a href="<?php echo home_url(); ?>">©<?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?></a></li>

								<?php if( is_user_logged_in() ) : ?>

									<?php if( Settings::frontend_login_enabled() ) : ?>
										<li><?php tpl_link_logout(); ?></li>
									<?php endif; ?>
									
									<?php if( Settings::frontend_profile_enabled() ) : ?>
										<li><?php tpl_link_profile(); ?></li>
									<?php endif; ?>
								
								<?php else : ?>

									<?php if( Settings::frontend_login_enabled() ) : ?>
										<li><?php tpl_link_login(); ?></li>
									<?php endif; ?>

									<?php if( Settings::frontend_registration_enabled() ) : ?>
										<li><?php tpl_link_register(); ?></li>
									<?php endif; ?>

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