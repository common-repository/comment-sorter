<?php 
/* Admin Panel Code - Created on January 31, 2008 by Ronald Huereca */
if ($this->adminOptionsName != "raproject_commentSorter_options") { die(''); }
if (isset($_POST['update_raprojectCommentSorterSettings'])) { 
   check_admin_referer('raproject-comment-sorter_admin-options');
   $this->adminOptions['comments_asc'] = $_POST['raprojectSortComments'];
   $this->adminOptions['hide_trackbacks'] = $_POST['raprojectHideTrackbacks'];
   $this->adminOptions['disable_auto_include'] = $_POST['raprojectEnableAutoSorter'];
	 $this->saveAdminOptions();
  ?>
<div class="updated"><p><strong><?php _e('Settings successfully updated.', $this->localizationName) ?></strong></p></div>
<?php } ?>
<div class=wrap>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<?php wp_nonce_field('raproject-comment-sorter_admin-options') ?>
<h2><?php _e('Comment Sorter Template Options', $this->localizationName) ?></h2>
<p><?php _e('You and your readers have used this tool', $this->localizationName) ?> <?php echo number_format(intval($this->adminOptions['sort_count'])); ?> <?php _e('times.', $this->localizationName) ?> <?php _e('Please', $this->localizationName) ?> <a href='https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ronalfy%40gmail%2ecom&item_name=Comment%20Sorter&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8'><?php _e('Donate.', $this->localizationName) ?></a></p>
<h3><?php _e('Disable Auto-Include?', $this->localizationName) ?></h3>
<p><?php _e('Selecting "Yes" will disable the Comment-Sorter box from automatically showing in your template.  You will have to call a template tag where you want the box to be displayed.', $this->localizationName); ?></p>
<p><label for="raprojectEnableAutoSorter_yes"><input type="radio" id="raprojectEnableAutoSorter_yes" name="raprojectEnableAutoSorter" value="true" <?php if ($this->adminOptions['disable_auto_include'] == "true") { echo('checked="checked"'); }?> /> <?php _e('Yes', $this->localizationName) ?></label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="raprojectEnableAutoSorter_no"><input type="radio" id="raprojectEnableAutoSorter_no" name="raprojectEnableAutoSorter" value="false" <?php if ($this->adminOptions['disable_auto_include'] == "false") { echo('checked="checked"'); }?>/> <?php _e('No', $this->localizationName) ?></label></p>
<h2><?php _e('General Options', $this->localizationName) ?></h2>
<h3><?php _e('Disable Trackbacks?', $this->localizationName) ?></h3>
<p><?php _e('Selecting "Yes" will hide trackbacks for all your posts.', $this->localizationName); ?></p>
<p><label for="raprojectHideTrackbacks_yes"><input type="radio" id="raprojectHideTrackbacks_yes" name="raprojectHideTrackbacks" value="true" <?php if ($this->adminOptions['hide_trackbacks'] == "true") { echo('checked="checked"'); }?> /> <?php _e('Yes', $this->localizationName) ?></label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="raprojectHideTrackbacks_no"><input type="radio" id="raprojectHideTrackbacks_no" name="raprojectHideTrackbacks" value="false" <?php if ($this->adminOptions['hide_trackbacks'] == "false") { echo('checked="checked"'); }?>/> <?php _e('No', $this->localizationName) ?></label></p>
<h3><?php _e('Comments Sorted Ascending or Descending (by date)?', $this->localizationName) ?></h3>
<p><?php _e('Selecting "ASC" will sort your comments in ascending order.  Selecting "DESC" will sort your comments in descending order.  This option affects all posts.', $this->localizationName); ?></p>
<p><label for="raprojectSortComments_asc"><input type="radio" id="raprojectSortComments_asc" name="raprojectSortComments" value="true" <?php if ($this->adminOptions['comments_asc'] == "true") { echo('checked="checked"'); }?> /> <?php _e('ASC', $this->localizationName) ?></label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="raprojectSortComments_desc"><input type="radio" id="raprojectSortComments_desc" name="raprojectSortComments" value="false" <?php if ($this->adminOptions['comments_asc'] == "false") { echo('checked="checked"'); }?>/> <?php _e('DESC', $this->localizationName) ?></label></p>
<div class="submit">
<input type="submit" name="update_raprojectCommentSorterSettings" value="<?php _e('Update Settings', $this->optionsName) ?>" /></div>
</form>
</div>
