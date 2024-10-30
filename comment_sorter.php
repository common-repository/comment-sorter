<?php
/*
Plugin Name: Comment Sorter
Plugin URI: http://www.raproject.com/comment-sorter/
Description: Sorts and filters comments by date and name.  Can disable trackbacks too.
Author: Ronald Huereca
Version: 1.1
Author URI: http://www.raproject.com
Generated At: www.wp-fun.co.uk;
*/ 

/*  Copyright 2008  Ronald Huereca  (email : ronalfy @t gmail <.> com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists('raproject_commentSorter')) {
    class raproject_commentSorter	{
		
		/**
		* @var string   The name the options are saved under in the database.
		*/
		var $adminOptionsName = "raproject_commentSorter_options";
		var $localizationName = "comment-sorter";
			
		/**
		* PHP 4 Compatible Constructor
		*/
		function raproject_commentSorter(){$this->__construct();}
		
		/**
		* PHP 5 Constructor
		*/		
		function __construct(){

		
		if ( isset( $_POST['serve'] ) && $_POST['serve'] == 'js' ) {
			header('Content-type: text/javascript');
			echo '
			function sample_function(args){
				return args;
			}
			';
			//stop the rest of the content being served		
			exit;
		}
		/* Language stuff */
		$comment_sorter_locale = get_locale();
		$comment_sorter_mofile = dirname(__FILE__) . "/languages/" . $this->localizationName . "-".$comment_sorter_locale.".mo";
		load_textdomain($this->localizationName, $comment_sorter_mofile);
		
		$this->adminOptions = $this->getAdminOptions();
		$this->increment = false;
		//actions
		add_action("admin_menu", array(&$this,"add_admin_pages"));
		add_action("comment_form", array(&$this,"addInterface")); //todo - find a better action because this only executues if people have the comment form showing.
		add_action("wp_head", array(&$this,"add_css"));
		add_action('wp_print_scripts', array(&$this, 'JS'));
		
		//Filters
		add_filter('the_posts', array(&$this, 'filterPostComments'), 0);
		add_filter('get_comment_author_link', array(&$this, 'commentAuthor'));
		add_filter('get_comment_date', array(&$this, 'commentDate'));
		add_filter('get_comment_time', array(&$this, 'commentTime'));
		add_filter('comment_text', array(&$this, 'commentText'), '1000'); //Low 
		add_filter('comments_array', array(&$this, 'filterComments')); 
		}
		//Before the comments are presented in a theme, they go through this filter
		function filterComments($comments) {
			global $post, $wpdb;
			if (empty($post) || (!is_single() && !is_page()) || $post->comment_count <= 0) { return $comments; }
			$options = $this->adminOptions;
			$comments = $this->filterTrackbacks($comments, $options); //Filters out trackbacks as applicable
			foreach($comments as $key => $row) { //comment date
				$sortCommentsDate[$key] = $row->comment_date;
			}
			foreach($comments as $key => $row) { //comment author
				$sortCommentsName[$key] = $row->comment_author;
			}
			//read in cookie and override admin values
			if (isset($_COOKIE['rap_comment_sorter'])) { 
				$this->incrementSort();
				$cookievars = explode(',', $_COOKIE['rap_comment_sorter']);
				switch ($cookievars[1]) {
					case "dateasc":
					$comments = $this->commentSort($sortCommentsDate, true, $comments);
					break;
					case "datedesc":
					$comments = $this->commentSort($sortCommentsDate, false, $comments);
					break;
					case "nameasc":
					$comments = $this->commentSort($sortCommentsName, true, $comments);
					break;
					case "namedesc": //SQL query is necessary here because the stupid sort function doesn't work for namedesc.  I doubt this will be used much, so a query is a nice compromise.
					$comments = $wpdb->get_results("select * from $wpdb->comments where comment_approved = '1' and comment_post_ID = $post->ID order by comment_author desc");
					$comments = $this->filterTrackbacks($comments, $options); 
					break;
				}
			} else { //Read in admin values (blog default until user overrides)
				if ($options['comments_asc'] == "true") {
					$comments = $this->commentSort($sortCommentsDate, true, $comments);
				} else {
					$comments = $this->commentSort($sortCommentsDate, false, $comments);
				}
			} //end Cookie IF statement
			//stats
			return $comments;
		}
		//Provides an accurate count for comments on a post.
		function filterPostComments($posts) {
			$options = $this->adminOptions;
			if ($options['hide_trackbacks'] != "true") { return $posts; }
			foreach ($posts as $key => $p) {
				if ($p->comment_count <= 0) { return $posts; }
				$comments = get_approved_comments((int)$p->ID);
				$comments = array_filter($comments, array(&$this,"stripTrackback"));
				$posts[$key]->comment_count = sizeof($comments);
			}
			return $posts;
		}
		//Sorts Comments - $sortArray = an single array with values to sort, $sortOrder (true=asc, false = desc), $comments = The WordPress comments variable
		function commentSort($sortArray, $sortOrder, $comments) {
			if ($sortOrder == true) {
				array_multisort($sortArray, SORT_ASC, SORT_STRING, $comments);
			} else {
				array_multisort($sortArray, SORT_DESC, SORT_STRING, $comments);
			}
			//stats
			return $comments;
		}
		//Filters out trackbacks
		//$comments - The comments array
		//$options - The Comment Sorter Admin Options
		function filterTrackbacks($comments, $options) {
			$hideTrackbacks = 'false';
			if (isset($_COOKIE['rap_comment_sorter'])) { 
				$cookievars = explode(',', $_COOKIE['rap_comment_sorter']);
				$hideTrackbacks = $cookievars[0];
				if ($hideTrackbacks == "true") {
					$comments = array_filter($comments, array(&$this,"stripTrackback")); //filters out trackbacks
				}
			}
				if (!isset($_COOKIE['rap_comment_sorter']) && $options['hide_trackbacks'] == "true") {
				$comments = array_filter($comments, array(&$this,"stripTrackback")); //filters out trackbacks
			}
			return $comments;
		}
		//Strips out trackbacks
		function stripTrackback($var) {
			if ($var->comment_type == 'trackback' || $var->comment_type == 'pingback') { return false; }
			return true;
		}
		/**
		* Retrieves the options from the database.
		* @return array
		*/
		function getAdminOptions() {
			$adminOptions = array("comments_asc" => "true",
			"hide_trackbacks" => "false",
			"disable_auto_include" => "false",
			"sort_count" => '0');
			$savedOptions = get_option($this->adminOptionsName);
			if (!empty($savedOptions)) {
				foreach ($savedOptions as $key => $option) {
					$adminOptions[$key] = $option;
				}
			}
			update_option($this->adminOptionsName, $adminOptions);
			return $adminOptions;
		}
		/**
		* Saves the admin options to the database.
		*/
		function saveAdminOptions(){
			update_option($this->adminOptionsName, $this->adminOptions);
		}
		/** 
		* Increments Sort Count for Stats Tracking Purposes
		*/
		function incrementSort() {
			$this->adminOptions['sort_count'] = intval($this->adminOptions['sort_count']) + 1;
			$this->saveAdminOptions();
		}
		function add_admin_pages(){
				add_submenu_page('options-general.php', "Comment Sorter", "Comment Sorter", 10, "Comment Sorter", array(&$this,"output_sub_admin_page_0"));
		}
		/**
		* Adds control panel in footer for comment sorter */
		function addInterface() {
			if (!is_single()) { return; }
			$options = $this->adminOptions;
			if ($options['disable_auto_include'] == 'false') { //Show interface
				$this->showInterface();
			}
		}
		//Displays the comment sorter interface to the user
		function showInterface() {
			global $post;
			if ( ( !is_single() && !is_page() ) || $post->comment_count <= 0) { return; }
			include dirname(__FILE__) . '/php/sorter-interface.php';
		}
		/**
		*Output the main admin page*/
		function output_sub_admin_page_0(){
			include dirname(__FILE__) . '/php/admin-panel.php';
		}
		/** The "comment" functions add spans around text so jQuery can grab the easier */ 
		function commentAuthor($content) {
			if (!is_single() && !is_page() ) { return $content; }
			$content = '<span class="comment-sorter-author">' . $content . '</span>';
			return $content;
		}
		function commentDate($content) {
			if (!is_single() && !is_page() ) { return $content; }
			$content = '<span class="comment-sorter-date">' . $content . '</span>';
			return $content;
		}
		function commentTime($content) {
			if (!is_single() && !is_page() ) { return $content; }
			$content = '<span class="comment-sorter-time">' . $content . '</span>';
			return $content;
		}
		function commentText($content) {
			global $comment;
			if (!is_single() && !is_page() ) { return $content; }
			if (empty($comment)) { return $content; }
			if ($comment->comment_type == 'trackback' || $comment->comment_type == 'pingback') {
				$content .= '<span class="comment-sorter-trackback">&nbsp;</span>'; //Needs the space for Safari web browser
			}
			return $content;
		}
		function JS() {
			if (!is_single() && !is_page() ) { return $content; }
			if (get_bloginfo('version') >= 2.1 && get_bloginfo('version') < 2.2) {
				wp_register_script('jquery', get_bloginfo('wpurl') . '/wp-content/plugins/comment-sorter/js/jquery.js', '1.2');
			}
			if (get_bloginfo('version') >= 2.1) {
				wp_enqueue_script($this->localizationName, get_bloginfo('wpurl') . '/wp-content/plugins/comment-sorter/js/comment-sorter.js.php', array('jquery'), '1.0');
			}
		}	
		/**
		* Adds a link to the stylesheet to the header
		*/
		function add_css(){
		if (!is_single() && !is_page() ) { return $content; }
		echo '<link rel="stylesheet" href="'.get_bloginfo('wpurl').'/wp-content/plugins/comment-sorter/css/comment-sorter.css" type="text/css" media="screen"  />'; 
		}
		//Returns an array of sorted comment IDs by date asc
		//$option = dateasc, datedesc, nameasc, namedesc
		function getSortedComments($commentID, $option) {
			global $wpdb, $post;
			$sortOption = '';
			switch($option) {
				case "dateasc":
					$sortOption = "comment_date asc";
					break;
				case "datedesc":
					$sortOption = "comment_date desc";
					break;
				case "nameasc":
					$sortOption = "comment_author asc";
					break;
				case "namedesc":
					$sortOption = "comment_author desc";
					break;
				}
			$query = "select comment_ID from $wpdb->comments where comment_post_ID = (select comment_post_ID from $wpdb->comments where comment_ID = $commentID limit 1) && comment_approved = '1' && comment_type = '' order by $sortOption"; //todo - trackback check since this will get rid of trackbacks in comments - Probably recommend that the reader have trackbacks in a div without the class .commentlist
			if (($result = $wpdb->get_col($query))) {
				$commentArray = '';
				foreach ($result as $key) {
					$commentArray .= $key . ",";
				}
				$this->incrementSort();
				return preg_replace('/,$/', '', $commentArray, 1);
			}
			return "0";
		}
  } //End Class
} //End if statement
//instantiate the class
if (class_exists('raproject_commentSorter')) {
	$raproject_commentSorter = new raproject_commentSorter();
	function raproject_getCommentSorter() {
		global $raproject_commentSorter;
		$options = $raproject_commentSorter->getAdminOptions();
		if ($options['disable_auto_include'] == 'true') {
		?>
<div id="comment-sorter-template">
		<?php	$raproject_commentSorter->showInterface();
		?>
</div>
    <?php
		}
	}
}




?>