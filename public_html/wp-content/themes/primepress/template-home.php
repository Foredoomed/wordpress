<?php
/**
 * Template Name: HomePage
 *
 * This is a quick custom template that gets rid of the entry title and entry meta.
 * Use this if you are following the tutorial 
 * @link http://www.techtrot.com/primepress/setting-up-the-wordpress-static-homepage/
 */
?>

<?php get_header(); ?>
	
	<div id="primary">
		
		<?php if(have_posts()) : ?>
		
		<?php while(have_posts()) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
			
			<div class="entry-content">
				<?php the_content(); ?>
				<?php wp_link_pages('before=<p><strong>' . __('Pages:') . '</strong>&after=</p>'); ?>
			</div>
			
		</div><!--.entry-->
		
		<?php endwhile; ?>
		<?php endif; ?>	

	</div><!--#primary-->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>