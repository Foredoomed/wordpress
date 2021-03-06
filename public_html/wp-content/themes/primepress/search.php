<?php get_header(); ?>
	
	<div id="primary" class="looped">
		
		<?php pp_breadcrubs(); ?>
		
		<?php if(have_posts()) : ?>
		
		<h1 class="page-title">Search Results for '<?php the_search_query(); ?>' &darr;</h1>
		
		<?php while(have_posts()) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
			
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permalink to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
			
			<div class="entry-byline">
				<a class="entry-date" rel="bookmark" title="<?php the_time('Y-m-d\TH:i:sO'); ?>" href="<?php the_permalink(); ?>"><abbr class="updated" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php the_time('M jS, Y'); ?></abbr></a>
				<address class="author vcard"><?php _e('by '); ?><a class="url fn" href="<?php the_author_meta('user_url'); ?>"><?php the_author(); ?></a>. </address>
				<?php comments_popup_link('No comments yet', '1 comment', '% comments', 'comments-link', 'Comments are off for this post'); ?>
				<?php edit_post_link('Edit', '[', ']'); ?>
			</div>
			
			<div class="entry-content">
				<?php the_excerpt(); ?>
			</div>
		</div><!--.entry-->
		
		<?php endwhile; ?>
		
		<?php get_template_part('navigation'); ?>
		
		<?php else : ?>
		
		<div class="entry">
			<h1 class="page-title">No posts found. Try a different search?</h1>
			<div class="entry-content">
			<?php get_search_form(); ?>
			</div>
		</div>
		
		<?php endif; ?>	

	</div><!--#primary-->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>