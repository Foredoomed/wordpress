<?php
/**
 * @package WordPress
 * @subpackage Theme
 */

get_header();

?>

<div id="content">
    <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
        <div class="post" id="post-<?php the_ID(); ?>">
	    <h2>
                <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a>
            </h2>
            <div class="postmetadata"><p>Posted on <?php the_time('F jS, Y') ?> under <?php the_category(', ') ?></p></div>
            <div class="entry">
                <?php the_content('Continue reading &raquo;'); ?>
             <span class="tag"><?php if (function_exists('the_tags')) {the_tags('Tags: ', ', ', ''); }  ?></span>
	    </div>
        </div>
     <?php endwhile; ?>
     <?php if (show_posts_link()) : ?>
         <div class="navigation">
	     <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
                 <div class="alignleft"><?php next_posts_link('&larr; Previous Entries') ?></div>
		 <div class="alignright"><?php previous_posts_link('Next Entries &rarr;') ?></div>
	     <?php } ?>
	 </div>
     <?php endif; ?>
     <?php else : ?>
         <h2 class="notfound">Sorry, Not Found. Please try again.</h2>
     <?php endif; ?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
