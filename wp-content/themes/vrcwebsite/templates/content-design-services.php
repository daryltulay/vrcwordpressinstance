<div id="about-us">
	<div class="row clearfix">
		<div class="col-xs-3 column services-photo">
			<div class="services-grey-up"></div>
			<div class="services-white-down"></div>
		</div>	
		<div class="col-xs-8 column">
			<?php while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
			<?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
			<?php endwhile; ?>
		</div>
	</div>
</div>