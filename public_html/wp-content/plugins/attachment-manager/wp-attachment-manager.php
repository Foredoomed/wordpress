<?php
/**
 * Plugin Name: Attachment Manager
 * Plugin URI: http://xavisys.com/wordpress-plugins/attachment-manager/
 * Description: Create a list of attachments with icons for each post.
 * Version: 2.1.1
 * Author: Aaron D. Campbell
 * Author URI: http://xavisys.com/
 * Text Domain: attachment-manager
 */

/**
 * @todo Consolidate settings into an array
 * @todo put icons into a wp-content/uploads subdir
 */

/**
 * wpAttachmentManager is the class that handles ALL of the plugin functionality.
 * It helps us avoid name collisions
 * http://codex.wordpress.org/Writing_a_Plugin#Avoiding_Function_Name_Collisions
 */
require_once('xavisys-plugin-framework.php');
class wpAttachmentManager extends XavisysPlugin {
	/**
	 * @var wpAttachmentManager - Static property to hold our singleton instance
	 */
	static $instance = false;

	/**
	 * @var string Icons directory
	 */
	private $_icon_dir;

	/**
	 * @var bool If icons dir is writable
	 */
	private $_icon_dir_is_writable;

	/**
	 * @var bool If the excerpt filter is run, we set this to true so we know
	 * 	it's an excerpt
	 */
	private $_is_excerpt;

	protected function _init() {
		$this->_hook = 'attachment_manager';
		$this->_file = plugin_basename( __FILE__ );
		$this->_pageTitle = __( 'Attachment Manager Options', $this->_slug );
		$this->_menuTitle = __( 'Attachment Manager', $this->_slug );
		$this->_accessLevel = 'manage_options';
		$this->_optionGroup = 'wam-options';
		$this->_optionNames = array(
			'wam',
			'wam_icon_dir',
			'icon_file_types',
			'wam_list_on_posts',
			'wam_dont_show_files_already_linked',
			'wam_dont_show_on_excerpts',
			'wam_dont_show_on_cat_page',
			'wam_show_file_icons',
			'wam_default_icon',
			'icons'
		);
		$this->_optionCallbacks = array();
		$this->_slug = 'attachment-manager';
		$this->_paypalButtonId = '11106634';

		/**
		 * Add filters and actions
		 */
		//We have to set the priority of the excerpt check to one, or it will run AFTER the post handler
		add_filter('get_the_excerpt', array($this,'check_excerpt'), 1);
		add_filter('the_content', array($this,'attach_to_post'));
		add_action('init', array($this,'handle_actions'));
		add_action('save_post', array($this,'handle_save_post'));
		register_activation_hook(__FILE__, array($this,'on_activate'));
	}

	private function _get_icon_url() {
		$siteurl = rtrim( get_option( 'siteurl' ), '/' );
		return str_replace(rtrim(ABSPATH, '/'), $siteurl, $this->_settings['wam_icon_dir']);
	}

	public function _postSettingsInit() {
		if ( empty($this->_settings['icons']) || !is_array( $this->_settings['icons'] )) {
			$this->_settings['icons'] = unserialize('a:99:{s:3:"css";s:7:"css.png";s:3:"eml";s:9:"email.png";s:3:"rss";s:8:"feed.png";s:1:"h";s:16:"page_white_h.png";s:3:"avi";s:8:"film.png";s:3:"mov";s:8:"film.png";s:3:"mp4";s:8:"film.png";s:3:"mpg";s:8:"film.png";s:2:"qt";s:8:"film.png";s:2:"rm";s:8:"film.png";s:3:"wmv";s:8:"film.png";s:3:"chm";s:8:"help.png";s:3:"mdb";s:18:"page_white_key.png";s:3:"htm";s:8:"html.png";s:4:"html";s:8:"html.png";s:3:"sht";s:8:"html.png";s:4:"shtm";s:8:"html.png";s:5:"shtml";s:8:"html.png";s:3:"aac";s:9:"music.png";s:3:"aif";s:9:"music.png";s:3:"mid";s:9:"music.png";s:4:"midi";s:9:"music.png";s:3:"mp3";s:9:"music.png";s:3:"mpa";s:9:"music.png";s:2:"ra";s:9:"music.png";s:3:"ram";s:9:"music.png";s:3:"wav";s:9:"music.png";s:3:"wma";s:9:"music.png";s:4:"flac";s:9:"music.png";s:3:"ogg";s:9:"music.png";s:3:"pdf";s:22:"page_white_acrobat.png";s:2:"as";s:27:"page_white_actionscript.png";s:1:"c";s:16:"page_white_c.png";s:3:"raw";s:21:"page_white_camera.png";s:3:"inc";s:18:"page_white_php.png";s:3:"php";s:18:"page_white_php.png";s:4:"php4";s:18:"page_white_php.png";s:4:"php5";s:18:"page_white_php.png";s:4:"phps";s:18:"page_white_php.png";s:5:"phtml";s:18:"page_white_php.png";s:3:"tpl";s:18:"page_white_php.png";s:3:"bmp";s:22:"page_white_picture.png";s:3:"gif";s:22:"page_white_picture.png";s:4:"jpeg";s:22:"page_white_picture.png";s:3:"jpg";s:22:"page_white_picture.png";s:3:"png";s:22:"page_white_picture.png";s:3:"psd";s:22:"page_white_picture.png";s:2:"js";s:23:"page_white_code_red.png";s:3:"ppt";s:25:"page_white_powerpoint.png";s:3:"cfm";s:25:"page_white_coldfusion.png";s:4:"cfml";s:25:"page_white_coldfusion.png";s:2:"bz";s:25:"page_white_compressed.png";s:3:"bz2";s:25:"page_white_compressed.png";s:3:"cab";s:25:"page_white_compressed.png";s:4:"gtar";s:25:"page_white_compressed.png";s:2:"gz";s:25:"page_white_compressed.png";s:4:"gzip";s:25:"page_white_compressed.png";s:3:"rar";s:25:"page_white_compressed.png";s:3:"tar";s:25:"page_white_compressed.png";s:6:"tar-gz";s:25:"page_white_compressed.png";s:3:"tgz";s:25:"page_white_compressed.png";s:3:"war";s:25:"page_white_compressed.png";s:3:"zip";s:25:"page_white_compressed.png";s:2:"rb";s:19:"page_white_ruby.png";s:3:"rbs";s:19:"page_white_ruby.png";s:5:"rhtml";s:19:"page_white_ruby.png";s:3:"cpp";s:24:"page_white_cplusplus.png";s:2:"cs";s:21:"page_white_csharp.png";s:5:"class";s:18:"page_white_cup.png";s:3:"jad";s:18:"page_white_cup.png";s:3:"jar";s:18:"page_white_cup.png";s:3:"jav";s:18:"page_white_cup.png";s:4:"java";s:18:"page_white_cup.png";s:3:"rdf";s:19:"page_white_text.png";s:3:"txt";s:19:"page_white_text.png";s:3:"sql";s:23:"page_white_database.png";s:4:"conf";s:18:"page_white_tux.png";s:2:"ai";s:21:"page_white_vector.png";s:3:"svg";s:21:"page_white_vector.png";s:3:"xls";s:20:"page_white_excel.png";s:3:"doc";s:19:"page_white_word.png";s:3:"fla";s:20:"page_white_flash.png";s:3:"swf";s:20:"page_white_flash.png";s:2:"fh";s:23:"page_white_freehand.png";s:4:"fh10";s:23:"page_white_freehand.png";s:3:"fh3";s:23:"page_white_freehand.png";s:3:"fh4";s:23:"page_white_freehand.png";s:3:"fh5";s:23:"page_white_freehand.png";s:3:"fh6";s:23:"page_white_freehand.png";s:3:"fh7";s:23:"page_white_freehand.png";s:3:"fh8";s:23:"page_white_freehand.png";s:3:"fh9";s:23:"page_white_freehand.png";s:3:"dtd";s:19:"page_white_gear.png";s:3:"tld";s:19:"page_white_gear.png";s:4:"wsdl";s:19:"page_white_gear.png";s:3:"xml";s:19:"page_white_gear.png";s:3:"xsd";s:19:"page_white_gear.png";s:3:"xsl";s:19:"page_white_gear.png";s:5:"xhtml";s:9:"xhtml.png";}');
			update_option('icons', $this->_settings['icons']);
		}

		if ( empty($this->_settings['wam_icon_dir']) ) {
			$this->_settings['wam_icon_dir'] = $this->_icon_dir = plugin_dir_path(__FILE__) . 'icons';
		} else {
			$this->_icon_dir = $this->_settings['wam_icon_dir'];
		}

		/**
		 * Default settings for the wam array
		 */
		$default_settings = array(
			'home_page'   => 'hide',
			'show_on_rss' => ''
		);

		$this->_settings['wam'] = wp_parse_args( $this->_settings['wam'], $default_settings );

		$this->_icon_dir_is_writable = is_writable($this->_icon_dir);

		if (isset($_GET['remove'])) {
			switch ($_GET['remove']) {
				case 'true':
					$message = __('Icon <strong>removed</strong>.', $this->_slug);
					$class = 'updated';
					break;
				default:
					$message = __('Problem removing icon.', $this->_slug);
					$class = 'error';
			}
			$notice = str_replace( "'", "\'", "<div class='{$class}'><p>{$message}</p></div>" );
			add_action('admin_notices', create_function( '', "echo '$notice';" ) );
		}
		if (isset($_GET['upload'])) {
			switch ($_GET['upload']) {
				case 'true':
					$message = __('Icon <strong>uploaded</strong>.', $this->_slug);
					$class = 'updated';
					break;
				default:
					$message = __('Problem uploading icon.', $this->_slug);
					$class = 'error';
			}
			$notice = str_replace( "'", "\'", "<div class='{$class}'><p>{$message}</p></div>" );
			add_action('admin_notices', create_function( '', "echo '$notice';" ) );
		}

		if ($this->_settings['wam_list_on_posts'] == 'some') {
			add_action('admin_menu', array($this, 'adminMenu'));
		}
	}

	/**
	 * Function to instantiate our class and make it a singleton
	 */
	public static function getInstance() {
		if ( !self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function addOptionsMetaBoxes() {
		add_meta_box( $this->_slug . '-general-settings', __('General Settings', $this->_slug), array($this, 'generalSettingsMetaBox'), 'xavisys-' . $this->_slug, 'main');
		add_meta_box( $this->_slug . '-icons', __('Icons', $this->_slug), array($this, 'iconsMetaBox'), 'xavisys-' . $this->_slug, 'main');
		if ($this->_icon_dir_is_writable) {
			add_meta_box( $this->_slug . '-upload-icons', __('Upload New Icon', $this->_slug), array($this, 'uploadIconPostsMetaBox'), 'xavisys-' . $this->_slug, 'main-2');
		}
	}

	public function generalSettingsMetaBox() {
		?>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="wam_icon_dir"><?php _e('Icons Directory:', $this->_slug); ?></label>
							</th>
							<td>
								<input type="text" id="wam_icon_dir" name="wam_icon_dir" value="<?php echo attribute_escape($this->_settings['wam_icon_dir']); ?>" style="width:95%;" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="icon_file_types"><?php _e('Allowed Icon File Extensions <small>(Comma Seperated)</small>:', $this->_slug); ?></label>
							</th>
							<td>
								<input type="text" id="icon_file_types" name="icon_file_types" value="<?php echo attribute_escape($this->_settings['icon_file_types']); ?>" style="width:95%;" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<?php _e('Show file lists on:', $this->_slug); ?>
							</th>
							<td>
								<input type="radio" name="wam_list_on_posts" value="all" id="wam_list_on_all_posts"<?php checked('all', $this->_settings['wam_list_on_posts']); ?> />
								<label for="wam_list_on_all_posts"><?php _e('All posts', $this->_slug); ?></label><br />
								<input type="radio" name="wam_list_on_posts" value="some" id="wam_list_on_some_posts"<?php checked('some', $this->_settings['wam_list_on_posts']); ?> />
								<label for="wam_list_on_some_posts"><?php _e('Some posts', $this->_slug); ?></label><br />
								<input type="radio" name="wam_list_on_posts" value="none" id="wam_list_on_no_posts"<?php checked('none', $this->_settings['wam_list_on_posts']); ?> />
								<label for="wam_list_on_no_posts"><?php _e('No posts', $this->_slug); ?></label><br />
								<br />
								<input type="radio" name="wam[home_page]" value="show" id="wam_show_on_home_page"<?php checked( 'show', $this->_settings['wam']['home_page'] ); ?> />
								<label for="wam_show_on_home_page"><?php _e("Show on home page", $this->_slug); ?></label><br />
								<input type="radio" name="wam[home_page]" value="hide" id="wam_hide_on_home_page"<?php checked( 'hide', $this->_settings['wam']['home_page'] ); ?> />
								<label for="wam_hide_on_home_page"><?php _e("Hide on home page", $this->_slug); ?></label><br />
								<br />
								<input type="checkbox" name="wam[show_on_rss]" value="true" id="wam_show_on_rss"<?php checked('true', $this->_settings['wam']['show_on_rss']); ?> />
								<label for="wam_show_on_rss"><?php _e("Show files on RSS feeds", $this->_slug); ?></label><br />
								<br />
								<input type="checkbox" name="wam_dont_show_files_already_linked" value="true" id="wam_dont_show_files_already_linked"<?php checked('true', $this->_settings['wam_dont_show_files_already_linked']); ?> />
								<label for="wam_dont_show_files_already_linked"><?php _e("Don't show files that are already linked in the post", $this->_slug); ?></label><br />
								<input type="checkbox" name="wam_dont_show_on_excerpts" value="true" id="wam_dont_show_on_excerpts"<?php checked('true', $this->_settings['wam_dont_show_on_excerpts']); ?> />
								<label for="wam_dont_show_on_excerpts"><?php _e("Don't show files on excerpts", $this->_slug); ?></label><br />
								<input type="checkbox" name="wam_dont_show_on_cat_page" value="true" id="wam_dont_show_on_cat_page"<?php checked('true', $this->_settings['wam_dont_show_on_cat_page']); ?> />
								<label for="wam_dont_show_on_cat_page"><?php _e("Don't show files on category pages", $this->_slug); ?></label><br />
								<input type="checkbox" name="wam_show_file_icons" value="true" id="wam_show_file_icons"<?php checked('true', $this->_settings['wam_show_file_icons']); ?> onclick="check_show_icons();" />
								<label for="wam_show_file_icons"><?php _e('Show icons for files', $this->_slug); ?><br /></label>
								<small><?php echo sprintf(__('The default icon set is <a href="%s">"Silk" by famfamfam</a>', $this->_slug), 'http://famfamfam.com/lab/icons/silk/'); ?></small><br />
							</td>
						</tr>
					</tbody>
				</table>
		<?php
	}

	public function iconsMetaBox() {
		$icon_file_types = $this->_get_icon_filetypes();
		$icon_files = array();
		$icon_file_types_arr = preg_split('/\s*,\s*/', $icon_file_types);
		$h = opendir($this->_icon_dir);
		while (($filename = readdir($h)) !== false) {
			if (in_array($this->_get_extension($filename), $icon_file_types_arr)) {
				$icon_files[] = $filename;
			}
		}
		sort($icon_files);
		$icons[0] = array_slice($icon_files, 0, ceil(count($icon_files)/2));
		$icons[1] = array_slice($icon_files, ceil(count($icon_files)/2));
?>
				<table class="form-table">
					<thead>
						<tr>
							<th class="wam_icon_td"><?php _e('Icon', $this->_slug); ?></th>
							<th class="wam_settings_td"><?php _e('Options', $this->_slug); ?></th>
							<th><?php _e('Associated File Types (comma seperated)', $this->_slug); ?></th>
							<th>&nbsp;</th>
							<th class="wam_icon_td"><?php _e('Icon', $this->_slug); ?></th>
							<th class="wam_settings_td"><?php _e('Options', $this->_slug); ?></th>
							<th><?php _e('Associated File Types (comma seperated)', $this->_slug); ?></th>
						</tr>
					</thead>
					<tbody>
<?php
for ($i=0; $i<count($icons[0]); $i++) {
	echo "<tr>";

	$this->_get_icon_cells($icons[0][$i], true);
	if (isset($icons[1][$i])) {
		$this->_get_icon_cells($icons[1][$i]);
	}
	echo "</tr>";
}
$default = (get_option('wam_default_icon') == '')?' checked="checked"':'';
?>
						<tr>
							<td colspan="7">
								<input type='radio' name='wam_default_icon' id='wam_default_icon_none' value=''<?php echo $default; ?> />
								<label for="wam_default_icon_none"><?php _e('No Default', $this->_slug); ?></label>
							</td>
						</tr>
					</tbody>
				</table>
<?php
	}

	private function _get_icon_cells( $icon = null, $add_blank_cell = false ) {
		if ( isset($icon) ) {
			$ta_name = preg_replace('/[^\w-]/', '', $icon);

			$img_src = $this->_get_icon_url() . '/' . $icon;
			$img_size = getimagesize($this->_icon_dir.'/'.$icon);
			$img_alt = htmlentities($icon);
			$img_alt = htmlentities($icon);
			$extensions = preg_split( '/\s*,\s*/', $this->_settings['icons'][$ta_name]['exts'] );
			sort($extensions);
			$ta_content = implode(',', $extensions);
			$default = (get_option('wam_default_icon') == $icon)?' checked="checked"':'';
			echo "<td><img src='{$img_src}' {$img_size[3]} alt='{$img_alt}' title='{$img_alt}' /></td>";
			echo "<td>";
			echo "<input type='radio' name='wam_default_icon' id='wam_default_icon_{$ta_name}' value='{$icon}'{$default} />";
			echo "<label for='wam_default_icon_{$ta_name}'>" . __('Default', $this->_slug) . '</label><br />';
			if ($this->_icon_dir_is_writable) {
				echo "<a href='options-general.php?page=attachment_manager&amp;action=remove&amp;icon=".urlencode($icon)."' title='".__('Remove this icon', $this->_slug)."' class='delete'>".__('Remove', $this->_slug)."</a>";
			}
			echo "</td>";
			echo "<td><textarea name='icons[{$ta_name}][exts]' rows='2' cols='20'>{$ta_content}</textarea><input type='hidden' value='{$icon}' name='icons[{$ta_name}][icon]' /></td>";
			if ( $add_blank_cell ) {
				echo "<td>&nbsp;</td>";
			}
		} else {
			$num_cells = ($add_blank_cell)? 4:3;
			echo str_repeat("<td>&nbsp;</td>", $num_cells);
		}
	}

	public function uploadIconPostsMetaBox() {
		?>
		<form enctype="multipart/form-data" action="" method="post" name="attachment_manager_upload" id="attachment_manager_upload">
			<fieldset class="profile">
				<label for="wam_add_icon"><?php _e('Select Icon File', $this->_slug); ?></label>
				<input type="file" name="wam_add_icon" id="wam_add_icon" />
				<p class="submit">
					<input type="submit" name="am_upload_icon" value="<?php _e('Upload Icon', $this->_slug);?>" />
				</p>
			</fieldset>
		</form>
		<?php
	}

	/**
	 * This is called if a post is in excerpt.  It sets $this->_is_static to
	 * true, so we can test for it later.
	 */
	public function check_excerpt($theExcerpt) {
		$this->_is_excerpt = true;
		return $theExcerpt;
	}

	/**
	 * This function is set as a filter on post content, and adds the attachment
	 * list if needed.
	 *
	 * @param string $post_content - Content of the current post
	 * @return string - Modified post content
	 */
	public function attach_to_post($post_content) {
		global $post;
		$dont_show_on_cats = ($this->_settings['wam_dont_show_on_cat_page'] == 'true');
		$this_post = (get_post_meta($post->ID, '_wam_show_attachments', true) == 'true');
		$dont_show_on_excerpts = (($this->_settings['wam_dont_show_on_excerpts'] == 'true') && !is_feed());
		$show_on_home_page = (($this->_settings['wam']['home_page'] == 'show') || is_feed());

		/**
		 * If we show on all posts or this post AND if we show on categories or
		 * this isn't a category AND if we show on excerpt or this isn't an
		 * excerpt
		 */
		if ( ($this->_settings['wam_list_on_posts'] == 'all' || ($this->_settings['wam_list_on_posts'] == 'some' && $this_post)) &&
				(!$dont_show_on_cats || !is_category()) &&
				( !$dont_show_on_excerpts || !$this->_is_excerpt ) &&
				( 'true' == $this->_settings['wam']['show_on_rss'] || !is_feed() ) &&
				( $show_on_home_page || !is_home() ) ) {
			$post_content .= $this->_get_attachments();
		}
		return $post_content;
	}

	public function attach_to_rss($post_content) {
		$this->_is_excerpt = false;
		return $this->attach_to_post($post_content);
	}

	/**
	 * Finds the attachments that belong to the current post, and creates an
	 * unordered list of them
	 *
	 * @return string - unordered list of attachments
	 */
	private function _get_attachments() {
		global $wpdb, $post;
		$dont_show_linked = (get_option('wam_dont_show_files_already_linked') == 'true');
		$attachment_str = '';
		$query = "SELECT `id`,`guid`, `post_content`, `post_title` FROM {$wpdb->posts} WHERE (post_status = 'attachment' || post_type = 'attachment') AND post_parent = '{$post->ID}'";
		$attachments = $wpdb->get_results($query);
		if (count($attachments) > 0) {
			$attachment_list = array();
			foreach ( $attachments as $attachment ) {
				if (!$dont_show_linked || (!$this->_isFileLinked($post->post_content, $attachment) )) {
					$link = $this->_get_attachment_link($attachment);
					$post_content = empty($attachment->post_content)? '':"<p>{$attachment->post_content}</p>";
					$attachment_list[] = $link.$post_content;
				}
			}
			if (count($attachment_list) > 0) {
				$attachment_str = '<div class="wam_wrap"><h4 class="wam">'.__('Attached Files:', $this->_slug).'</h4><ul class="wam_ul"><li>'.implode('</li><li>', $attachment_list).'</li></ul></div>';
			}
		}
		return $attachment_str;
	}

	private function _isFileLinked($content, $file) {
		$pathinfo = pathinfo($file->guid);
		$filename = preg_quote($pathinfo['dirname'].'/'.basename($pathinfo['filename'], '.'.$pathinfo['basename']), '/');
		$ext = preg_quote($pathinfo['extension']);
		return (
			preg_match("/src=['\"]{$filename}(-\d*x\d*)?\.{$ext}['\"]/", $content) ||
			preg_match("/class=['\"][^'\"]*wp-image-{$file->id}[^'\"]*['\"]/", $content)
		);
	}

	/**
	 * Returns a link to a file, including an icon if needed.
	 *
	 * @param object $attachment - wp-attachment object
	 * @return string - link to attachment
	 */
	private function _get_attachment_link($attachment) {
		$icon = '';
		if ($this->_settings['wam_show_file_icons'] == 'true') {
			$ext = $this->_get_extension($attachment->guid);
			$img = $this->_settings['wam_default_icon'];
			foreach( $this->_settings['icons'] as $cur_icon ) {
				$cur_icon['exts'] = preg_split('/\s*,\s*/', $cur_icon['exts']);
				if ( in_array($ext, $cur_icon['exts']) ) {
					$img = $cur_icon['icon'];
					break;
				}
			}
			if (!empty($img)) {
				$icon_url = path_join($this->_get_icon_url(),$img);
				$img_size = getimagesize(path_join($this->_icon_dir,$img));
				$img_alt = esc_html($ext);
				$icon = "<img src='{$icon_url}' {$img_size[3]} alt='{$img_alt}' title='{$img_alt}' style='border:none;' /> ";
			}
		}
		return sprintf("<a href='{$attachment->guid}' class='wam_link'>%s{$attachment->post_title}</a>", $icon);
	}

	/**
	 * This attaches to init, and was needed so we could use wp_redirect.  It
	 * checks to see if an icon is has been requested to be removed.  Then it
	 * removes it, and redirects back to the options page, and gives a succes or
	 * error message
	 */
	public function handle_actions() {
		if (isset($_GET['page']) && $_GET['page'] == 'attachment_manager') {
			if ( isset($_GET['action']) ) {
				if ('remove' == $_GET['action']) {
					$this->_settings['icons'] = array_diff($this->_settings['icons'], array(preg_replace('/[^\w-]/', '', $_GET['icon'])));
					update_option('icons', $this->_settings['icons']);
					if (is_writable($this->_icon_dir.'/'.$_GET['icon']) && @unlink($this->_icon_dir.'/'.$_GET['icon'])) {
						wp_redirect('options-general.php?page=attachment_manager&remove=true');
					} else {
						wp_redirect('options-general.php?page=attachment_manager&remove=false');
					}
				}
				exit;
			} elseif (isset($_FILES['wam_add_icon'])) {
				$filename = basename($_FILES['wam_add_icon']['name']);
				$file = path_join($this->_icon_dir, $filename);
				$icon_clean_name = preg_replace('/[^\w-]/', '', $filename);
				$this->_settings['icons'][$icon_clean_name] = array('exts'=>'', 'icon'=>$filename);
				update_option('icons', $this->_settings['icons']);
				if (move_uploaded_file($_FILES['wam_add_icon']['tmp_name'], $file)) {
					wp_redirect('options-general.php?page=attachment_manager&upload=true');
				} else {
					wp_redirect('options-general.php?page=attachment_manager&upload=false');
				}
			}
		}
	}

	/**
	 * Used to find the file extension of any file (given a filename.ext,
	 * path/to/filename.ext, or even http://example.com/path/to/filename.ext
	 *
	 * @param string $file_name
	 * @return string - lowercase file extension
	 */
	private function _get_extension($file_name) {
		$file = pathinfo($file_name);
		return strtolower($file['extension']);
	}

	/**
	 * Either returns the icon file types from the plugin options, or returns the
	 * default (jpg, jpeg, gif, and png)
	 *
	 * @return array - icon file types
	 */
	private function _get_icon_filetypes() {
		$icon_file_types = get_option('icon_file_types');
		if ($icon_file_types == false || empty($icon_file_types)) {
			$icon_file_types = 'jpg, jpeg, gif, png';
			update_option('icon_file_types', $icon_file_types);
		} elseif (is_array($icon_file_types)) {
			$icon_file_types = implode(',', $icon_file_types);
			update_option('icon_file_types', $icon_file_types);
		}
		return $icon_file_types;
	}

	/**
	 * Add boxes to the "edit post" and "edit page" pages
	 */
	public function adminMenu() {
		add_meta_box($this->_slug, __('Attachment Manager'), array($this, 'post_form'), 'post', 'normal', 'high');
		add_meta_box($this->_slug, __('Attachment Manager'), array($this, 'post_form'), 'page', 'normal', 'high');
	}

	/**
	 * Displays the "show attachments" checkbox if wam_list_on_posts is set to some
	 */
	public function post_form() {
		global $post;
?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="wam_show_attachments"><?php _e('Show attachments for this post')?></label></th>
                <td>
					<input type='checkbox' name='wam_show_attachments' value='true' id='wam_show_attachments'<?php checked('true', get_post_meta($post->ID, '_wam_show_attachments', true)) ?> />
                </td>
            </tr>
		</table>
<?php
	}

	/**
	 * Adds or removes the show_attachments meta from the post
	 *
	 * @param int $pid - Post ID
	 */
	public function handle_save_post($pid) {
		if (isset($_POST['wam_show_attachments']) && strtolower($_POST['wam_show_attachments']) == 'true') {
			add_post_meta($pid, '_wam_show_attachments', 'true', true);
		} else {
			delete_post_meta($pid, '_wam_show_attachments');
		}
	}

	/**
	 * This is used to set some default values when the plugin is activated.
	 */
	public function on_activate() {
		if (get_option('wam_list_on_posts') === false) {
			update_option('wam_list_on_posts', 'all');
		}
		if (get_option('wam_show_file_icons') === false) {
			update_option('wam_show_file_icons', 'true');
		}
		if (get_option('wam_dont_show_on_excerpts') === false) {
			update_option('wam_dont_show_on_excerpts', 'true');
		}

		update_option('wam_list_on_posts', 'all');
		update_option('wam_show_file_icons', 'true');
		update_option('wam_dont_show_on_excerpts', 'true');
	}
}

// Instantiate our class
$wpAttachmentManager = wpAttachmentManager::getInstance();
