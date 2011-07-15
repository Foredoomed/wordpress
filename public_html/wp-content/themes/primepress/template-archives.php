<?php
/**
 * Template Name: Archives
 *
 * This template will display your Category, Tag and Monthly archives.
 */
?>

<?php get_header(); ?>
	
	<div id="primary">
		
		<?php pp_breadcrubs(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
			
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php bloginfo('name'); ?> Archives"><?php the_title(); ?></a></h1>
			
			<div class="entry-content">
				<p>The following is the list of archives organized by time and category.</p>
				<h2><?php _e('Category Archives') ?></h2>
				<ul class="archives-list">
					<?php wp_list_categories('title_li=&show_count=1'); ?>
				</ul>
				
				<h2><?php _e('Tag Archives') ?></h2>
				<p>
					<?php wp_tag_cloud('number=0'); ?>
				</p>
				
				<h2><?php _e('Monthly Archives') ?></h2>
				<ul class="archives-list">
					<?php wp_get_archives('show_post_count=1'); ?>
				</ul>
			</div>
			
		</div><!--.entry-->
		
	</div><!--#primary-->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>