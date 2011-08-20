<?php 
$themename = "PrimePress";
$shortname = "pp";
$options = array (

	array(	"name" => "Layout Options",
			"desc" => "Choose your preferred layout.",
			"id" => $shortname."_admin_heading",
			"type" => "info"),

	array(	"name" => "Content Width",
			"desc" => "Select the width of the content column. sidebar width is automatically adjusted.",
			"id" => $shortname."_layout_setup",
			"type" => "select",
			"std" => "content-540px",
			"options" => array("content-480px", "content-540px", "content-580px", "content-620px")),

	array(	"name" => "Sidebar Options",
			"desc" => "How many sidebars do you want ?",
			"id" => $shortname."_sidebar_option",
			"type" => "select",
			"std" => "3 sidebars",
			"options" => array("1 sidebar", "2 sidebars", "3 sidebars")),

	array(	"name" => "FeedBurner",
			"desc" => "Your custom FeedBurner settings.",
			"id" => $shortname."_admin_heading",
			"type" => "info"),

	array(	"name" => "Feed Address",
			"desc" => "enter your full feed address.",
			"id" => $shortname."_feed_address",
			"std" => "",
			"type" => "text"),

	array(	"name" => "feedburner.com Address",
			"desc" => "enter your feedburner.com address to enable eMail subscription. The one that comes after http://feeds.feedburner.com/... (eg: ...urner.com/<span style='text-decoration: underline;'>primepress</span>)",
			"id" => $shortname."_feed_id",
			"std" => "",
			"type" => "text"),
			
	array(	"name" => "Excerpt Settings",
			"desc" => "Choose to display content or excepts.",
			"id" => $shortname."_admin_heading",
			"type" => "info"),
	
	array(	"name" => "Excerpt length",
			"desc" => "type in the excerpt length (no. of words)",
			"id" => $shortname."_excerpt_length",
			"std" => "55",
			"type" => "text"),

	array(	"name" => 'Excerpt options',
			"desc" => "display excerpts on the homepage.",
			"id" => $shortname."_home_excerpts",
			"std" => "false",
			"type" => "checkbox"),

	array(	"name" => '',
			"desc" => "display excerpts on Category pages.",
			"id" => $shortname."_category_excerpts",
			"std" => "false",
			"type" => "checkbox"),

	array(	"name" => '',
			"desc" => "display excerpts on Tag pages.",
			"id" => $shortname."_tag_excerpts",
			"std" => "false",
			"type" => "checkbox"),

	array(	"name" => '',
			"desc" => "display excerpts on Archive pages.",
			"id" => $shortname."_date_excerpts",
			"std" => "false",
			"type" => "checkbox")

);

function mytheme_add_admin() {
	global $themename, $shortname, $options;
	if ( isset($_GET['page']) && $_GET['page'] == basename(__FILE__) ) {
		if ( isset($_REQUEST['action']) && 'save' == $_REQUEST['action'] ) {
			foreach ($options as $value) {
				update_option( $value['id'], $_REQUEST[$value['id']] ); }
			foreach ($options as $value) {
                if( isset($_REQUEST[$value['id']]) ) { update_option( $value['id'], $_REQUEST[$value['id']]  ); } else { delete_option( $value['id'] ); } }
			header("Location: themes.php?page=pp-options.php&saved=true");
			die;
		} else if( isset($_REQUEST['action']) && 'reset' == $_REQUEST['action'] ) {
			foreach ($options as $value) {
				delete_option( $value['id'] ); }
			header("Location: themes.php?page=pp-options.php&reset=true");
			die;
		}
	}
	
	add_theme_page($themename." Options", "PrimePress Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');

}

function mytheme_admin() {
	global $themename, $shortname, $options;
	if ( isset($_REQUEST['saved']) ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
	if ( isset($_REQUEST['reset']) ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';

?>
<div class="wrap">
<h2><?php echo $themename; ?> Settings</h2>

<form method="post">

<table class="form-table">

<?php foreach ($options as $value) { 
    
if ($value['type'] == "text") { ?>

<tr valign="middle"> 
    <th scope="row" ><?php echo $value['name']; ?>:</th>
    <td>
        <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" size="50" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" /><br />
		<?php echo $value['desc']; ?>
    </td>
</tr>

<?php } elseif ($value['type'] == "info") { ?>

    <tr><th scope="row" colspan="2" style="font-size:15px; font-weight:bold;"><?php echo $value['name']; ?></th></tr>
	<tr><th scope="row" colspan="2" style="font-size:13px; font-weight:normal;"><?php echo $value['desc']; ?></th></tr>

<?php } elseif ($value['type'] == "select") { ?>

    <tr valign="middle"> 
        <th scope="row"><?php echo $value['name']; ?>:</th>
        <td>
            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                <?php foreach ($value['options'] as $option) { ?>
                <option<?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ( get_option($value['id'] ) == NULL && $option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
				<?php } ?>
            </select><br />
			<?php echo $value['desc']; ?>
        </td>
    </tr>

<?php } elseif ($value['type'] == "info") { ?>

    <tr><th scope="row" colspan="2" style="font-size:15px; font-weight:bold;"><?php echo $value['name']; ?></th></tr>
	<tr><th scope="row" colspan="2" style="font-size:13px; font-weight:normal;"><?php echo $value['desc']; ?></th></tr>

<?php } elseif ($value['type'] == "checkbox") { ?>

	<tr valign="top"> 
		<th scope="row"><?php echo $value['name']; ?></th>
		<td>
			<?php
				if(get_option($value['id'])){
					$checked = "checked=\"checked\"";
				}else{
					$checked = "";
				}
			?>
			<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
			<label for="<?php echo $value['id']; ?>"><?php echo $value['desc']; ?></label>
		</td>
	</tr>

<?php
}
}
?>

</table>

<p class="submit">
<input class="button-primary" name="save" type="submit" value="Save changes" />    
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input class="button-primary" name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>

<?php
}

add_action('admin_menu', 'mytheme_add_admin'); ?>