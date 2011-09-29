<div id="pp-subscribe" class="clearfix<?php if ($pp_feed_id) echo ' pp-email-true'; ?>">
	<ul class="clearfix">
	<li>
        <a href="http://feeds.feedburner.com/zhixingheyi" title="Subscribe to my feed" rel="alternate" type="application/rss+xml"><img src="http://www.feedburner.com/fb/images/pub/feed-icon32x32.png" alt="Subscribe in a reader" style="border:0;vertical-align:middle"/></a><a href="http://feeds.feedburner.com/zhixingheyi" title="Subscribe to my feed" rel="alternate" type="application/rss+xml">Subscribe in a reader</a>
	</li>
<li>
<a target="_blank" href="https://twitter.com/nauxuil" title="Follow me on Twitter">
<img src="/twitter_32x32.png" alt="Follow me on Twitter" style="border:0;vertical-align:middle"/></a><a target="_blank" href="https://twitter.com/nauxuil" title="Follow me on Twitter">Follow me on Twitter</a>
</li>
	<?php if ($pp_feed_id) { ?>
	<li id="pp-email">
		<a target="_blank" href="http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $pp_feed_id; ?>" title="Subscribe via Email" >Subscribe via eMail</a>
	</li>
	<?php } ?>
	</ul>
</div>