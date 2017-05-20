=== Slideshow Satellite ===
Contributors: C- Pres
Donate link: http://c-pr.es/satellite/
Tags: responsive slideshow, custom slideshow, watermark, satellite, orbit, responsive, slideshow, infinite scroll, lazy load, flipbook 
Requires at least: 3.1
Tested up to: 4.7.4
Stable tag: 2.4
License: GPLv2 or later

So Responsive! So Customizable! So Modern! Build Slideshows, Flipbooks, and do Infinite Scrolls with Slideshow Satellite. 

== Description ==
Satellite's goal is to present a responsive and beautiful slideshow experience to visitors of your WordPress site.

Choose from multiple easy to use themes like Slideshows with full thumbnail displays, Flipbooks for that animated-gif style slideshow, or the brand new Infinite Scroll!

As of August 2015 - Premium edition is available for free for everyone!

Use either your WordPress Media Galleries or our own highly customized Gallery Editor.

Videos, Feature info, Developer Information and more : http://c-pr.es/satellite/

Easily embed using our slideshow button. Otherwise, to embed into a post/page, simply insert <code>[satellite]</code> into its content with optional <code>post_id</code>, <code>thumbs</code>, <code>exclude</code>, <code>include</code>, <code>caption</code>, and <code>auto</code>  parameters.  Check out the Slideshow Satellite Manual on the plugin details page (linked above) for code examples.

== Installation ==
Installing the WordPress Slideshow Satellite plugin manually is very easy. Simply follow the steps below.

1. Option 1) Search Satellite in WordPress plugins, find Slideshow Satellite and install!

1. Option 2) In your WP Dashboard goto Plugins and Add New. Then click Upload

1. Upload 'slideshow-satellite.zip', Install & Activate.

1. Configure the settings according to your needs through the 'Satellite' > 'Configuration' menu

1. Add and manage your slides in the 'Satellite' > 'Manage Slides' section (Or just use the built in wordpress gallery)

1. Put `[satellite post_id="X" exclude="" caption="on/off" thumbs="on/off"]` to embed a slideshow with the images of a post into your posts/pages or use `[satellite gallery=X]` to embed a slideshow with images in 'Manage Slides'

1. For the most up to date list of options available please goto: http://c-pr.es/satellite and check out the manual

1. Premium Edition: You will download the premium edition from the website directly after paying and setting up your user account. Check your spam folder for your password!!

1. Premium Edition: Just make sure the `/pro/` folder is in your /wp-content/plugins/slideshow-satellite/ directory and you're set!

== Frequently Asked Questions ==
= I am having some issues with my plugin, whats a good first look? =
Start with 'Reset to Defaults' on the top of your plugin configuration page.

= Still having some major issues, next? =
You may be dealing with a conflict with your theme or other plugins. To really test, if it works with Twenty-Eleven theme and no other plugins active it's a conflict.

= It's not that serious, just a little funky =
Oh, well have you checked out the Manual? http://bit.ly/stlmanual

= How can I display the slideshow in a sidebar as a widget? =
Install the plugin Advanced Text Widget and put the embed code in there. We would suggest using the Premium Edition as you can specify width and height in the embed.

= All the images show up on the page, this ain't no slideshow!!! Oh, and I'm running the slideshow through the template in PHP or through another plugin =
The slideshow isn't loading your JS or CSS most likely! That's because the plugin doesn't know it's being called. It's looking for the `[satellite` reference and not seeing it. `[satellite display=off]` in an area being called by the loop on that page or just going to Advanced Settings and turn 'Shortcode Requirement' to off.

= I'm seeing a non-stop loading icon on the page =
You're most likely dealing with some javascript weirdness. Check using Firebug and ask for help in the forums. Your theme or another plugin might be working improperly with Satellite

= Can I display/embed multiple instances of Slideshow Satellite? =
Yes you can, but you have to have the Premium Edition

= What if I only want captions on some of my pages =
Set your default captions to off; for any slideshow you put on your page use `[satellite caption=on]` - Captions can also be set at the Gallery level

= How do I find the numbers to exclude (or include)? =
Not as easy as it used to be! Go into the Media Library. Choose an image you want to exclude and click on it and notice your address bar: "/wp-admin/media.php?action=edit&attachment_id=353". Therefore, `[satellite exclude=353]`

= What sizes can my thumbnails be? =
For Wordpress Image Gallery the max is 100, for the Custom Galleries the max is 150 pixels. The thumbnail must be at least 10 pixels.

= How do I show multiple galleries on a single page in a tabbed view? =
If you have the premium edition and multiple custom galleries setup do this `[satellite gallery=5,3,4,8]` and they will display in that order

= The slideshow loads a little funky, I fear it's the theme =
With premium edition, you can load the plugin after the theme loads by using the splash screen `[satellite gallery=2 splash=on]`


== Screenshots ==
1. Slideshow Satellite with bottom thumbnails
2. Slideshow Satellite with "Full Right"

See slideshows in action on our Examples Site: http://c-pr.es/satellite
See the manual and videos and more: http://c-pr.es/satellite

== Changelog ==
= 2.5 =
* Fix tinymce plugin to have description and thumbnails on right
* Fullright Thumbs set by number columns not pixels

= 2.4 =
* PREMIUM RELEASE August 2015
* All premium capabilities are now added for everyone to use!
* Fixed all PHP 5.5 issues
* Added support for HVM

= 2.3.3 =
* Fixing needing to save gallery before it loads bug
* Fixing premium width on shortcode not required bug

= 2.3.2 =
* getimagesize Bug Fix
* Adding Post Types to Gallery options
* Fixing Thumb Right/Left
* Adding Thumb Right/Left to button
* bug with mysql_list_fields fix

= 2.2.5 =
* Fixed Transition = none bug
* Fixed tinyMCE dialog box insert slideshow bug
* Fixed Checkbox issue on custom gallery

= 2.2.4 =
* Ordering slides fixes incl. ajax update
* Fixed slide saving
* Adding Alt Text

= 2.2.3 =
* Fixed Save Gallery Bug
* Fixed Extendability Bug

= 2.2.2 =
= 2.2 =
* The Extendable Release - Support for Twenty Fourteen Theme
* Allows for other plugins to add functionality to Satellite
* Everyone can have multiple slideshows now
* [satellite play=off]
* Slideshows stop when you leave the browser window
* Fixing many bugs
* Config/Admin fixes and updates


= 2.1.3 =
* WP 3.6 Release - Support for Twenty Thirteen Theme
* Reuse existing images in your slideshows
* Fixes jQuery problem
* Updated Satellite website (new links)

= 2.1.2 =
* Add Stretch and Center
* Lightbox Readiness
* Timing issue with no caption
* Minor bug fixes

= 2.1 =
* Slideshow Themes Added: Infinite Scroll & Flipbooks
* Infinite Scrolls for Admin Slides
* Updated Admin Sections: Quick Edit & Gallery-Slide Connection
* jQuery IE 7&8 issue

= 2.0.2 =
* Fixed thickbox issue
* Fixed Twenty-Twelve Admin CSS issues
* Preloading upgraded for minimal images & quicker load time
* Premium added 'infocolor' capability to pair with 'infobackground'

= 2.0 =
* The Responsive release!! - Regular;Multi-Gallery;Full-Thumbs
* Resizing Images
* Watermarking Images (Premium Only)
* New Transition: None - good for flipbooks
* Preloading now works! Premium can change how many images preload
* Fix Clean Start
* Add Change Font per Gallery
* Adding More Ordering Options
* Adding Bulk Slide Updates - Resize & Watermark
* Fixing Delete Images - actually deletes now!
* Making PHP not "necessary" for CSS

= 1.3.3 =
* Adding back in Shortcode Requirement
* Fixing Captions on the Right
* Adding Navigation Opacity
* Adding "Clean Start" for hover effects
* Adding always show play/pause button
* Kanji & Russian & Multilingual Support! UTF8 titles and descriptions

= 1.3.2 =
* Added More Gallery capability for call to action
* Navigation hidden to start
* Added more unique caption functionality to Galleries
* Added transparency capabilities
* Added an extra fade capability, "Fade Empty", and changed name of prior to "Fade Blend"
* Fixed captions to display through animation
* Added splash and load through AJAX for premium
* Added multiple gallery options when comma-delimited

= 1.2 =
* Added Galleries
* Added Bulk Image Upload
* Fixed Transparent Caption Backgrounds
* Added Text on the Right for Galleries
* Full Left & Full Right on General Config
* Slides no pause when image is clicked

= 1.1.4 = 
* Fixing for Wordpress 3.4
* Premium /pro/ directory copying feature for Automatic Updates
* Fixes for Full-Right Scrolling
* Fix all PHP errors

= 1.1 = 
* Added Caption Hover!
* Added Random capability
* Toggle Requirement of shortcode to load js and css
* Added multiple title sizing including 'Hidden'
* Minor bug fixes including Directory Separator
* Added Premium edition notifier of new versions

= 1.02 =
* Created easy out for tinyMCE bug
* Fixed some minor thumbnail issues
* Cleaned up Config Page
* Added more Caption options
* Update database to varchar textlocation
* "No link" fix
* Navigation Arrows push out
 
= 1.0 =
* Initial Plugin Release using a customized ZURB Orbit javascript slideshow
* Created easy one-click transition from Slideshow Gallery Pro
* Took best ideas in the world, created some more, and added some rad spice.

== Upgrade Notice ==

= 1.3.3 =
There may be some ordering of custom gallery slides you'll want to reconfirm. We had to rename the order column of the slides table for MySQL requirements.
