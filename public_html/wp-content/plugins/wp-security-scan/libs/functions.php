<?php

if (!function_exists('make_seed')) :
	/**
	 * @public
	 * Create a number
	 * @return double
	 */
    function make_seed()
    {
        list($usec, $sec) = explode(' ', microtime());
        return (float)$sec + ((float)$usec * 100000);
    }
endif;


if (!function_exists('make_password')) :
	/**
	 * @public
	 * @uses make_seed()
	 * Generate a strong password
	 * @return string
	 */
    function make_password($password_length)
    {
        srand(make_seed());
        $alfa = "!@123!@4567!@890qwer!@tyuiopa@!sdfghjkl@!zxcvbn@!mQWERTYUIO@!PASDFGH@!JKLZXCVBNM!@";
        $token = "";
        for($i = 0; $i < $password_length; $i ++) {
          $token .= $alfa[rand(0, strlen($alfa))];
        }
        return $token;
    }
endif;

function check_perms($name,$path,$perm)
{
    clearstatcache();
    $configmod = substr(sprintf("%o", fileperms($path)), -4);
    $trcss = (($configmod != $perm) ? "background-color:#fd7a7a;" : "background-color:#91f587;");
    echo "<tr style=".$trcss.">";
        echo '<td style="border:0px;">' . $name . "</td>";
        echo '<td style="border:0px;">'. $path ."</td>";
        echo '<td style="border:0px;">' . $perm . '</td>';
        echo '<td style="border:0px;">' . $configmod . '</td>';
    echo "</tr>";
}

//since 3.0.3
function wsd_getFilePermissions($filePath)
{
    clearstatcache();
    $res =  @substr(sprintf("%o", fileperms($filePath)), -4);
    
    return (empty($res) ? '0' : $res);
}

function mrt_get_serverinfo() {
        global $wpdb;
        $sqlversion = $wpdb->get_var("SELECT VERSION() AS version");
        $mysqlinfo = $wpdb->get_results("SHOW VARIABLES LIKE 'sql_mode'");
        if (is_array($mysqlinfo)) $sql_mode = $mysqlinfo[0]->Value;
        if (empty($sql_mode)) $sql_mode = __('Not set');
        if(ini_get('safe_mode')) $safe_mode = __('On');
        else $safe_mode = __('Off');
        if(ini_get('allow_url_fopen')) $allow_url_fopen = __('On');
        else $allow_url_fopen = __('Off');
        if(ini_get('upload_max_filesize')) $upload_max = ini_get('upload_max_filesize');
        else $upload_max = __('N/A');
        if(ini_get('post_max_size')) $post_max = ini_get('post_max_size');
        else $post_max = __('N/A');
        if(ini_get('max_execution_time')) $max_execute = ini_get('max_execution_time');
        else $max_execute = __('N/A');
        if(ini_get('memory_limit')) $memory_limit = ini_get('memory_limit');
        else $memory_limit = __('N/A');
        if (function_exists('memory_get_usage')) $memory_usage = round(memory_get_usage() / 1024 / 1024, 2) . __(' MByte');
        else $memory_usage = __('N/A');
        if (is_callable('exif_read_data')) $exif = __('Yes'). " ( V" . substr(phpversion('exif'),0,4) . ")" ;
        else $exif = __('No');
        if (is_callable('iptcparse')) $iptc = __('Yes');
        else $iptc = __('No');
        if (is_callable('xml_parser_create')) $xml = __('Yes');
        else $xml = __('No');

?>
        <li><?php _e('Operating System'); ?> : <strong><?php echo PHP_OS; ?></strong></li>
        <li><?php _e('Server'); ?> : <strong><?php echo $_SERVER["SERVER_SOFTWARE"]; ?></strong></li>
        <li><?php _e('Memory usage'); ?> : <strong><?php echo $memory_usage; ?></strong></li>
        <li><?php _e('MYSQL Version'); ?> : <strong><?php echo $sqlversion; ?></strong></li>
        <li><?php _e('SQL Mode'); ?> : <strong><?php echo $sql_mode; ?></strong></li>
        <li><?php _e('PHP Version'); ?> : <strong><?php echo PHP_VERSION; ?></strong></li>
        <li><?php _e('PHP Safe Mode'); ?> : <strong><?php echo $safe_mode; ?></strong></li>
        <li><?php _e('PHP Allow URL fopen'); ?> : <strong><?php echo $allow_url_fopen; ?></strong></li>
        <li><?php _e('PHP Memory Limit'); ?> : <strong><?php echo $memory_limit; ?></strong></li>
        <li><?php _e('PHP Max Upload Size'); ?> : <strong><?php echo $upload_max; ?></strong></li>
        <li><?php _e('PHP Max Post Size'); ?> : <strong><?php echo $post_max; ?></strong></li>
        <li><?php _e('PHP Max Script Execute Time'); ?> : <strong><?php echo $max_execute; ?>s</strong></li>
        <li><?php _e('PHP Exif support'); ?> : <strong><?php echo $exif; ?></strong></li>
        <li><?php _e('PHP IPTC support'); ?> : <strong><?php echo $iptc; ?></strong></li>
        <li><?php _e('PHP XML support'); ?> : <strong><?php echo $xml; ?></strong></li>
<?php
}

function mrt_check_table_prefix(){
    if($GLOBALS['table_prefix']=='wp_'){
        echo '<span style="color:#f00">Your table prefix should not be <i>wp_</i>.  <a href="admin.php?page=database">Click here</a> to change it.</span><br />';
    }
    else { echo '<span class="scanpass">Your table prefix is not <i>wp_</i>.</span><br />'; }
}

function mrt_errorsoff(){
    echo '<span class="scanpass">WordPress DB Errors turned off.</span><br />';
}

function mrt_wpdberrors()
{
    global $wpdb;
    $wpdb->show_errors = false;
}

function mrt_version_removal(){
global $wp_version;
   echo '<span class="scanpass">Your WordPress version is successfully hidden.</span><br />';
}

function mrt_remove_wp_version()
{
    function filter_generator( $gen, $type ) { 
        switch ( $type ) { 
            case 'html':
                $gen = '<meta name="generator" content="WordPress">';
                break;
            case 'xhtml':
                $gen = '<meta name="generator" content="WordPress" />';
                break;
            case 'atom':
                $gen = '<generator uri="http://wordpress.org/">WordPress</generator>';
                break;
            case 'rss2':
                $gen = '<generator>http://wordpress.org/?v=</generator>';
                break;
            case 'rdf':
                $gen = '<admin:generatorAgent rdf:resource="http://wordpress.org/?v=" />';
                break;
            case 'comment':
                $gen = '<!-- generator="WordPress" -->';
                break;
        }    
        return $gen;
    }
    foreach ( array( 'html', 'xhtml', 'atom', 'rss2', 'rdf', 'comment' ) as $type ) {
        add_filter( "get_the_generator_$type", 'filter_generator', 10, 2 );
    }
}

function mrt_check_version(){
//echo "WordPress Version: ";
global $wp_version;
$mrt_wp_ver = ereg_replace("[^0-9]", "", $wp_version);
while ($mrt_wp_ver > 10){
    $mrt_wp_ver = $mrt_wp_ver/10;
    }
if ($mrt_wp_ver >= "2.8") $g2k5 = '<span class="scanpass"><strong>WordPress version: ' . $wp_version . '</strong> &nbsp;&nbsp;&nbsp; You have the latest stable version of WordPress.</span><br />';
if ($mrt_wp_ver < "2.8") $g2k5 = '<span style="color:#f00"><strong>WordPress version: ' . $wp_version . '</strong> &nbsp;&nbsp;&nbsp; You need version 2.8.6.  Please <a href="http://wordpress.org/download/">upgrade</a> immediately.</span><br />';
echo $g2k5;
}


function mrt_javascript(){
$siteurl = get_option('siteurl');
?><script language="JavaScript" type="text/javascript" src="<?php echo WP_PLUGIN_DIR;?>/wp-security-scan/js/scripts.js"></script><?php
}



/**
 * @public
 * @since v3.0.2
 * Check permissions (&& if we can automatically change it) for the wp-config.php file
 * @param string $wpConfigFilePath The path to the wp-config file
 * @return boolean
 */
function wsd_wpConfigCheckPermissions($wpConfigFilePath)
{
    if (!is_writable($wpConfigFilePath)) { return false; }

    // We use these functions later to access the wp-config file
    // so if they're not available we stop here
    if (!function_exists('file') || !function_exists('file_get_contents') || !function_exists('file_put_contents'))
    {
        return false;
    }

    return true;
}

/**
 * @public
 * @since v3.0.2
 * @return array
 */
function wsd_getDbUserRights()
{
/*
    global $wpdb;
    $rights = $wpdb->get_results("SHOW GRANTS FOR '".DB_USER."'@'".DB_HOST."'", ARRAY_N);
    $rightsenough = $rightstomuch = false;

    foreach ($rights as $right) {
        
        
        if (ereg("ALTER(.*)(\*|`".str_replace("_", "\\\\_", DB_NAME)."`)\.(\*|`".DB_HOST."`) TO '".DB_USER."'@'".DB_HOST."'", $right[0]) || ereg("ALL PRIVILEGES ON (\*|`".str_replace("_", "\\\\_", DB_NAME)."`)\.(\*|`".DB_HOST."`) TO '".DB_USER."'@'".DB_HOST."'", $right[0])) {
            $rightsenough = $rightstomuch = true;
            break;
        }
        else {
            if (ereg("ALTER(.*)`".DB_NAME."`", $right[0])) {
                $rightsenough = true;
                break;
            }
        }
    }

    return array(
        'rightsEnough' => $rightsenough,
        'rightsTooMuch' => $rightstomuch,
    );    
 */
    global $wpdb;

    $rightsenough = $rightstoomuch = false;
    $data = array(
        'rightsEnough' => false,
        'rightsTooMuch' => false
    );

//@ $r1 09/12/2011 {c} $

    $rights = $wpdb->get_results("SHOW PRIVILEGES", ARRAY_N);

    if (empty($rights)) { return $data; }

    $_tooManyRights = array('CREATE','DELETE','DROP','EVENT','EXECUTE','FILE','GRANT','PROCESS','RELOAD','SHUTDOWN','SUPER');
    $numRights = 0;
    foreach ($rights as $right)
    {
        if (! empty($right[0]))
        {
            $_right = strtoupper($right[0]);
            if ('ALTER' == $_right) {
                $rightsenough = true;
            }
            if (in_array($_right, $tooManyRights)) {
                $numRights += 1;
            }
        }
    }
    if ($numRights >= 5) {
        $rightstoomuch = true;
    }

    return array(
        'rightsEnough' => $rightsenough,
        'rightsTooMuch' => $rightstoomuch,
    );    
}


/**
 * @public
 * @since v3.0.2
 * @revision $1 07/13/2011 $k
 * 
 * Update the wp-config file to reflect the table prefix change.
 * The wp file must be writable for this operation to work!
 * 
 * @param string $wsd_wpConfigFile The path to the wp-config file
 * @param string $newPrefix The new prefix to use instead of the old one
 * @return boolean
 */
function wsd_updateWpConfigTablePrefix($wsd_wpConfigFile, $oldPrefix, $newPrefix)
{
    // Check file' status's permissions
    if (!is_writable($wsd_wpConfigFile))
    {
        return -1;
    }
    
    if (!function_exists('file')) {
        return -1;
    }

    // Try to update the wp-config file
    $lines = file($wsd_wpConfigFile);
    $fcontent = '';
    $result = -1;
    foreach($lines as $line)
    {
        $line = ltrim($line);
        if (!empty($line)){
            if (strpos($line, '$table_prefix') !== false){
                $line = preg_replace("/=(.*)\;/", "= '".$newPrefix."';", $line);
            }
        }
        $fcontent .= $line;
    }
    if (!empty($fcontent)){
        // Save wp-config file
        $result = file_put_contents($wsd_wpConfigFile, $fcontent);
    }
    
    return $result;
}

/**
 * @public
 * @since v3.0.2
 * Get the list of tables to modify
 * @global object $wpdb
 * @return array
 */
function wsd_getTablesToAlter()
{
    global $wpdb;
    
    return $wpdb->get_results("SHOW TABLES LIKE '".$GLOBALS['table_prefix']."%'", ARRAY_N);
}

/**
 * @public
 * @since v3.0.2
 * Rename tables from database
 * @global object $wpdb 
 * @param array the list of tables to rename
 * @param string $currentPrefix the current prefix in use
 * @param string $newPrefix the new prefix to use
 * @return array
 */
function wsd_renameTables($tables, $currentPrefix, $newPrefix)
{
    global $wpdb;

    $changedTables = array();
    
    foreach ($tables as $k=>$table)
    {
        $tableOldName = $table[0];

        // Hide errors
        $wpdb->hide_errors();

        // Try to rename the table
        $tableNewName = substr_replace($tableOldName, $newPrefix, 0, strlen($currentPrefix));
        //$tableNewName = str_ireplace($currentPrefix, $newPrefix, $tableOldName);

        // Try to rename the table
        $wpdb->query("RENAME TABLE `{$tableOldName}` TO `{$tableNewName}`");
        array_push($changedTables, $tableNewName);
    }
    return $changedTables;
}

/**
 * @public
 * @since v3.0.2
 * @revision $1 07/13/2011 $k
 * 
 * Rename some fields from options & usermeta tables in order to reflect the prefix change
 *
 * @global object $wpdb
 * @param string $newPrefix the new prefix to use
 */
function wsd_renameDbFields($oldPrefix,$newPrefix)
{
    global $wpdb;

/*
 * usermeta table
 * ===========================
    wp_*
 
 * options table
 * ===========================
    wp_user_roles
    
*/
    $str = '';

    if (false === $wpdb->query("UPDATE {$newPrefix}options SET option_name='{$newPrefix}user_roles' WHERE option_name='{$oldPrefix}user_roles';")) {
        $str .= '<br/>Changing value: '.$newPrefix.'user_roles in table <strong>'.$newPrefix.'options</strong>: <font color="#ff0000">Failed</font>';
    }

    $query = 'update '.$newPrefix.'usermeta
set meta_key = CONCAT(replace(left(meta_key, ' . strlen($oldPrefix) . "), '{$oldPrefix}', '{$newPrefix}'), SUBSTR(meta_key, " . (strlen($oldPrefix) + 1) . "))
where 
    meta_key in ('{$oldPrefix}autosave_draft_ids', '{$oldPrefix}capabilities', '{$oldPrefix}metaboxorder_post', '{$oldPrefix}user_level', '{$oldPrefix}usersettings', 
                '{$oldPrefix}usersettingstime', '{$oldPrefix}user-settings', '{$oldPrefix}user-settings-time', '{$oldPrefix}dashboard_quick_press_last_post_id')";

    if (false === $wpdb->query($query)) {
        $str .= '<br/>Changing values in table <strong>'.$newPrefix.'usermeta</strong>: <font color="#ff0000">Failed</font>';
    }
    
    if (!empty($str)) {
        $str = '<div class="wsd_user_information"><p>Changing database prefix:</p><p>'.$str.'</p></div>';
    }
    
    return $str;
}


/**
 * @public
 * @since v3.0.2
 * Backup the database and save the script to backups directory
 * @param string $tables the table or the list of tables to backup.
 * Defaults to all tables
 * @return int The function returns the number of bytes that were written to the file, or
 * false on failure.
 */
function wsd_backupDatabase($tables = '*')
{
  // cache
  $_tables = $tables;
    
  $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
  if (!$link) {
      exit('Error: Cannot connect to db');
  }
  if (!mysql_select_db(DB_NAME,$link)) {
      exit('Error: Could not select db');
  }

  //get all of the tables
  if($tables == '*')
  {
    $tables = array();
    $result = mysql_query('SHOW TABLES');
    while($row = mysql_fetch_row($result))
    {
      $tables[] = $row[0];
    }
  }
  else
  {
    $tables = is_array($tables) ? $tables : explode(',',$tables);
  }
  
  $return = 'CREATE DATABASE IF NOT EXISTS '.DB_NAME.";\n\n";
  $return .= 'USE '.DB_NAME.";\n\n";
  
  //cycle through
  foreach($tables as $table)
  {
    $result = mysql_query('SELECT * FROM '.$table);
    $num_fields = mysql_num_fields($result);
    
    $return.= 'DROP TABLE IF EXISTS '.$table.';';
    $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
    $return.= "\n\n".$row2[1].";\n\n";
    
    for ($i = 0; $i < $num_fields; $i++) 
    {
      while($row = mysql_fetch_row($result))
      {
        $return.= 'INSERT INTO '.$table.' VALUES(';
        for($j=0; $j<$num_fields; $j++) 
        {
          $row[$j] = addslashes($row[$j]);
          $row[$j] = ereg_replace("\n","\\n",$row[$j]);
          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
          if ($j<($num_fields-1)) { $return.= ','; }
        }
        $return.= ");\n";
      }
    }
    $return.="\n\n\n";
  }
  
  //save file
    $fname = 'bck-'.date("m-d-Y",time()).'-'.md5(uniqid(rand())).'.sql';
    $filePath = ABSPATH.PLUGINDIR .'/wp-security-scan/backups/'.$fname;
    $ret = file_put_contents($filePath, $return); 
    if ($ret > 0) {
        return $fname;
    }
    return '';
}

/*
 * @public
 * @since v3.0.2
 * Retrieve the list of tables from database
 * @return array
 */
function wsd_getTablesList()
{
    global $wpdb;
    $data = $wpdb->get_results("SHOW TABLES",ARRAY_N);
    if (empty($data))
    {
        return array();
    }
    $tmp = array();
    foreach($data as $k=>$v)
    {
        array_push($tmp, $v[0]);
    }
    return $tmp;
}

/**
 * @public
 * @since v3.0.2
 * Retrieve the list of all available backup files from the backups directory
 * @return array
 */
function wsd_getAvailableBackupFiles()
{
    $files = glob(ABSPATH. '/wp-content/plugins/wp-security-scan/backups/*.sql');
    if (empty($files)) { return array();}
    return array_map('basename', $files/*, array('.sql')*/);
    
}


/**
 * @public
 * @since v3.0.2
 * Retrieve the content of the specified template file
 * from the inc/admin/templates directory
 * 
 * @param string $fileName The name of the file to retrieve. Without the .php extension!
 * @param array $vars The list of variables to send to the template
 * @return string The file's content
 */
function wsd_getTemplate($fileName, array $vars = array())
{
    $file = ABSPATH.PLUGINDIR.'/wp-security-scan/inc/admin/templates/'.$fileName.'.php';
    if (!is_file($file)) { return ''; }
    
    $str = '';
    ob_start();
        if (!empty($vars)) {
            extract($vars);
        }
        include $file;
        $str = ob_get_contents();
    ob_end_clean();
    
    return $str;
}


/**
 * @public
 * @since v3.0.2
 * Display a specific message
 * @param string $infoMessage The info message to display
 * @param string $alertType The type of the message to display. This string
 * should be part of the wsd_user_* css class that will be used to style the output.
 * Defaults to 'notify' (wsd_user_notify).
 * @return string
 */
function wsd_eInfo($infoMessage, $alertType = 'notify')
{
    return ('<p class="wsd_user_'.$alertType.'">'.$infoMessage.'</p>');
}
?>