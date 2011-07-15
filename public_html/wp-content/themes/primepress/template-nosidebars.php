<?php
/**
 * Template Name: No Sidebars
 *
 * This template gets rid of the sidebars and displays your content full width.
 */
?>

<?php get_header(); ?>
	
	<div id="primary" style="width:920px;">
		
		<?php pp_breadcrubs(); ?>
		
		<?php if(have_posts()) : ?>
		
		<?php while(have_posts()) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
			
			<h1 class="entry-title"><?php the_title(); ?></h1>
			
			<div class="entry-byline">
				<?php edit_post_link('Edit', '[', ']'); ?>
			</div>
			
			<div class="entry-content">
				<?php the_content(); ?>
				<?php wp_link_pages('before=<p><strong>' . __('Pages:') . '</strong>&after=</p>'); ?>
			</div>
			
		</div><!--.entry-->
		
		<?php comments_template(); ?>
		
		<?php endwhile; ?>
		<?php endif; ?>	

	</div><!--#primary-->

<?php get_footer(); ?>