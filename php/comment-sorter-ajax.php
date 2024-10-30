<?php 
//Written by Ronald Huereca
//Last modified on 01/22/2008 
if (!function_exists('add_action'))
{
	require_once("../../../../wp-config.php");
}
if (!isset($raproject_commentSorter)) { die(''); }

//Gets the comment
if (isset($_POST['option']) && isset($_POST['ID'])) {
	if(!is_numeric($_POST['ID'])) { echo "0"; } //sanity check for the post ID
	if (($result = $raproject_commentSorter->getSortedComments($_POST['ID'], $_POST['option'])) != "0") {
		echo $result;
	} else { echo "0"; }
}

?>
