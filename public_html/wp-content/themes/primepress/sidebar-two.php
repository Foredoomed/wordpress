<div id="sidebar-2" class="sidebar">
	
	<ul class="xoxo sidebar-items">
	<?php if ( !dynamic_sidebar('Sidebar Two') ) : ?><!--sidebar-2 widgets start-->
		
		<!--Calendar Widget-->
		<?php the_widget('WP_Widget_Calendar', 'title=Calendar','before_widget=<li class="widget widget_calendar">&after_widget=</li>'); ?>
		
		<!--Pages Widget-->
		<?php the_widget('WP_Widget_Pages', '','before_widget=<li class="widget widget_pages">&after_widget=</li>'); ?>
		
		<!--Meta Widget-->
		<?php the_widget('WP_Widget_Meta', '','before_widget=<li class="widget widget_meta">&after_widget=</li>'); ?>
		
	<?php endif; ?><!--sidebar-2 widgets end-->
	</ul>

</div><!--#sidebar-2-->