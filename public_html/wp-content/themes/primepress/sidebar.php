<?php 
global $options;
foreach ($options as $value) {
    if ( get_option($value['id']) === FALSE && isset($value['std']) ) { $$value['id'] = $value['std']; } 
      else { $$value['id'] = get_option($value['id']); } 
} ?>
<div id="secondary">

<?php include(TEMPLATEPATH . '/subscribe.php'); ?>

<div id="pp-sidebars" class="clearfix">

<?php if ($pp_sidebar_option != '2 sidebars') {
	get_sidebar('wide');
} ?>

<?php if ($pp_sidebar_option != '1 sidebar') {
	get_sidebar('one');
	get_sidebar('two');
} ?>

</div><!--#pp-sidebars-->

</div><!--#secondary-->