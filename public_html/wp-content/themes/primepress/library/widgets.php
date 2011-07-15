<?php 
function pp_widgets_init() {
	register_sidebar(array(
		'name' => 'Sidebar Wide',
		'id' => 'sidebar-wide',
		'description' => 'The is the sidebar you see at the top when you use the 1 or 3 sidebar layout',
		'berfore_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));
	
	register_sidebar(array(
		'name' => 'Sidebar One',
		'id' => 'sidebar-one',
		'description' => 'The is the narrow sidebar on the left when you use the 2 or 3 sidebar layout',
		'berfore_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));
	
	register_sidebar(array(
		'name' => 'Sidebar Two',
		'id' => 'sidebar-two',
		'description' => 'The is the narrow sidebar on the right when you use the 2 or 3 sidebar layout',
		'berfore_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));
}
add_action('widgets_init', 'pp_widgets_init');
?>