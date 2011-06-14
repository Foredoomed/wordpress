<?php
/**
 * @package WordPress
 * @subpackage Theme
 */
?>
<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">

<table cellpadding="0" cellspacing="0" style="width:200px; height:32px;">
    <tbody>
        <tr id="WLSearchBoxPlaceholder">
            <td style="width:100%; border:solid 1px #cccccc; border-right-style:none;  padding-right:10px; vertical-align:middle;">
                <input id="WLSearchBoxInput" type="text"  style="background-image:url(http://www.bing.com/siteowner/s/siteowner/searchbox_background_k.png); background-position:right; background-repeat:no-repeat; font-family:Arial; font-size:14px; color:#000000; width:100%; border:none 0 transparent;" autocomplete="off" maxlength="200">
            </td>
            <td style="border:solid 1px #cccccc; border-left-style:none; padding-left:0px; padding-right:3px;">
                <input id="WLSearchBoxButton" type="image" src="http://www.bing.com/siteowner/s/siteowner/searchbutton_normal_k.gif" style="border:none 0 transparent; height:24px; width:24px; vertical-align:top;">
            </td>
         </tr>
     </tbody>
</table>
</form>
