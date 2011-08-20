<div id="sidebar-1" class="sidebar">

	<ul class="xoxo sidebar-items">
	<?php if ( !dynamic_sidebar('Sidebar One') ) : ?><!--sidebar-1 widgets start-->
		
		<!--Categories Widget-->
		<?php the_widget('WP_Widget_Categories', 'count=0&hierarchical=1&dropdown=0' ,'before_widget=<li class="widget widget_categories">&after_widget=</li>'); ?>
		
		<!--Archives Widget-->
		<?php the_widget('WP_Widget_Archives', 'count=0&dropdown=0','before_widget=<li class="widget widget_archive">&after_widget=</li>'); ?>
		
	<?php endif; ?><!--sidebar-1 widgets end-->
	</ul>

</div><!--#sidebar-1-->