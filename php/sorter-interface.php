<?php 
/* User Interface Code - Created on January 31, 2008 by Ronald Huereca */
if ($this->adminOptionsName != "raproject_commentSorter_options") { die(''); } ?>
<!-- Code inserted by comment-sorter -->
<div id="comment-sorter">
<p><a id="comment-sorter-open" href="#"><img src="<?php bloginfo('wpurl') ?>/wp-content/plugins/comment-sorter/images/sort_icon_open.gif" width="48" height="45" alt='<?php _e("Open Sort Options", $this->localizationName) ?>' title='<?php _e("Open Sort Options", $this->localizationName) ?>' /></a></p>
<div id="comment-sorter-container">
<h5>Sort comments by:</h5>
<form id="comment-sorter-form">
<ul id="comment-sorter-left">
<?php
$rap_commentSorterDate = ''; 
$rap_commentSorterName = ''; 
$rap_commentSorterTrackbacks = ''; 
if (isset($_COOKIE['rap_comment_sorter'])) { 
	$rap_CommentSorterCookieVars = explode(',', $_COOKIE['rap_comment_sorter']);
	if ($rap_CommentSorterCookieVars[0] == "true") { $rap_commentSorterTrackbacks = 'true'; }
	switch ($rap_CommentSorterCookieVars[1]) {
					case "dateasc":
					$rap_commentSorterDate = "dateasc";
					break;
					case "datedesc":
					$rap_commentSorterDate = "datedesc";
					break;
					case "nameasc":
					$rap_commentSorterName = 'nameasc'; 
					break;
					case "namedesc":
					$rap_commentSorterName = 'namedesc'; 
					break;
				}
}
//todo - not remembering comment-sorter info - This is a bug in Firefox only
?>
<li><label for="comment-sorter-date_asc"><input type="radio" id="comment-sorter-date_asc" name="comment-sorter-options" value="dateasc" <?php if ($rap_commentSorterDate == "dateasc") { ?>checked="checked"<?php }?> /> <?php _e('Date ASC',$this->localizationName); ?></label></li>
<li><label for="comment-sorter-date_desc"><input type="radio" id="comment-sorter-date_desc" name="comment-sorter-options" value="datedesc" <?php if ($rap_commentSorterDate == "datedesc") { ?>checked="checked"<?php }?> /> <?php _e('Date DESC',$this->localizationName); ?></label></li>
<li><label for="comment-sorter-name_asc"><input type="radio" id="comment-sorter-name_asc" name="comment-sorter-options" value="nameasc" <?php if ($rap_commentSorterName == "nameasc") { ?>checked="checked"<?php }?>/> <?php _e('Name ASC',$this->localizationName); ?></label></li><li><label for="comment-sorter-name_desc"><input type="radio" id="comment-sorter-name_desc" name="comment-sorter-options" value="namedesc" <?php if ($rap_commentSorterName == "namedesc") { ?>checked="checked"<?php }?>/> <?php _e('Name DESC',$this->localizationName); ?></label></li></ul>
<ul id="comment-sorter-right"><li><label for="comment-sorter-trackbacks"><input type="checkbox" id="comment-sorter-trackbacks" name="comment-sorter-trackbacks" value="true" <?php if ($rap_commentSorterTrackbacks == "true") { ?>checked="checked"<?php }?> /> <?php _e('Disable Trackbacks',$this->localizationName); ?></label></li><li><label for="comment-sorter-remember"><input type="checkbox" id="comment-sorter-remember" name="comment-sorter-remember" value="true"<?php if (isset($_COOKIE['rap_comment_sorter'])) { ?> checked="checked" <?php }?> /> <?php _e('Remember Settings?',$this->localizationName); ?> *</label></li><li><small>* <?php _e('Applied after refresh', $this->localizationName); ?></small></li></ul>
<p id="comment-sorter-submit-container"><input type="submit" name="update_comment-sorter" id="comment-sorter_submit" value="<?php _e('Apply', $this->localizationName) ?>" /></p>
</form>
</div>
 </div>
<!-- End Comment Sorter-->
