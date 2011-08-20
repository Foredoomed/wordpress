<?php get_header(); ?>
	
	<div id="primary" class="looped">
		
		<?php if(have_posts()) : ?>
		
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
				<?php the_post_thumbnail('thumbnail'); ?>
				<?php pp_the_content(); ?>
				<?php wp_link_pages('before=<p><strong>' . __('Pages:') . '</strong>&after=</p>'); ?>
			</div>
			
			<p class="entry-meta"><span class="entry-categories"><?php _e('Posted in: '); ?><?php the_category(', '); ?>.</span><br />
			<?php if(function_exists('the_tags')) { ?>
				<span class="entry-tags"><?php the_tags('Tagged: ',' &middot; ','<br />'); ?></span>
			<?php } ?></p>
		</div><!--.entry-->
		
		<?php endwhile; ?>
		
		<?php get_template_part('navigation'); ?>
		
		<?php else : ?>
		
		<div class="entry">
			<h2 class="entry-title"><?php _e('Not Found'); ?></h2>
			<div class="entry-content">
			<p>Sorry, what you are looking for isn't here.</p>
			<?php get_search_form(); ?>
			</div>
		</div>
		
		<?php endif; ?>	

	</div><!--#primary-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>