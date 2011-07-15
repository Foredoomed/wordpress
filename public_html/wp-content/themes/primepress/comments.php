<?php
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	if ( post_password_required() ) {
		echo '<p class="nopassword">This post is password protected. Enter the password to view comments.</p>';
		return;
	}
	if ( is_page() && !have_comments() && !comments_open() && !pings_open() )
		return;
?>

<div id="comments">	

<?php
	if ( have_comments() ) : ?>
	
	<h3 class="comments-number"><?php comments_number('No Comments', 'One Comment', '% Comments' );?></h3>
	
	<ol class="commentlist">
		<?php wp_list_comments(array('style' => 'ol')); ?>
	</ol>
	
<?php if ( get_option('page_comments') && get_comment_pages_count() > 1 ) { ?>
	<div class="navigation comment-nav clearfix">
		<?php paginate_comments_links( array('prev_text' => '&#8592; Older Comments', 'next_text' => 'Newer Comments &#8594;') ); ?>
	</div>
<?php } ?>	
	<?php else : // this is displayed if there are no comments so far ?>
		<?php if ( !comments_open() ) {
			echo "<p class='nocomments'>Comments are closed.</p>";
		}
		
	endif; // have_comments()
?>

<?php comment_form(); ?>

</div><!--#comments-->