<?php if (is_single()) : ?>

	<div class="navigation">
		<span class="navleft"><?php previous_post_link('&#8592; %link') ?></span>
		<span class="navright"><?php next_post_link('%link &#8594;') ?></span>
	</div>

<?php else : ?>

	<div class="navigation">
	<?php 
		if (function_exists('wp_pagenavi')) {
			wp_pagenavi();
		} else {
	?>
		<span class="navleft"><?php next_posts_link('&#8592; Earlier Posts') ?></span>
		<span class="navright"><?php previous_posts_link('Newer Posts &#8594;') ?></span>
		<?php } ?>
	</div>

<?php endif; ?>