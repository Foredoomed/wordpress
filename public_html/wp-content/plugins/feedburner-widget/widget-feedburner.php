<?php
/*
Plugin Name: FeedBurner Widget
Version: 1.2
Plugin URI: http://dichev.com/blog/webdesign/feedburner-widget-wordpress-plugin/
Description: Adds a sidebar widget to easy customize and display your <a href="http://feedburner.google.com">FeedBurner</a> subscribers stats RSS button.
Author: Alexander Dichev
Author URI: http://dichev.com/
*/

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_feedburner_init() {
	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') ) {
		return;
    }
	// This is the function that outputs our little FeedBurner chicklet.
	function widget_feedburner($args) {
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);
		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_feedburner');
		$title = $options['title'];
	    $supported_feedburner_widget_version = 2;
	    // FeedBurner Stats widget options
        $fb_brand = !empty($options['fb_brand'])?$options['fb_brand']:'http://feeds2.feedburner.com/';
        $fb_user = $options['fb_user'];
	    $fb_bg = $options['fb_bg'];
	    $fb_fg = $options['fb_fg'];
	    $fb_anim = $options['fb_anim'];
	    $url_params = $options['url_params'];
	    if ( $url_params == "" ) {
            $url_params = 'bg=' . substr($fb_bg,1) .'&amp;fg=' . substr($fb_fg,1) . '&amp;anim=' . $fb_anim;
	    }
		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.
        echo $before_widget;
		if (!empty($title)) {
            echo $before_title . $title . $after_title;  
        }
        echo "\n" . '<p><a href="'. $fb_brand . $fb_user . '"><img src="' . $fb_brand . '~fc/' . $fb_user . '?' . $url_params . '" height="26" width="88" style="border:0" alt="" /></a></p>' . "\n";
		echo $after_widget;
	}
	// This is the function that outputs the form to let the users edit
	// the widget's settings. It's an optional feature that users cry for.
	function widget_feedburner_control() {
		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_feedburner');
		if ( !is_array($options) )
			$options = array('title'=>'FeedBurner RSS',
                     'fb_brand'=>'http://feeds2.feedburner.com/',
					 'fb_user'=>'', 
					 'fb_bg'=>'#E3DACF',
					 'fb_fg'=>'#345797',
					 'fb_anim'=>'0',
					 'url_params'=>'');
		if ( $_POST['feedburner-stats-submit'] ) {
			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['title']));
            $options['fb_brand'] = strip_tags(stripslashes($_POST['fb_brand']));            
			$options['fb_user'] = strip_tags(stripslashes($_POST['fb_user']));			
			$options['fb_bg'] = strip_tags(stripslashes($_POST['fb_bodyColor']));
			$options['fb_fg'] = strip_tags(stripslashes($_POST['fb_textColor']));
			$options['fb_anim'] = strip_tags(stripslashes($_POST['fb_anim']));			
			update_option('widget_feedburner', $options);
		}
		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
        $fb_brand = htmlspecialchars($options['fb_brand'], ENT_QUOTES);
		$fb_user = htmlspecialchars($options['fb_user'], ENT_QUOTES);
		$fb_bg = htmlspecialchars($options['fb_bg'], ENT_QUOTES);
		$fb_fg = htmlspecialchars($options['fb_fg'], ENT_QUOTES);
		$fb_anim = htmlspecialchars($options['fb_anim'], ENT_QUOTES);		
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
?>
<div>
<style type="text/css">
div.csq { background-color:transparent; border:none; cursor:pointer; height:10px; width:10px; }
div#countPreviewDiv { background-color:<?php echo $fb_bg; ?>; border:solid 1px #444; color:<?php echo $fb_fg; ?>; font:11px Verdana, Arial, sans; text-align:center; height:16px; margin: 10px auto 4px; padding:3px; vertical-align:middle; width:82px; }
</style>
<script type="text/javascript">
function assignColor(theColor) {
	if (document.getElementById) {
		var circForm = document.forms['widget-controls'];
		var re6 = /#[0-9A-Za-z]{6}/;
		if (!re6.test(theColor)) {
			return;
		}
		bodyFld = circForm.fb_radioColor[0];
		txtFld = circForm.fb_radioColor[1];
		pv = document.getElementById("countPreviewDiv");
		if (bodyFld.checked) {		
			circForm.elements['fb_bodyColor'].value= theColor;
			pv.style.backgroundColor = theColor;
		} else {
			circForm.elements['fb_textColor'].value= theColor;
			pv.style.color = theColor;
		}
	}
}
</script>  

    <p><strong>Widget Title</strong></p>
    <p><label for="title">Title text (optional)</label><br />
    <input type="text" name="title" id="title" value="<?php echo $title; ?>" class="widefat" /></p>

    <p><strong>FeedBurner Feed Address</strong></p>
    <p><label for="fb_user">FeedBurner Username</label><br />
    <input type="text" name="fb_user" id="fb_user" value="<?php echo $fb_user; ?>" class="widefat" /><br />
    <small><em>(http://feeds2.feedburner.com/USERNAME)</em></small></p>
    <p><label for="fb_brand">MyBrand (if using this feature)</label><br />
    <input type="text" name="fb_brand" id="fb_brand" value="<?php echo $fb_brand; ?>" class="widefat" /><br />
    <small><em>(http://feeds.yourdomain.com/)</em></small></p>

    <p><strong>Chicklet Style</strong></p>
    <p><input type="radio" name="fb_anim" value="0"<?php echo (($fb_anim='1')?' checked="checked"':''); ?> id="fb_static" /> <label for="fb_static"><img src="http://www.feedburner.com/fb/i/services/feedcount_static.gif" alt="" /> Static</label></p>
    <p><input type="radio" name="fb_anim" value="1"<?php echo (($fb_anim='1')?'':' checked="checked"'); ?> id="fb_animated" /> <label for="fb_animated"><img src="http://www.feedburner.com/fb/i/services/feedcount_anim.gif" alt="" /> Animated</label></p>

	<p><strong>Color Scheme</h5>

        <div>
                 <table style="border:0; border-collapse:collapse; margin:0 0 12px 0; padding:0;">
  					
  						<tr>
  						
  							<td style="background-color:#000000;margin:0;padding:0"><a href="javascript:assignColor('#000000')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#000033;margin:0;padding:0"><a href="javascript:assignColor('#000033')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#000066;margin:0;padding:0"><a href="javascript:assignColor('#000066')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#000099;margin:0;padding:0"><a href="javascript:assignColor('#000099')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#0000CC;margin:0;padding:0"><a href="javascript:assignColor('#0000CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#0000FF;margin:0;padding:0"><a href="javascript:assignColor('#0000FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#006600;margin:0;padding:0"><a href="javascript:assignColor('#006600')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#006633;margin:0;padding:0"><a href="javascript:assignColor('#006633')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#006666;margin:0;padding:0"><a href="javascript:assignColor('#006666')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#006699;margin:0;padding:0"><a href="javascript:assignColor('#006699')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#0066CC;margin:0;padding:0"><a href="javascript:assignColor('#0066CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#0066FF;margin:0;padding:0"><a href="javascript:assignColor('#0066FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#00CC00;margin:0;padding:0"><a href="javascript:assignColor('#00CC00')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#00CC33;margin:0;padding:0"><a href="javascript:assignColor('#00CC33')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#00CC66;margin:0;padding:0"><a href="javascript:assignColor('#00CC66')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#00CC99;margin:0;padding:0"><a href="javascript:assignColor('#00CC99')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#00CCCC;margin:0;padding:0"><a href="javascript:assignColor('#00CCCC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#00CCFF;margin:0;padding:0"><a href="javascript:assignColor('#00CCFF')"><div class="csq"></div></a></td>
  						
  						</tr>											
  					
  						<tr>
  						
  							<td style="background-color:#003300;margin:0;padding:0"><a href="javascript:assignColor('#003300')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#003333;margin:0;padding:0"><a href="javascript:assignColor('#003333')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#003366;margin:0;padding:0"><a href="javascript:assignColor('#003366')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#003399;margin:0;padding:0"><a href="javascript:assignColor('#003399')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#0033CC;margin:0;padding:0"><a href="javascript:assignColor('#0033CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#0033FF;margin:0;padding:0"><a href="javascript:assignColor('#0033FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#009900;margin:0;padding:0"><a href="javascript:assignColor('#009900')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#009933;margin:0;padding:0"><a href="javascript:assignColor('#009933')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#009966;margin:0;padding:0"><a href="javascript:assignColor('#009966')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#009999;margin:0;padding:0"><a href="javascript:assignColor('#009999')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#0099CC;margin:0;padding:0"><a href="javascript:assignColor('#0099CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#0099FF;margin:0;padding:0"><a href="javascript:assignColor('#0099FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#00FF00;margin:0;padding:0"><a href="javascript:assignColor('#00FF00')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#00FF33;margin:0;padding:0"><a href="javascript:assignColor('#00FF33')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#00FF66;margin:0;padding:0"><a href="javascript:assignColor('#00FF66')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#00FF99;margin:0;padding:0"><a href="javascript:assignColor('#00FF99')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#00FFCC;margin:0;padding:0"><a href="javascript:assignColor('#00FFCC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#00FFFF;margin:0;padding:0"><a href="javascript:assignColor('#00FFFF')"><div class="csq"></div></a></td>
  						
  						</tr>											
  					
  						<tr>
  						
  							<td style="background-color:#330000;margin:0;padding:0"><a href="javascript:assignColor('#330000')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#330033;margin:0;padding:0"><a href="javascript:assignColor('#330033')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#330066;margin:0;padding:0"><a href="javascript:assignColor('#330066')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#330099;margin:0;padding:0"><a href="javascript:assignColor('#330099')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#3300CC;margin:0;padding:0"><a href="javascript:assignColor('#3300CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#3300FF;margin:0;padding:0"><a href="javascript:assignColor('#3300FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#336600;margin:0;padding:0"><a href="javascript:assignColor('#336600')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#336633;margin:0;padding:0"><a href="javascript:assignColor('#336633')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#336666;margin:0;padding:0"><a href="javascript:assignColor('#336666')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#336699;margin:0;padding:0"><a href="javascript:assignColor('#336699')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#3366CC;margin:0;padding:0"><a href="javascript:assignColor('#3366CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#3366FF;margin:0;padding:0"><a href="javascript:assignColor('#3366FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#33CC00;margin:0;padding:0"><a href="javascript:assignColor('#33CC00')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#33CC33;margin:0;padding:0"><a href="javascript:assignColor('#33CC33')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#33CC66;margin:0;padding:0"><a href="javascript:assignColor('#33CC66')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#33CC99;margin:0;padding:0"><a href="javascript:assignColor('#33CC99')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#33CCCC;margin:0;padding:0"><a href="javascript:assignColor('#33CCCC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#33CCFF;margin:0;padding:0"><a href="javascript:assignColor('#33CCFF')"><div class="csq"></div></a></td>
  						
  						</tr>											
  					
  						<tr>
  						
  							<td style="background-color:#333300;margin:0;padding:0"><a href="javascript:assignColor('#333300')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#333333;margin:0;padding:0"><a href="javascript:assignColor('#333333')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#333366;margin:0;padding:0"><a href="javascript:assignColor('#333366')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#333399;margin:0;padding:0"><a href="javascript:assignColor('#333399')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#3333CC;margin:0;padding:0"><a href="javascript:assignColor('#3333CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#3333FF;margin:0;padding:0"><a href="javascript:assignColor('#3333FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#339900;margin:0;padding:0"><a href="javascript:assignColor('#339900')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#339933;margin:0;padding:0"><a href="javascript:assignColor('#339933')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#339966;margin:0;padding:0"><a href="javascript:assignColor('#339966')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#339999;margin:0;padding:0"><a href="javascript:assignColor('#339999')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#3399CC;margin:0;padding:0"><a href="javascript:assignColor('#3399CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#3399FF;margin:0;padding:0"><a href="javascript:assignColor('#3399FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#33FF00;margin:0;padding:0"><a href="javascript:assignColor('#33FF00')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#33FF33;margin:0;padding:0"><a href="javascript:assignColor('#33FF33')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#33FF66;margin:0;padding:0"><a href="javascript:assignColor('#33FF66')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#33FF99;margin:0;padding:0"><a href="javascript:assignColor('#33FF99')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#33FFCC;margin:0;padding:0"><a href="javascript:assignColor('#33FFCC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#33FFFF;margin:0;padding:0"><a href="javascript:assignColor('#33FFFF')"><div class="csq"></div></a></td>
  						
  						</tr>											
  					
  						<tr>
  						
  							<td style="background-color:#660000;margin:0;padding:0"><a href="javascript:assignColor('#660000')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#660033;margin:0;padding:0"><a href="javascript:assignColor('#660033')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#660066;margin:0;padding:0"><a href="javascript:assignColor('#660066')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#660099;margin:0;padding:0"><a href="javascript:assignColor('#660099')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#6600CC;margin:0;padding:0"><a href="javascript:assignColor('#6600CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#6600FF;margin:0;padding:0"><a href="javascript:assignColor('#6600FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#666600;margin:0;padding:0"><a href="javascript:assignColor('#666600')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#666633;margin:0;padding:0"><a href="javascript:assignColor('#666633')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#666666;margin:0;padding:0"><a href="javascript:assignColor('#666666')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#666699;margin:0;padding:0"><a href="javascript:assignColor('#666699')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#6666CC;margin:0;padding:0"><a href="javascript:assignColor('#6666CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#6666FF;margin:0;padding:0"><a href="javascript:assignColor('#6666FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#66CC00;margin:0;padding:0"><a href="javascript:assignColor('#66CC00')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#66CC33;margin:0;padding:0"><a href="javascript:assignColor('#66CC33')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#66CC66;margin:0;padding:0"><a href="javascript:assignColor('#66CC66')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#66CC99;margin:0;padding:0"><a href="javascript:assignColor('#66CC99')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#66CCCC;margin:0;padding:0"><a href="javascript:assignColor('#66CCCC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#66CCFF;margin:0;padding:0"><a href="javascript:assignColor('#66CCFF')"><div class="csq"></div></a></td>
  						
  						</tr>											
  					
  						<tr>
  						
  							<td style="background-color:#663300;margin:0;padding:0"><a href="javascript:assignColor('#663300')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#663333;margin:0;padding:0"><a href="javascript:assignColor('#663333')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#663366;margin:0;padding:0"><a href="javascript:assignColor('#663366')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#663399;margin:0;padding:0"><a href="javascript:assignColor('#663399')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#6633CC;margin:0;padding:0"><a href="javascript:assignColor('#6633CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#6633FF;margin:0;padding:0"><a href="javascript:assignColor('#6633FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#669900;margin:0;padding:0"><a href="javascript:assignColor('#669900')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#669933;margin:0;padding:0"><a href="javascript:assignColor('#669933')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#669966;margin:0;padding:0"><a href="javascript:assignColor('#669966')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#669999;margin:0;padding:0"><a href="javascript:assignColor('#669999')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#6699CC;margin:0;padding:0"><a href="javascript:assignColor('#6699CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#6699FF;margin:0;padding:0"><a href="javascript:assignColor('#6699FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#66FF00;margin:0;padding:0"><a href="javascript:assignColor('#66FF00')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#66FF33;margin:0;padding:0"><a href="javascript:assignColor('#66FF33')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#66FF66;margin:0;padding:0"><a href="javascript:assignColor('#66FF66')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#66FF99;margin:0;padding:0"><a href="javascript:assignColor('#66FF99')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#66FFCC;margin:0;padding:0"><a href="javascript:assignColor('#66FFCC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#66FFFF;margin:0;padding:0"><a href="javascript:assignColor('#66FFFF')"><div class="csq"></div></a></td>
  						
  						</tr>											
  					
  						<tr>
  						
  							<td style="background-color:#990000;margin:0;padding:0"><a href="javascript:assignColor('#990000')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#990033;margin:0;padding:0"><a href="javascript:assignColor('#990033')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#990066;margin:0;padding:0"><a href="javascript:assignColor('#990066')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#990099;margin:0;padding:0"><a href="javascript:assignColor('#990099')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#9900CC;margin:0;padding:0"><a href="javascript:assignColor('#9900CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#9900FF;margin:0;padding:0"><a href="javascript:assignColor('#9900FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#996600;margin:0;padding:0"><a href="javascript:assignColor('#996600')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#996633;margin:0;padding:0"><a href="javascript:assignColor('#996633')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#996666;margin:0;padding:0"><a href="javascript:assignColor('#996666')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#996699;margin:0;padding:0"><a href="javascript:assignColor('#996699')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#9966CC;margin:0;padding:0"><a href="javascript:assignColor('#9966CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#9966FF;margin:0;padding:0"><a href="javascript:assignColor('#9966FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#99CC00;margin:0;padding:0"><a href="javascript:assignColor('#99CC00')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#99CC33;margin:0;padding:0"><a href="javascript:assignColor('#99CC33')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#99CC66;margin:0;padding:0"><a href="javascript:assignColor('#99CC66')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#99CC99;margin:0;padding:0"><a href="javascript:assignColor('#99CC99')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#99CCCC;margin:0;padding:0"><a href="javascript:assignColor('#99CCCC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#99CCFF;margin:0;padding:0"><a href="javascript:assignColor('#99CCFF')"><div class="csq"></div></a></td>
  						
  						</tr>											
  					
  						<tr>
  						
  							<td style="background-color:#993300;margin:0;padding:0"><a href="javascript:assignColor('#993300')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#993333;margin:0;padding:0"><a href="javascript:assignColor('#993333')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#993366;margin:0;padding:0"><a href="javascript:assignColor('#993366')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#993399;margin:0;padding:0"><a href="javascript:assignColor('#993399')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#9933CC;margin:0;padding:0"><a href="javascript:assignColor('#9933CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#9933FF;margin:0;padding:0"><a href="javascript:assignColor('#9933FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#999900;margin:0;padding:0"><a href="javascript:assignColor('#999900')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#999933;margin:0;padding:0"><a href="javascript:assignColor('#999933')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#999966;margin:0;padding:0"><a href="javascript:assignColor('#999966')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#999999;margin:0;padding:0"><a href="javascript:assignColor('#999999')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#9999CC;margin:0;padding:0"><a href="javascript:assignColor('#9999CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#9999FF;margin:0;padding:0"><a href="javascript:assignColor('#9999FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#99FF00;margin:0;padding:0"><a href="javascript:assignColor('#99FF00')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#99FF33;margin:0;padding:0"><a href="javascript:assignColor('#99FF33')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#99FF66;margin:0;padding:0"><a href="javascript:assignColor('#99FF66')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#99FF99;margin:0;padding:0"><a href="javascript:assignColor('#99FF99')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#99FFCC;margin:0;padding:0"><a href="javascript:assignColor('#99FFCC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#99FFFF;margin:0;padding:0"><a href="javascript:assignColor('#99FFFF')"><div class="csq"></div></a></td>
  						
  						</tr>											
  					
  						<tr>
  						
  							<td style="background-color:#CC0000;margin:0;padding:0"><a href="javascript:assignColor('#CC0000')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC0033;margin:0;padding:0"><a href="javascript:assignColor('#CC0033')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC0066;margin:0;padding:0"><a href="javascript:assignColor('#CC0066')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC0099;margin:0;padding:0"><a href="javascript:assignColor('#CC0099')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC00CC;margin:0;padding:0"><a href="javascript:assignColor('#CC00CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC00FF;margin:0;padding:0"><a href="javascript:assignColor('#CC00FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC6600;margin:0;padding:0"><a href="javascript:assignColor('#CC6600')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC6633;margin:0;padding:0"><a href="javascript:assignColor('#CC6633')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC6666;margin:0;padding:0"><a href="javascript:assignColor('#CC6666')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC6699;margin:0;padding:0"><a href="javascript:assignColor('#CC6699')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC66CC;margin:0;padding:0"><a href="javascript:assignColor('#CC66CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC66FF;margin:0;padding:0"><a href="javascript:assignColor('#CC66FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CCCC00;margin:0;padding:0"><a href="javascript:assignColor('#CCCC00')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CCCC33;margin:0;padding:0"><a href="javascript:assignColor('#CCCC33')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CCCC66;margin:0;padding:0"><a href="javascript:assignColor('#CCCC66')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CCCC99;margin:0;padding:0"><a href="javascript:assignColor('#CCCC99')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CCCCCC;margin:0;padding:0"><a href="javascript:assignColor('#CCCCCC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CCCCFF;margin:0;padding:0"><a href="javascript:assignColor('#CCCCFF')"><div class="csq"></div></a></td>
  						
  						</tr>											
  					
  						<tr>
  						
  							<td style="background-color:#CC3300;margin:0;padding:0"><a href="javascript:assignColor('#CC3300')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC3333;margin:0;padding:0"><a href="javascript:assignColor('#CC3333')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC3366;margin:0;padding:0"><a href="javascript:assignColor('#CC3366')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC3399;margin:0;padding:0"><a href="javascript:assignColor('#CC3399')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC33CC;margin:0;padding:0"><a href="javascript:assignColor('#CC33CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC33FF;margin:0;padding:0"><a href="javascript:assignColor('#CC33FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC9900;margin:0;padding:0"><a href="javascript:assignColor('#CC9900')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC9933;margin:0;padding:0"><a href="javascript:assignColor('#CC9933')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC9966;margin:0;padding:0"><a href="javascript:assignColor('#CC9966')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC9999;margin:0;padding:0"><a href="javascript:assignColor('#CC9999')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC99CC;margin:0;padding:0"><a href="javascript:assignColor('#CC99CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CC99FF;margin:0;padding:0"><a href="javascript:assignColor('#CC99FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CCFF00;margin:0;padding:0"><a href="javascript:assignColor('#CCFF00')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CCFF33;margin:0;padding:0"><a href="javascript:assignColor('#CCFF33')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CCFF66;margin:0;padding:0"><a href="javascript:assignColor('#CCFF66')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CCFF99;margin:0;padding:0"><a href="javascript:assignColor('#CCFF99')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CCFFCC;margin:0;padding:0"><a href="javascript:assignColor('#CCFFCC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#CCFFFF;margin:0;padding:0"><a href="javascript:assignColor('#CCFFFF')"><div class="csq"></div></a></td>
  						
  						</tr>											
  					
  						<tr>
  						
  							<td style="background-color:#FF0000;margin:0;padding:0"><a href="javascript:assignColor('#FF0000')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF0033;margin:0;padding:0"><a href="javascript:assignColor('#FF0033')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF0066;margin:0;padding:0"><a href="javascript:assignColor('#FF0066')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF0099;margin:0;padding:0"><a href="javascript:assignColor('#FF0099')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF00CC;margin:0;padding:0"><a href="javascript:assignColor('#FF00CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF00FF;margin:0;padding:0"><a href="javascript:assignColor('#FF00FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF6600;margin:0;padding:0"><a href="javascript:assignColor('#FF6600')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF6633;margin:0;padding:0"><a href="javascript:assignColor('#FF6633')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF6666;margin:0;padding:0"><a href="javascript:assignColor('#FF6666')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF6699;margin:0;padding:0"><a href="javascript:assignColor('#FF6699')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF66CC;margin:0;padding:0"><a href="javascript:assignColor('#FF66CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF66FF;margin:0;padding:0"><a href="javascript:assignColor('#FF66FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FFCC00;margin:0;padding:0"><a href="javascript:assignColor('#FFCC00')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FFCC33;margin:0;padding:0"><a href="javascript:assignColor('#FFCC33')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FFCC66;margin:0;padding:0"><a href="javascript:assignColor('#FFCC66')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FFCC99;margin:0;padding:0"><a href="javascript:assignColor('#FFCC99')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FFCCCC;margin:0;padding:0"><a href="javascript:assignColor('#FFCCCC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FFCCFF;margin:0;padding:0"><a href="javascript:assignColor('#FFCCFF')"><div class="csq"></div></a></td>
  						
  						</tr>											
  					
  						<tr>
  						
  							<td style="background-color:#FF3300;margin:0;padding:0"><a href="javascript:assignColor('#FF3300')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF3333;margin:0;padding:0"><a href="javascript:assignColor('#FF3333')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF3366;margin:0;padding:0"><a href="javascript:assignColor('#FF3366')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF3399;margin:0;padding:0"><a href="javascript:assignColor('#FF3399')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF33CC;margin:0;padding:0"><a href="javascript:assignColor('#FF33CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF33FF;margin:0;padding:0"><a href="javascript:assignColor('#FF33FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF9900;margin:0;padding:0"><a href="javascript:assignColor('#FF9900')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF9933;margin:0;padding:0"><a href="javascript:assignColor('#FF9933')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF9966;margin:0;padding:0"><a href="javascript:assignColor('#FF9966')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF9999;margin:0;padding:0"><a href="javascript:assignColor('#FF9999')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF99CC;margin:0;padding:0"><a href="javascript:assignColor('#FF99CC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FF99FF;margin:0;padding:0"><a href="javascript:assignColor('#FF99FF')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FFFF00;margin:0;padding:0"><a href="javascript:assignColor('#FFFF00')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FFFF33;margin:0;padding:0"><a href="javascript:assignColor('#FFFF33')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FFFF66;margin:0;padding:0"><a href="javascript:assignColor('#FFFF66')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FFFF99;margin:0;padding:0"><a href="javascript:assignColor('#FFFF99')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FFFFCC;margin:0;padding:0"><a href="javascript:assignColor('#FFFFCC')"><div class="csq"></div></a></td>
  						
  							<td style="background-color:#FFFFFF;margin:0;padding:0"><a href="javascript:assignColor('#FFFFFF')"><div class="csq"></div></a></td>
  						
  						</tr>											
  					
  					</table>

               </div>
                    
          <p><input type="radio" name="fb_radioColor" value="body" id="fb_radioColor1" checked="checked" /> <label for="fb_radioColor1"><input type="text" name="fb_bodyColor" maxlength="7" value="<?php echo $fb_bg; ?>" onkeyup="document.forms['widget-controls'].fb_radioColor[0].checked=true;assignColor(this.value)" style="width:80px;" /> Body</label></p>
          <p><input type="radio" name="fb_radioColor" value="text" id="fb_radioColor2" /> <label for="fb_radioColor2"><input type="text" name="fb_textColor" maxlength="7" value="<?php echo $fb_fg; ?>" onkeyup="document.forms['widget-controls'].fb_radioColor[1].checked=true;assignColor(this.value)" style="width:80px;" /> Text</label></p>

    <p><strong>FeedBurner Button Settings Preview</strong></p>
    
    <div id="countPreviewDiv">text</div>
  
    <input type="hidden" id="feedburner-stats-submit" name="feedburner-stats-submit" value="1" />
  
</div>

<?php
	}
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget('FeedBurner Stats', 'widget_feedburner');
	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control('FeedBurner Stats', 'widget_feedburner_control', 250, 470);
}
// Run our code later in case this loads prior to any required plugins.
add_action('plugins_loaded', 'widget_feedburner_init');
?>