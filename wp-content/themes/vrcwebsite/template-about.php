<?php
/*
Template Name: About Us Template
*/
?>
<!-- Nav tabs -->
<div class="container">
	<ul id="about-tabnav" class="nav nav-tabs redify">
		<li class="active"><a href="#about-us" data-toggle="tab">ABOUT US</a></li>
		<li><a href="#about-the-owner" data-toggle="tab">ABOUT THE OWNER</a></li>
		<li><a href="#our-team" data-toggle="tab">OUR TEAM</a></li>
	</ul>
<!-- Tab panes -->	
	<div class="tab-content">
		<div class="tab-pane active" id="about-us">
		<?php get_template_part('templates/content-about-us');  ?>	
	</div>
		<div class="tab-pane" id="about-the-owner">
	  	<?php get_template_part('templates/content-about-owner');  ?>
  	</div>
	  	<div class="tab-pane" id="our-team">
  		<?php get_template_part('templates/content-about-ourteam');  ?>
  </div>
	</div>
</div>