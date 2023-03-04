<!-- .main-content -->
</div>

<!-- Footer -->
<footer class="py-4 bg-secondary">
	<div class="container">
		<div class="row">
			<div class="col-sm-4 py-3">
                <?php
                wp_nav_menu( array(
                    'theme_location'  => 'footer-left',
                    'depth'           => 1,
                    'container'       => 'div',
                    'container_class' => 'navbar',
                    'container_id'    => 'footer-middle',
                    'menu_class'      => 'navbar-nav mr-auto',
                    'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                    'walker'          => new WP_Bootstrap_Navwalker(),
                ) );
                ?>
			</div>
			<div class="col-sm-4 py-3">
			    <?php
                wp_nav_menu( array(
                    'theme_location'  => 'footer-center',
                    'depth'           => 1,
                    'container'       => 'div',
                    'container_class' => 'navbar',
                    'container_id'    => 'footer-middle',
                    'menu_class'      => 'navbar-nav mr-auto',
                    'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                    'walker'          => new WP_Bootstrap_Navwalker(),
                ) );
                ?>
			</div>
			<div class="col-sm-4 py-3">
			    <?php
                wp_nav_menu( array(
                    'theme_location'  => 'footer-right',
                    'depth'           => 1,
                    'container'       => 'div',
                    'container_class' => 'navbar',
                    'container_id'    => 'footer-end',
                    'menu_class'      => 'navbar-nav mr-auto',
                    'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                    'walker'          => new WP_Bootstrap_Navwalker(),
                ) );
                ?>
			</div>
		</div>
	</div>	
</footer>
<!-- Sub Footer -->
<div class="container-fluid sub-footer bg-primary">
	<div class="container">
		<div class="row">
			<div class="col-12 py-1">
				<?php
				wp_nav_menu( array(
					'theme_location'  => 'footer-bottom',
					'depth'           => 1,
					'container'       => 'div',
					'container_class' => 'pe-4',
					'container_id'    => 'footer-menu-bottom',
					'menu_class'      => 'navbar-nav d-flex flex-lg-row justify-content-md-between',
					'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
					'walker'          => new WP_Bootstrap_Navwalker(),
				) );
				?>
			</div>
		</div>
	</div>
</div>

<?php wp_footer(); ?>

</body>

</html>