=== Plugin Name ===
Contributors: ronalfy
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ronalfy%40gmail%2ecom&item_name=Comment%20Sorter&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Author URI: http://www.ronalfy.com
Plugin URI: http://www.raproject.com/comment-sorter/
Tags: post, comments, ajax, comment, sort, admin
Requires at least: 2.2
Tested up to: 2.5
Stable tag: 1.1

Allows readers to choose the comment sort order and allows for filtering of Trackbacks/Pingbacks. 

== Description ==

How many times you been to a blog where Trackbacks/Pingbacks were interspersed with the regular comments? Or perhaps you wanted the comments to be displayed with the latest one on the top instead of the bottom. With Comment Sorter, readers can disable Trackbacks and also decide what order they would like to read the comments.

As an admin, you also have the ability to assign global options to your comments that you can't normally do in the WordPress admin panel. For example, if you want Trackbacks to not show on all of your posts, you can do that without modifying any template files. You can also change the default sort order of the comments.

For bug reports, feature requests, and support requests, please <a href='http://www.raproject.com/support/'>contact me</a>.
<h3>Demonstration Videos</h3>
For videos and screenshots of the plugin in action, please visit the <a href='http://www.raproject.com/comment-sorter/media'>Comment Sorter Media Page</a>.
<h3>User Features</h3>

<ul>

<li>Prevent Trackbacks/Pingbacks from showing.</li>

<li>Sort comments by Date Ascending.</li>

<li>Sort comments by Date Descending.</li>

<li>Sort comments by Name Ascending.</li>

<li>Sort comments by Name Descending.</li>

<li>The ability to remember options.</li>

</ul>



<h3>Admin Features</h3>

<ul>

<li>Ability to change default sort order of comments without modifying template files.</li>

<li>Ability to disable Trackbacks/Pingbacks for all posts without modifying template files (you will still receive Trackbacks/Pingbacks, they just won't show on a post).</li>

<li>Can disable the auto-include of the sorter interface (useful if you only want this to be an admin tool).</li>

</ul>



== Installation ==
<ul>
<li>Download the plugin.</li>
<li>Extract the plugin from the ZIP file and place the "comment-sorter" directory into your WordPress plugins directory.</li>
<li>Go to the Plugins page in your WordPress Admin Panel and "Activate" the Comment Sorter plugin.</li>
</ul>

The plugin will automatically insert the interface above the first comment section it finds.  It searches for the class "<strong>commentlist</strong>", which is the default class for a comments section.



If you want to interface to be inserted manually, go to the Comment Sorter options (under "Options" in the Admin Panel) and select "Yes" for Disable Auto Include.  From there you'll want to add this code where you want the plugin to show up:
<p>&lt;?php if (function_exists(&quot;raproject_getCommentSorter&quot;)) { raproject_getCommentSorter(); } ?&gt;</p>

== Frequently Asked Questions ==
= The Comment Sorter interface isn't showing or isn't doing anything.  What's wrong? =
Make sure that your comment list has the class "commentlist".  This is the default class for the comments section and some theme authors do not use this.

If the interface fails to show up, you can try inserting it manually using the template tag.  Go into the admin panel and set "Disable Auto-Include" to "Yes" and insert the following template tag above your comments section:

<p>&lt;?php if (function_exists(&quot;raproject_getCommentSorter&quot;)) { raproject_getCommentSorter(); } ?&gt;</p>

= Why give readers the option to sort? =

Because readers have different preferences on how they want to read comments.  Some prefer to read comments without Trackbacks/Pingbacks interspersed.  Some prefer to read the latest comment at the top of the comment's section instead of the bottom.

= Why would admin want to disable Trackbacks/Pingbacks? =

Not all readers like having Trackbacks/Pingbacks shown with the regular comments.

= Does this plugin separate Comments and Trackbacks? =

Unfortunately no.  There's no way (from what I've seen) to separate Comments and Trackbacks without modifying template files.

= This plugin causes other comment plugins to not work.  What's wrong? =

When a reader uses the sort options, any plugins that rely on events to modify the comments section will not work.  There is not an elegant solution to this problem unfortunately.

If the reader chooses to have the plugin remember their settings, the comment section will be made re-compatible with other plugins after a refresh.

= What browsers is this plugin compatible with? =

This plugin has been tested successfully on Firefox 2.0, Safari 3.0, IE 6 & 7.  This plugin does not work on IE 5.5.

==Screenshots==

1. Comment section with Trackbacks Showing
2. The Comment Sorter Interface
3. Selecting Comment Sorter Options
4. Comment section with Trackbacks Filtered Out
5. Comment Sorter Admin Panel

==Other Notes==
The plugin is available in the following languages:

<ul>
<li>English</li>
</ul>

If you are interested in providing a translation, please <a href='http://www.raproject.com/support/'>contact me</a>.








