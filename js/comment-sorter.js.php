<?php /*WP Comment-Sorter Script
--Created by Ronald Huereca
--Created on: 01/20/2008
--Last modified on: 02/04/2008
--Relies on jQuery
*/ 
if (!function_exists('add_action'))
{
	require_once("../../../../wp-config.php");
} ?>
<?php if (!empty($raproject_commentSorter)) { ?>
jQuery(document).ready(function(){
   RAProjectCommentSorter.init();
	 
});
var RAProjectCommentSorter = function() {
	var $j = jQuery;
	var PluginUrl = "<?php bloginfo('wpurl') ?>/wp-content/plugins/comment-sorter";
  var CommentID = 0; //Used to get the Post ID in Ajax
  var HideTrackbacks = "false";
  var SortComments = 'dateasc';
	function insertInterface() {
  	if ($j("#comment-sorter-template").length == 0) {
      if ($j(".commentlist:first").length > 0) {
        $j("#comment-sorter").insertBefore(".commentlist:first"); 
      }
    }
    $j("#comment-sorter").show(); 
	}
	function setupEvents() {
		//Toggles the Open/Close link
		$j("a#comment-sorter-open").toggle(
     function () {
       $j("#comment-sorter-container").show(); 
			 $j("#comment-sorter-open img").attr("src", PluginUrl + "/images/sort_icon_close.gif");
			 $j("#comment-sorter-open img").attr("alt", "<?php _e('Close Sort Options',$raproject_commentSorter->localizationName); ?>");
       $j("#comment-sorter-open img").attr("title", "<?php _e('Close Sort Options',$raproject_commentSorter->localizationName); ?>");
			 return false;
			 
      },
    function () {
      $j("#comment-sorter-container").hide(); 
       $j("#comment-sorter-open img").attr("src", PluginUrl + "/images/sort_icon_open.gif");
     $j("#comment-sorter-open img").attr("alt", "<?php _e('Open Sort Options',$raproject_commentSorter->localizationName); ?>");
     $j("#comment-sorter-open img").attr("title", "<?php _e('Open Sort Options',$raproject_commentSorter->localizationName); ?>");
      return false;
    }
    );
		$j("#comment-sorter_submit").bind("click",function() { formProcessor(); return false;});
	}
	function hideTrackbacks() {
		//Hides Trackbacks
		$j("li:has(.comment-sorter-trackback)").hide();
		$j(".commentlist div:has(.comment-sorter-trackback)").hide(); //Some theme authors use divs for comments
	}
	function showTrackbacks() {
		//Shows Trackbacks
		$j("li:has(.comment-sorter-trackback)").show();
		$j(".commentlist div:has(.comment-sorter-trackback)").show(); //Some theme authors use divs for comments
	}
  //Gets the date asc information
  function getDateNameAjax(option) {
  	$j.ajax({
			type: "post",
      async: true,
      dataType: "text",
			url: PluginUrl + '/php/comment-sorter-ajax.php',
			timeout: 30000,
			global: false,
			data: {
				option: option,
        ID: CommentID
			},
			success: function(msg) { getComplete(msg); },
			error: function(msg) { getFailure(msg); }
		})
  }
  function getComplete(msg) {
  	if (msg == "0") { getFailure(msg); }
    if ($j(".commentlist:first").length > 0) {
      var commentList = $j(".commentlist:first").clone(true).empty(); //todo - check for existence
      msg = msg.split(',');
      var i = 0;
      for (var i=0; i < msg.length; i++) {
        commentList.append($j("#comment-" + msg[i]).clone(true));
      }
     commentList.hide();
     $j('.commentlist:first').after(commentList);
     $j('.commentlist:first').remove(); //remove original comment list
     commentList.fadeIn("1300");
     if (window.AjaxEditComments) {
      AjaxEditComments.init();
     }
   } else {
   	getFailure(msg);
   }
  }
  function getFailure(msg) { 
  	alert("<?php _e('Could not sort comments',$raproject_commentSorter->localizationName); ?>");
  
  };
	function formProcessor() {
		//trackbacks
		if ($j("#comment-sorter-trackbacks:checked").length > 0) { 
    	HideTrackbacks = "true";
			hideTrackbacks();
		} else {
    	HideTrackbacks = "false";
			showTrackbacks();
		}
		//Sort the comments.  Woot :)
		if ($j("#comment-sorter-date_asc:checked").length > 0) { 
    	SortComments = "dateasc";
			getDateNameAjax("dateasc");
		}
		if ($j("#comment-sorter-date_desc:checked").length > 0) { 
    		SortComments = "datedesc";
				getDateNameAjax("datedesc");
		}
		if ($j("#comment-sorter-name_asc:checked").length > 0) { 
    		SortComments = "nameasc";
				getDateNameAjax("nameasc");
		}
		if ($j("#comment-sorter-name_desc:checked").length > 0) { 
    		SortComments = "namedesc";
				getDateNameAjax("namedesc");
		}
    if ($j("#comment-sorter-remember:checked").length > 0) {
    		rememberSettings('rap_comment_sorter', HideTrackbacks, SortComments);
    } else {
    		createCookie('rap_comment_sorter',"",-1);
    }
	}
  function rememberSettings(cookieName, trackbacks, comments) {
  	createCookie(cookieName, '' + trackbacks + ',' + comments, 365);
  }
  function createCookie(name,value,days) { //from http://www.quirksmode.org/js/cookies.html
    if (days) {
      var date = new Date();
      date.setTime(date.getTime()+(days*24*60*60*1000));
      var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
  }
	return {
			init : function() { //AKA the constructor - 
				insertInterface();
				setupEvents();
        //Get a comment ID
        if ($j(".commentlist:first").length > 0) {
        CommentID = $j(".commentlist li:first").attr("id").match(/([0-9]+)/i)[1]; //todo - Possible sanity check
        }
			}
	};
	
}();
<?php } ?>