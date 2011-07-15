<?php
/**
 * Template Name: Sitemap
 *
 * This template displays a HTML based sitemap of your site listing all your Pages, posts, Category, Tag and Monthly archives.
 */
?>
<?php get_header(); ?>
	
	<div id="primary">
		
		<?php pp_breadcrubs(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
			
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php bloginfo('name'); ?> Sitemap"><?php the_title(); ?></a></h1>
			
			<div class="entry-content">
				<h2><?php _e('Pages') ?></h2>
				<ul>
					<?php wp_list_pages('title_li='); ?>
				</ul>
				
				<h2><?php _e('Posts') ?></h2>
				<ul>
					<?php wp_get_archives('type=postbypost'); ?>
				</ul>
				
				<h2><?php _e('Category Archives') ?></h2>
				<ul>
					<?php wp_list_categories('title_li='); ?>
				</ul>
				
				<h2><?php _e('Tag Archives') ?></h2>
				<p>
					<?php wp_tag_cloud('number=0'); ?>
				</p>
				
				<h2><?php _e('Monthly Archives') ?></h2>
				<ul>
					<?php wp_get_archives(); ?>
				</ul>
			</div>
			
		</div><!--.entry-->
		
	</div><!--#primary-->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>