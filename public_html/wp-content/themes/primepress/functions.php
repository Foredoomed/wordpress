<?php 

require_once(TEMPLATEPATH . '/library/pp-options.php');
require_once(TEMPLATEPATH . '/library/widgets.php');

add_action( 'init', 'primepress_register_menus' );
function primepress_register_menus() {
	register_nav_menus(
		array(
			'primary-menu' => 'Primary Menu'
		)
	);
}
// add support for post thumbnails
add_theme_support('post-thumbnails');
// add the feed links in the head
add_theme_support('automatic-feed-links');
// enable the custom background option
add_custom_background();

//Set the content width.
	if ( (!isset($content_width)) && (get_option('pp_layout_setup') == 'content-480px' ) ) {
		$content_width = 480;
	} elseif ( (!isset($content_width)) && (get_option('pp_layout_setup') == 'content-580px' ) ) {
		$content_width = 580;
	} elseif ( (!isset($content_width)) && (get_option('pp_layout_setup') == 'content-620px' ) ) {
		$content_width = 620;
	} else {
		$content_width = 540;
	}

// link the post thumbnails to the post
add_filter( 'post_thumbnail_html', 'pp_post_image_html', 10, 3 );
function pp_post_image_html( $html, $post_id, $post_image_id ) {
	$html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '">' . $html . '</a>';
	return $html;
}

// exceprt options
function pp_the_content() {
	if ( ( get_option('pp_home_excerpts') == 'true' && is_home() ) || ( get_option('pp_category_excerpts') == 'true' && is_category() ) || ( get_option('pp_tag_excerpts') == 'true' && is_tag() ) || ( get_option('pp_date_excerpts') == 'true' && is_date() ) ) {
		the_excerpt();
	} else {
		the_content('阅读全文');
	}
}

function pp_excerpt_length( $length ) {
	if (get_option('pp_excerpt_length')) {
		return get_option('pp_excerpt_length');
	}
}
add_filter( 'excerpt_length', 'pp_excerpt_length' );

function pp_breadcrubs() {
	if ( function_exists('bcn_display') ) {
		echo '<div class="breadcrumb-nav">';
		bcn_display();
		echo '</div>';
	} elseif ( function_exists('yoast_breadcrumb') ) {
		echo '<div class="breadcrumb-nav">';
		yoast_breadcrumb('<p>','</p>');
		echo '</div>';
	}

}


/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function my_page_menu_args( $args ) {
	    $args['show_home'] = true;
	    return $args;
}
add_filter( 'wp_page_menu_args', 'my_page_menu_args' );


function mytheme_comment($comment, $args, $depth) {
   static $comment_number;
   $comment_number++;

   $GLOBALS['comment'] = $comment; ?>

   <?php $i = 0; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author vcard">
         <span class="comment-counter"><a href="#comment-<?php comment_ID() ?>" title="Permalink to this comment" rel="nofollow">#<?php echo $comment_number; ?></a></span>
         <?php echo get_avatar($comment,$size='32'); ?>

         <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      <?php endif; ?>

      <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','') ?></div>

      <?php comment_text() ?>
     <?php $i++; ?>
     </div>
<?php
        }
?>


























