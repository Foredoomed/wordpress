<?php get_header(); ?>
	
	<div id="primary" class="looped">
		
		<?php pp_breadcrubs(); ?>
		
		<?php if(have_posts()) : ?>
		<div class="archive-info">
		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		<?php /* If this is a category archive */ if (is_category()) { ?>
		<h1 class="page-title"><?php single_cat_title(); ?>&#58;</h1>
		<?php /* If this is a tag archive */ } elseif(function_exists('is_tag')&& is_tag()) { ?>
		<h1 class="page-title"><?php single_tag_title(); ?>&#58;</h1>
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h1 class="page-title"><?php the_time('F jS, Y'); ?>&#58;</h1>
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h1 class="page-title"><?php the_time('F, Y'); ?>&#58;</h1>
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h1 class="page-title"><?php the_time('Y'); ?>&#58;</h1>
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h1 class="page-title">Blog Archives</h1>
		<?php } ?>
		</div>
		<?php while(have_posts()) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
			
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permalink to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
			
			<div class="entry-byline">
				<a class="entry-date" rel="bookmark" title="<?php the_time('Y-m-d\TH:i:sO'); ?>" href="<?php the_permalink(); ?>"><abbr class="updated" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php the_time('M jS, Y'); ?></abbr></a>
				<address class="author vcard"><?php _e('by '); ?><a class="url fn" href="<?php the_author_meta('user_url'); ?>"><?php the_author(); ?></a>. </address>
				<?php comments_popup_link('No comments yet', '1 comment', '% comments', 'comments-link', 'Comments are off for this post'); ?>
&nbsp;<?php if(function_exists('the_views')) { the_views(); } ?>
				<?php edit_post_link('Edit', '[', ']'); ?>
			</div>
			
			<div class="entry-content">
				<?php the_post_thumbnail('thumbnail'); ?>
				<?php pp_the_content(); ?>
			</div>
		</div><!--.entry-->
		
		<?php endwhile; ?>
		
		<?php get_template_part('navigation'); ?>
		
		<?php endif; ?>	

	</div><!--#primary-->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>