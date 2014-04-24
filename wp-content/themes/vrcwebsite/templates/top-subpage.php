<div class="top subpage-container darkify">
	<div class="container">
		<div class="row clearfix">
			<div class="col-md-12 column">
				<?php get_template_part('templates/logo');?> <!-- get logo -->
				<div data-spy="affix" data-offset-top="200">
				<?php get_template_part('templates/header-top-navbar');?></div> <!-- get nav bar -->
				<div style="position: relative; padding-bottom: 40px;">
					
					<?php
					while (have_posts()) : the_post();
					$slug = basename(get_permalink());
					endwhile;
					
					switch ($slug) {
		case "about-us":
			get_template_part('templates/jumbotron', 'about');
			break;
		case "projects":
			get_template_part('templates/jumbotron', 'projects');
			break;
		case "design-services":
			get_template_part('templates/jumbotron', 'design');
			break;
		case "contact-us":
			get_template_part('templates/jumbotron', 'contact');
			break;
		default:
			get_template_part('templates/jumbotron', 'page');
	}


					
					?>
				</div>
			</div>
		</div>
	</div>
</div>
