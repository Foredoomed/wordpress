<div id="pp-subscribe" class="clearfix<?php if ($pp_feed_id) echo ' pp-email-true'; ?>">
	<ul class="clearfix">
	<li id="pp-feed">
		<a href="<?php if ($pp_feed_address) {echo $pp_feed_address;} else {bloginfo('rss2_url');} ?>" title="Subscribe to this Feed via RSS">Subscribe <span class="email-narrow">to this Feed</span> via RSS</a>
	</li>

	<?php if ($pp_feed_id) { ?>
	<li id="pp-email">
		<a target="_blank" href="http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $pp_feed_id; ?>" title="Subscribe via Email" >Subscribe via eMail</a>
	</li>
	<?php } ?>
	</ul>
</div>