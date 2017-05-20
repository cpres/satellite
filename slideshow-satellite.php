<?php
/*
Plugin Name: Slideshow Satellite
Plugin URI: http://c-pr.es/satellite
Author: C- Pres
Author URI: http://c-pr.es
Description: Responsive display for all your photo needs. Customize to your hearts content.
Version: 2.4
*/
define('SATL_VERSION', '2.4');
$uploads = wp_upload_dir();
if (!defined('SATL_PLUGIN_BASENAME'))
    define('SATL_PLUGIN_BASENAME', plugin_basename(__FILE__));
if (!defined('SATL_PLUGIN_NAME'))
    define('SATL_PLUGIN_NAME', trim(dirname(SATL_PLUGIN_BASENAME), '/'));
if (!defined('SATL_PLUGIN_DIR'))
    define('SATL_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . SATL_PLUGIN_NAME);
if (!defined('SATL_PLUGIN_URL'))
    define('SATL_PLUGIN_URL', WP_PLUGIN_URL . '/' . SATL_PLUGIN_NAME);
if (!defined('SATL_UPLOAD_DIR'))
    define('SATL_UPLOAD_DIR', $uploads['basedir'] . '/' . SATL_PLUGIN_NAME);
if (!defined('SATL_UPLOAD_URL'))
    define('SATL_UPLOAD_URL', $uploads['baseurl'] . '/' . SATL_PLUGIN_NAME);
if (!defined('SATL_UPLOADPRO_DIR'))
    define('SATL_UPLOADPRO_DIR', SATL_UPLOAD_DIR . '/pro/');
if (!defined('SATL_PLUGINPRO_DIR'))
    define('SATL_PLUGINPRO_DIR', SATL_PLUGIN_DIR . '/pro/');
if (!file_exists(SATL_PLUGINPRO_DIR))
    define('SATL_PRO', false);
else
    define('SATL_PRO', true);

require_once SATL_PLUGIN_DIR . '/slideshow-satellite-plugin.php';
require_once SATL_PLUGIN_DIR . '/slideshow-satellite-premium.php';

class Satellite extends SatellitePlugin
{
    function __construct()
    {
        $url = explode("&", $_SERVER['REQUEST_URI']);
        $this->url = $url[0];

        $this->register_plugin('slideshow-satellite', __FILE__);

        //WordPress action hooks
        $this->add_action('admin_menu');
        $this->add_action('admin_head');
        $this->add_action('admin_notices');

        //WordPress filter hooks
        if ($this->get_option('satwiz') != "N") {
            $this->add_filter('mce_buttons');
            $this->add_filter('mce_external_plugins');
            /*$this -> add_filter( 'ckeditor_plugin');
            $this -> add_filter( 'ckeditor_buttons');*/

            $this->add_action('admin_print_footer_scripts', 'htmlmce_add_quicktags', 100);
        }
        $this->add_filter('plugin_action_links', 'add_satl_settings_link', 10, 2);

        add_shortcode('satellite', array($this, 'embed'));
        add_shortcode('gpslideshow', array($this, 'embed'));
        if ($this->get_option('embedss') == "Y") {
            add_shortcode('slideshow', array($this, 'embed'));
        }
        if (class_exists('SatellitePremium')) {
            $satlp = new SatellitePremium;
            register_activation_hook(__FILE__, array(&$satlp, 'prem_activate_plugin'));
        }

    }

    function my_action_javascript()
    {
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                function showSatellite(id) {
                    var data = {
                        action: 'my_action',
                        slideshow: id
                    };
                    $.post(ajaxurl, data, function (response) {
                        alert('Got this from the server: ' + response);
                    });
                }
            }
        </script>
    <?php
    }

    function admin_menu()
    {
        add_menu_page(__('Satellite', SATL_PLUGIN_NAME), __('Satellite', SATL_PLUGIN_NAME), $this->get_option('manager'), "satellite", array($this, 'admin_settings'), SATL_PLUGIN_URL . '/images/icon.png');
        $this->menus['satellite'] = add_submenu_page("satellite", __('Configuration', SATL_PLUGIN_NAME), __('Configuration', SATL_PLUGIN_NAME), $this->get_option('manager'), "satellite", array($this, 'admin_settings'));
        $this->menus['satellite-galleries'] = add_submenu_page("satellite", __('Manage Galleries', SATL_PLUGIN_NAME), __('Manage Galleries', SATL_PLUGIN_NAME), $this->get_option('manager'), "satellite-galleries", array($this, 'admin_galleries'));
        $this->menus['satellite-slides'] = add_submenu_page("satellite", __('Manage Slides', SATL_PLUGIN_NAME), __('Manage Slides', SATL_PLUGIN_NAME), $this->get_option('manager'), "satellite-slides", array($this, 'admin_slides'));

        add_action('admin_head-' . $this->menus['satellite'], array($this, 'admin_head_gallery_settings'));
    }

    function admin_head()
    {
        $this->render('head', false, true, 'admin');
    }

    function admin_head_gallery_settings()
    {
        add_meta_box('submitdiv', __('Save Settings', SATL_PLUGIN_NAME), array($this->Metabox, "settings_submit"), $this->menus['satellite'], 'side', 'core');
        add_meta_box('generaldiv', __('General Settings', SATL_PLUGIN_NAME), array($this->Metabox, "settings_general"), $this->menus['satellite'], 'normal', 'core');
        add_meta_box('linksimagesdiv', __('Links &amp; Images', SATL_PLUGIN_NAME), array($this->Metabox, "settings_linksimages"), $this->menus['satellite'], 'normal', 'core');
        add_meta_box('stylesdiv', __('Appearance &amp; Styles', SATL_PLUGIN_NAME), array($this->Metabox, "settings_styles"), $this->menus['satellite'], 'normal', 'core');
        add_meta_box('thumbsdiv', __('Thumbnail Settings', SATL_PLUGIN_NAME), array($this->Metabox, "settings_thumbs"), $this->menus['satellite'], 'normal', 'core');
        add_meta_box('posttypediv', __('Post Type Settings', SATL_PLUGIN_NAME), array($this->Metabox, "settings_posttype"), $this->menus['satellite'], 'normal', 'core');
        add_meta_box('advanceddiv', __('Advanced Settings', SATL_PLUGIN_NAME), array($this->Metabox, "settings_advanced"), $this->menus['satellite'], 'normal', 'core');
        if (SATL_PRO) {
            add_meta_box('prodiv', __('Premium Features', SATL_PLUGIN_NAME), array($this->Metabox, "settings_pro"), $this->menus['satellite'], 'normal', 'core');
        }
        // Satellite Extendable!
        apply_filters('satl_add_menu', $this->menus['satellite']);

        do_action('do_meta_boxes', $this->menus['satellite'], 'normal');
        do_action('do_meta_boxes', $this->menus['satellite'], 'side');

    }

    function admin_notices()
    {
        $this->check_uploaddir();
        $this->check_sgprodir();

        if (!empty($_GET[$this->pre . 'message'])) {
            $msg_type = (!empty($_GET[$this->pre . 'updated'])) ? 'msg' : 'err';
            $render_method = 'render_' . $msg_type;
            call_user_func(array('SatellitePlugin', $render_method), $_GET[$this->pre . 'message']);
        }
    }

    function mce_buttons($buttons)
    {
        array_push($buttons, "separator", "gallery");
        return $buttons;
    }

    function mce_external_plugins($plugins)
    {
        $plugins['gallery'] = SATL_PLUGIN_URL . '/js/tinymce/editor_plugin.js';
        return $plugins;
    }

    function ckeditor_plugin($plugins)
    {
        $plugins['gallery'] = SATL_PLUGIN_URL . '/js/ckeditor/';
        return $plugins;
    }

    function ckeditor_buttons($buttons)
    {
        array_push($buttons, "separator", "gallery");
        return $buttons;
    }

    function htmlmce_add_quicktags()
    {
        ?>
        <script type="text/javascript">
            if (typeof QTags != 'undefined') {
                QTags.addButton('content_gallery', 'satellite', '\n[satellite gallery=1 caption=on auto=on thumbs=on]');
            }
        </script>
    <?php
    }
    /**
     **** DEPRECATED - USE do_shortcode() instead ***
     * 
     * @global type $wpdb
     * @param type $output
     * @param type $post_id
     * @param type $exclude
     * @param type $include
     * @param type $custom
     * @param type $gallery
     * @param type $width
     * @param type $height
     * @return type 
     */
    function slideshow($output = true, $post_id = null, $exclude = null, $include = null, $custom = null, $gallery = null, $width = null, $height = null)
    {
        $this->satellite_counter();
        $this->resetTemp();
        $args = func_get_args();
        global $wpdb;
        $post_id_orig = $post->ID;
        if (((!empty($width)) || (!empty($height))) && SATL_PRO) {
            require SATL_PLUGIN_DIR . '/pro/custom_sizing.php';
        }

        if (!empty($post_id) && $post = get_post($post_id)) {
            if ($attachments = get_children("post_parent=" . $post->ID . "&post_type=attachment&post_mime_type=image&orderby=menu_order ASC, ID ASC")) {
                $content = $this->exclude_ids($attachments, $exclude, $include);
            }
        } elseif ((!empty($custom) || (!empty($gallery)))) {
            $gallery = ($custom) ? $custom : $gallery;
            $ordertopic = (isset($_GET['orderby'])) ? $_GET['orderby'] : 'slide_order';
            $slides = $this->Slide->find_all(array('section' => (int)stripslashes($gallery)), null, array($ordertopic, "ASC"));
            $this->slidenum = count($slides);

            if ($this->get_option('transition_temp') == "OM") {
                $content = $this->render('multislider', array('slides' => $slides, 'frompost' => false), false, 'pro');
            } else {
                $content = $this->render('default', array('slides' => $slides, 'frompost' => false), false, 'orbit');
            }
        } else {
            $slides = $this->Slide->find_all(null, null, array('slide_order', "ASC"));
            $this->slidenum = count($slides);

            if ($this->get_option('transition_temp') == "OM") {
                $content = $this->render('multislider', array('slides' => $slides, 'frompost' => false), false, 'pro');
            } else {
                $content = $this->render('default', array('slides' => $slides, 'frompost' => false), false, 'orbit');
            }
        }
        $post->ID = $post_id_orig;
        if ($output) {
            echo $content;
        } else {
            return $content;
        }
    }

    /**
     * SimpleEmbed - Boolean only
     * @param type $embed - The passed embed variable
     * @param type $option - The option argument for update_option
     * @param type $need - What is needed in order to update the option; ex. "on"
     * @param type $get - What that value of need SHOULD be; ex. true
     */
    public function parseSimpleEmbed($embed, $option, $need, $get)
    {
        if (!empty($embed)) {
            if ($embed == $need)
                $this->update_option($option, $get);
        }

    }

    public function embed($atts = array(), $content = null)
    {
        //global variables
        global $wpdb;

        $this->satellite_counter();
        $defaults = array();
        $setDefault = array('post_id', 'exclude', 'include', 'custom', 'gallery', 'caption', 'auto', 'w', 'h', 'nolink',
            'slug', 'thumbs', 'align', 'nav', 'transition', 'display', 'random', 'splash', 'background',
            'infobackground', 'infocolor', 'autospeed', 'animspeed', 'play');
        foreach ($setDefault as $d) {
            $defaults[$d] = null;
        }
        $r = shortcode_atts($defaults, $atts);

        $this->resetTemp();
        $align = stripslashes($r['align']);

        /** display is only used to fake a satellite embed ***/
        if (!empty ($display)) {
            if ($display == "off") {
                return null;
            }
        }

        if (!empty($caption)) {
            if (($this->get_option('information') == 'Y') && ($r['caption'] == 'off')) {
                $this->update_option('information_temp', 'N');

            } elseif (($this->get_option('information') == 'N') && ($r['caption'] == 'on')) {
                $this->update_option('information_temp', 'Y');
            }
            if (($this->get_option('orbitinfo') == 'Y') && ($r['caption'] == 'off')) {
                $this->update_option('orbitinfo_temp', 'N');

            } elseif (($this->get_option('orbitinfo') == 'N') && ($r['caption'] == 'on')) {
                $this->update_option('orbitinfo_temp', 'Y');
            }
        }
        if (!empty($r['thumbs'])) {
            if (($this->get_option('thumbnails') == 'Y') && ($r['thumbs'] == 'off')) {
                $this->update_option('thumbnails_temp', 'N');
            } elseif (($this->get_option('thumbnails') == 'N') && ($r['thumbs'] == 'on')) {
                $this->update_option('thumbnails_temp', 'Y');
            } elseif ($r['thumbs'] == "fullright") {
                $this->update_option('thumbnails_temp', 'FR');
            } elseif ($r['thumbs'] == "fullleft") {
                $this->update_option('thumbnails_temp', 'FL');
            }
        }

        if (!empty($r['random'])) { // update random in db options
            if (($this->get_option('random') == 'off' || $this->get_option('random') == null) && ($r['random'] == 'on')) {
                $this->update_option('random', 'on');
            } elseif (($this->get_option('random') == 'on') && ($r['random'] == 'off')) {
                $this->update_option('random', 'off');
            }
        }

        if (!empty($r['transition'])) {
            if (($this->get_option('transition') != 'FE') && ($r['transition'] == 'fade-empty')) {
                $this->update_option('transition_temp', 'FE');
            } elseif (($this->get_option('transition') != 'FB') && ($r['transition'] == 'fade-blend')) {
                $this->update_option('transition_temp', 'FB');
            } elseif (($this->get_option('transition') != 'OHS') && ($r['transition'] == 'vertical-slide')) {
                $this->update_option('transition_temp', 'OVS');
            } elseif (($this->get_option('transition') != 'OHS') && ($r['transition'] == 'horizontal-slide')) {
                $this->update_option('transition_temp', 'OHS');
            } elseif (($this->get_option('transition') != 'OHP') && ($r['transition'] == 'horizontal-push')) {
                $this->update_option('transition_temp', 'OHP');
            } elseif (($this->get_option('transition') != 'OM') && ($r['transition'] == 'orbit-multi')) {
                $this->update_option('transition_temp', 'OM');
            } elseif (($this->get_option('transition') != 'N') && ($r['transition'] == 'none')) {
                $this->update_option('transition_temp', 'N');
            }
        }
        if (!empty($r['auto'])) {
            if (($this->get_option('autoslide') == 'Y') && ($r['auto'] == 'off')) {
                $this->update_option('autoslide_temp', 'N');
            } elseif (($this->get_option('autoslide') == 'N') && ($r['auto'] == 'on')) {
                $this->update_option('autoslide_temp', 'Y');
            }
        } elseif ($this->get_option('autoslide') == 'Y') {
            $this->update_option('autoslide_temp', 'Y');
        }
        $this->parseSimpleEmbed($r['splash'], 'splash', 'on', true);
        $this->parseSimpleEmbed($r['nolink'], 'nolinker', true, true);
        $this->parseSimpleEmbed($r['play'], 'play', "off", true);

        /******** PRO ONLY **************/
        if (SATL_PRO) {
            $custom_sizing = SATL_PLUGIN_DIR . '/pro/custom_sizing.php';
            if (file_exists($custom_sizing)) {
                $w = isset($r['w']) ? $r['w'] : null;
                $w = isset($r['width']) ? $r['width'] : $w;
                $h = isset($r['h']) ? $r['h'] : null;
                $h = isset($r['height']) ? $r['height'] : $w;
                require $custom_sizing;
            }
        }
        /******** END PRO ONLY **************/
        if (!empty($r['nocaption'])) {
            $this->update_option('information', 'N');
            $this->update_option('orbitinfo', 'N');
        }
        if (!empty($r['gallery'])) { // custom is deprecated as of version 1.2
            $gallery = $r['gallery'];
            $multigallery = preg_match("[\,]", $gallery);
            $data = $this->Gallery->loadData($gallery);
            $this->log_me("multi: ".$multigallery);
//            $this->log_me($data);
            $slides = $this->getSlidesArray($data, $gallery,$multigallery);

            $this->slidenum = count($slides);

            /* THIS IS WHERE THE VIEW MAGIC HAPPENS */
            $view = $this->getCustomView($data, $multigallery, $gallery);
            $this->log_me('View for this embed is: ' . $view);
            switch ($view) {
                case 'multigallery':
                  
                    $gallery_array = explode(',', $gallery);
//                    $first_gallery = $gallery_array[0];
                    $content = $this->render('galleries', array('slides' => $slides, 'frompost' => false, 'galleries' => $gallery_array), false, 'premium');
                    break;
                case 'splash':
                    $content = $this->render('splash', array('slides' => $slides, 'frompost' => false), false, 'orbit');
                    break;
                case 'fullthumb':
                    $content = $this->render('fullthumb', array('slides' => $slides, 'frompost' => false), false, 'orbit');
                    break;
                case 'infinite':
                    $this->run_angular();
                    //add_action( 'wp_head', 'run_angular' );
                    $content = $this->render('infinite', array('slides' => $slides, 'frompost' => false), false, 'custom');
                    break;
                default:
                    if (has_filter('satl_render_view')) {
                        $content = apply_filters('satl_render_view', array($view, $slides));
                    }
                    if (!$content || is_array($content) ) {
                        $content = $this->render('default', array('slides' => $slides, 'frompost' => false), false, 'orbit');
                    }

                    break;

            }
        } else { // from post "frompost => true"
            global $post;
            $post_id_orig = $post->ID;

            if (empty($slug)) {
                $pid = (empty($post_id)) ? $post->ID : $post_id;
            } else {
                $page = get_page_by_path('$slug');
                if ($page) {
                    $pid = $page->ID;
                } else {
                    $page = get_page_by_path($slug, '', 'post');
                    if ($page) {
                        $pid = $page->ID;
                    } else {
                        $pid = (empty($post_id)) ? $post->ID : $post_id;
                    }
                }
            }
            if (!empty($pid)) {
                if ($attachments = get_children("post_parent=" . $pid . "&post_type=attachment&post_mime_type=image&orderby=menu_order ASC, ID ASC")) {
                    if ($this->get_option('random') == "on") {
                        shuffle($attachments);
                    }
                    $content = $this->exclude_ids($attachments, $exclude, $include);
                }
            }
        }

        return $content;
    }

    public function getCustomView($data, $multigallery, $gallery)
    {
        $theme = $data->theme;
        if ($theme && $theme != 'standard' && $theme != 'flipbook')
            return $theme;
        elseif (SATL_PRO && $multigallery)
            return 'multigallery';
        elseif (SATL_PRO && $this->get_option('splash'))
            return 'splash';
        elseif ($this->get_option('thumbnails_temp') == "FR" || $this->get_option('thumbnails_temp') == "FL")
            return 'fullthumb';
        else
            return 'default';
    }
    
    /**
     *
     * @param int $gal - Gallery ID
     * @param int $mg - multigallery or not
     * @return array 
     */
    private function getSlidesArray($data,$gal,$mg) {
      if ($mg) {
          $gallery_array = explode(',', $gal);
          $first_gallery = $gallery_array[0];
          $slides = $this->Slide->find_all(array('section' => (int)stripslashes($first_gallery)), null, array('slide_order', "ASC"));
      } else {
          $gal = intVal($gal);
          if ($data->source == 'satellite' || empty($data->source)) {
            $slides = $this->Slide->find_all(array('section' => (int)stripslashes($gal)), null, array('slide_order', "ASC"));
          } else {
            error_log('post type slides');
            $slides = $this->processPostTypeSlides($data->source,$gal);
          }
      }

      if ($this->get_option('random') == "on") {
          shuffle($slides);
      }
      
      return $slides;
    }
    /**
     *
     * Creates a Slide object array for the post type
     * @param type $postType - Post Type
     * @param int $gal - Gallery
     * @return SatelliteSlide 
     */
    function processPostTypeSlides($postType,$gal)
    {
      $args = array(
            'orderby'       => 'post_title',
            //'order'       => $post_order,
            'post_type'     => $postType,
            'post_status'   => 'publish',
            //'paged'			  => $paged,
            'nopaging'		  => true,
        );

      $my_query = new WP_Query();
      $query_posts = $my_query->query($args);
      $slide_arr = Array();
      $pTConfig = $this->get_option('PostType');

      while ( $my_query->have_posts() ) : 
        
        // Each image saved is a Wordpress Post in nm-userfiles tied to a post
        $my_query->the_post();
        if (has_post_thumbnail()) {
          $slide = new SatelliteSlide;
          $slide->id = get_the_ID();
          error_log(get_attachment_link(get_the_ID()));
          $large_img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
          $slide->title = get_the_title();
          $slide->description = get_the_content();
          $slide->img_url = $large_img[0];
          $slide->section = $gal;
          $slide->more = false;
          $slide->textlocation = "D";
          $slide->img_width = $large_img[1];
          $slide->img_height = $large_img[2];
          if ($pTConfig['post_link'] && $satl_link = get_post_custom_values('satl_link')) {
            $slide->link = $satl_link[0];
          } else {
            $slide->link = get_the_permalink();
          }
          $slide->uselink = "Y";
          // Default is allow click through, if post_clickthru is true, only logged in users can click through
          if (!is_user_logged_in() && $pTConfig['post_clickthru']) {
            $slide->uselink = false;
          }

          $slide_arr[] = $slide;
        }

      endwhile;

      return $slide_arr;
    }

    function satellite_counter()
    {
      global $satellite_init_ok;
      if (!$satellite_init_ok) {
        $satellite_init_ok = 1;
      } else {
        $satellite_init_ok = $satellite_init_ok + 1;
      }
    }

    function resetTemp()
    {
        // This section allows for using _temp variable only (esp in gallery.php)
        if ($this->get_option('information') == 'Y') {
            $this->update_option('information_temp', 'Y');
        } elseif ($this->get_option('information') == 'N') {
            $this->update_option('information_temp', 'N');
        }
        if ($this->get_option('orbitinfo') == 'Y') {
            $this->update_option('orbitinfo_temp', 'Y');
        } elseif ($this->get_option('orbitinfo') == 'N') {
            $this->update_option('orbitinfo_temp', 'N');
        }
        if ($this->get_option('thumbnails') == 'Y') {
            $this->update_option('thumbnails_temp', 'Y');
            if ($this->get_option('thumbposition') == 'FR') {
                $this->update_option('thumbnails_temp', 'FR');
            } elseif ($this->get_option('thumbposition') == 'FL') {
                $this->update_option('thumbnails_temp', 'FL');
            }
        } elseif ($this->get_option('thumbnails') == 'N') {
            $this->update_option('thumbnails_temp', 'N');
        }
        if ($this->get_option('autoslide') == 'Y') {
            $this->update_option('autoslide_temp', 'Y');
        } elseif ($this->get_option('autoslide') == 'N') {
            $this->update_option('autoslide_temp', 'N');
        }
        if ($this->get_option('transition') == 'FE') {
            $this->update_option('transition_temp', 'FE');
        } elseif ($this->get_option('transition') == 'FB') {
            $this->update_option('transition_temp', 'FB');
        } elseif ($this->get_option('transition') == 'OVS') {
            $this->update_option('transition_temp', 'OVS');
        } elseif ($this->get_option('transition') == 'OHS') {
            $this->update_option('transition_temp', 'OHS');
        } elseif ($this->get_option('transition') == 'OHP') {
            $this->update_option('transition_temp', 'OHP');
        } elseif ($this->get_option('transition') == 'N') {
            $this->update_option('transition_temp', 'N');
        } elseif ($this->get_option('transition') == 'OM') {
            $this->update_option('transition_temp', 'OM');
        }
        if ($this->get_option('random') != null) {
            $this->update_option('random', null);
        }

        $style = array();
        $style = $this->get_option('styles');
        $style['align'] = "none";
        $this->update_option('styles', $style);

        // RESET non configurable options - reset parseSimpleEmbed
        $this->update_option('splash', false);
        $this->update_option('nolinker', false);
        $this->update_option('play', false);
    }

    function exclude_ids($attachments, $exclude, $include)
    {
        if (!empty($exclude)) {
            $exclude = array_map('trim', explode(',', $exclude));
            /*			echo("<script type='text/javascript'>alert('exclude! ".$exclude[0]."');</script>");*/
            foreach ($attachments as $id => $attachment) {
                if (in_array($id, $exclude)) {
                    unset($attachments[$id]);
                }
            }
        } elseif (!empty($include)) {
            $include = array_map('trim', explode(',', $include));
            /*			echo("<script type='text/javascript'>alert('include!".$include[0]."');</script>");*/
            foreach ($attachments as $id => $attachment) {
                if (in_array($id, $include)) {
                } else {
                    unset($attachments[$id]);
                }
            }
        }
        if ($this->get_option('transition_temp') == "OM") {
            $content = $this->render('multislider', array('slides' => $attachments, 'frompost' => true), false, 'pro');
        } elseif ($this->get_option('thumbnails_temp') == "FR" ||
            $this->get_option('thumbnails_temp') == "FL"
        ) {
            $content = $this->render('fullthumb', array('slides' => $attachments, 'frompost' => true), false, 'orbit');
        } else {
            $content = $this->render('default', array('slides' => $attachments, 'frompost' => true), false, 'orbit');
        }
        return $content;
    }

    function admin_slides()
    {
        $method = (isset($_GET['method'])) ? $_GET['method'] : null;
        switch ($method) {
            case 'delete'            :
                if (!empty($_GET['id'])) {
                    if ($this->Slide->delete($_GET['id'])) {
                        $msg_type = 'message';
                        $message = __('Slide has been removed', SATL_PLUGIN_NAME);
                    } else {
                        $msg_type = 'error';
                        $message = __('Slide cannot be removed', SATL_PLUGIN_NAME);
                    }
                } else {
                    $msg_type = 'error';
                    $message = __('No slide was specified', SATL_PLUGIN_NAME);
                }

                $this->redirect($this->url, $msg_type, $message);
                break;
            case 'single'            :
                if (!empty($_POST['section'])) {
                    $msg_type = 'message';
                    $single = $_POST['section'];
                    $message = __('You have successfully updated your view to ' . $single, SATL_PLUGIN_NAME);
                    if ($single != "All") {
                        $slides = $this->Slide->find_all(array('section' => (int)stripslashes($single)), null, array('slide_order', "ASC"));
                        $this->url = $this->url . "&single={$single}";
                    } else {
                        $this->url = $this->url;
                    }
                } else {
                    $msg_type = 'error';
                    $message = __('No section was specified', SATL_PLUGIN_NAME);
                }
                $this->redirect($this->url, $msg_type, $message);
                break;
            case 'save'                :
                if (!empty($_POST)) {
                    if ($this->Slide->save($_POST, true)) {
                        $message = __('Slide has been saved', SATL_PLUGIN_NAME);
                        $this->redirect($this->url, "message", $message);
                    } else {
                        $this->render('slides/save', false, true, 'admin');
                    }
                } else {
                    $this->Db->model = $this->Slide->model;
                    if (isset($_GET['id'])) {
                        $this->Slide->find(array('id' => $_GET['id']));
                    }
                    $this->render('slides/save', false, true, 'admin');
                }
                break;
            case 'mass'                :
                if (!empty($_POST['action'])) {
                    if (!empty($_POST['Slide']['checklist'])) {
                        switch ($_POST['action']) {
                            case 'delete'                :
                                foreach ($_POST['Slide']['checklist'] as $slide_id) {
                                    $this->Slide->delete($slide_id);
                                }

                                $message = __('Selected slides have been removed', SATL_PLUGIN_NAME);
                                $this->redirect($this->url, 'message', $message);
                                break;
                            case 'resize' :
                                $slide_ids = $this->getSlideFromPost($_POST['Slide']['checklist']);
                                foreach ($slide_ids as $slide_id) {
                                    $this->Slide->resizeById($slide_id);
                                }
                                $message = __('Selected slides have been resized', SATL_PLUGIN_NAME);
                                $this->redirect($this->url, 'message', $message);
                                break;
                            case 'quickedit':
                                $slide_ids = $this->getSlideFromPost($_POST['Slide']['checklist']);
                                $slide_titles = $this->getSlideFromPost($_POST['Slide']['title']);
                                $slide_galleries = $this->getSlideFromPost($_POST['Slide']['gallery']);
                                for ($i = 0; $i < count($slide_ids); $i++) {
                                    $this->Slide->quickSaveSlide($slide_ids[$i], $slide_titles[$i], $slide_galleries[$i]);
                                }
                                $message = __('Selected slides have been edited, quickly', SATL_PLUGIN_NAME);
                                $this->redirect($this->url, 'message', $message);
                                break;
                            case 'watermark' :
                                $slide_ids = $this->getSlideFromPost($_POST['Slide']['checklist']);
                                foreach ($slide_ids as $slide_id) {
                                    $this->Slide->watermarkById($slide_id);
                                }
                                $message = __('Selected slides have been watermarked', SATL_PLUGIN_NAME);
                                $this->redirect($this->url, 'message', $message);
                                break;

                        }
                    } else {
                        $message = __('No slides were selected', SATL_PLUGIN_NAME);
                        $this->redirect($this->url, "error", $message);
                    }
                } else {
                    $message = __('No action was specified', SATL_PLUGIN_NAME);
                    $this->redirect($this->url, "error", $message);
                }
                break;
            case 'order'            :
                $slides = $this->Slide->find_all(null, null, array('slide_order', "ASC"));
                $this->render('slides/order', array('slides' => $slides), true, 'admin');
                break;
            case 'copysgpro'                :
                $sgprodir = SATL_UPLOAD_DIR . '/../slideshow-gallery-pro/';
                SatelliteSlide::full_copy($sgprodir, SATL_UPLOAD_DIR);
                if ($this->is_empty_folder(SATL_UPLOAD_DIR)) {
                    $message = __('Sorry! Your files weren\'t able to be copied over.', SATL_PLUGIN_NAME);
                    $this->redirect($this->url, "error", $message);
                } else {
                    $message = __('Your files have been successfully copied!', SATL_PLUGIN_NAME);
                    $this->redirect($this->url, "message", $message);
                }
                break;
            default                    :
                //$data = $this -> lazyload('Slide');
                //$this -> render('slides/index', array('slides' => $data[$this -> Slide -> model], 'paginate' => $data['Paginate']), true, 'admin');
                $ordertopic = (isset($_GET['orderby'])) ? $_GET['orderby'] : 'modified';
                $orderdirection = ($ordertopic == 'modified') ? "DESC" : "ASC";
                $slides = $this->Slide->find_all(null, null, array($ordertopic, $orderdirection));
                $this->render('slides/index', array('slides' => $slides, null), true, 'admin');
                break;
        }
    }

    /*
     * @return array
     * $post is array or string
     */
    function getSlideFromPost($post)
    {
        if (is_array($post)) {
            foreach ($post as $slide_id) {
                $slide_array[] = $slide_id;
            }
        } else {
            $slide_array[] = $slide_id;
        }
        return $slide_array;
    }

    function admin_galleries()
    {
        $galleries = $this->Gallery->find_all(null, null, array('id', "ASC"));
        $method = (isset($_GET['method'])) ? $_GET['method'] : null;
        switch ($method) {
            case 'delete'            :
                if (!empty($_GET['id'])) {
                    if ($this->Gallery->delete($_GET['id'])) {
                        $msg_type = 'message';
                        $message = __('Gallery has been removed', SATL_PLUGIN_NAME);
                    } else {
                        $msg_type = 'error';
                        $message = __('Gallery cannot be removed', SATL_PLUGIN_NAME);
                    }
                } else {
                    $msg_type = 'error';
                    $message = __('No gallery was specified', SATL_PLUGIN_NAME);
                }

                $this->redirect($this->url, $msg_type, $message);
                break;
            case 'save'                :
                if (!empty($_POST)) {
//                  var_dump($_POST);die();
                    if ($this->Gallery->save($_POST, true)) {
                        if (!empty($_POST['images'])) {
                            if ($this->Slide->processImages($_POST['images'], $_POST['Gallery']['id'])) {
                                $message = __('Gallery and images have been saved', SATL_PLUGIN_NAME);
                            } else {
                                $message = __('Gallery has saved but image upload failed', SATL_PLUGIN_NAME);
                            }
                        } else {
                            $message = __('Gallery info has been saved', SATL_PLUGIN_NAME);
                        }
                        $this->redirect($this->url, "message", $message);
                    } else {
                        $this->render('galleries/save', false, true, 'admin');
                    }
                } else {
                    $this->Db->model = $this->Gallery->model;
                    if (isset($_GET['id'])){
                        $this->Gallery->find(array('id' => $_GET['id']));
                    }
                    $this->render('galleries/save', false, true, 'admin');
                }
                break;
            case 'mass'                :
                if (!empty($_POST['action'])) {
                    if (!empty($_POST['Gallery']['checklist'])) {
                        switch ($_POST['action']) {
                            case 'delete'                :
                                foreach ($_POST['Gallery']['checklist'] as $gallery_id) {
                                    $this->Gallery->delete($gallery_id);
                                }

                                $message = __('Selected galleries have been removed', SATL_PLUGIN_NAME);
                                $this->redirect($this->url, 'message', $message);
                                break;
                        }
                    } else {
                        $message = __('No galleries were selected', SATL_PLUGIN_NAME);
                        $this->redirect($this->url, "error", $message);
                    }
                } else {
                    $message = __('No action was specified', SATL_PLUGIN_NAME);
                    $this->redirect($this->url, "error", $message);
                }
                break;
            default                    :
                $data = $this->paginate('Gallery');
                $this->render('galleries/index', array('galleries' => $data[$this->Gallery->model], 'paginate' => $data['Paginate']), true, 'admin');
                break;
        }
    }

    function admin_newgallery()
    {
        if (!empty($_POST)) {
            if ($this->Gallery->save($_POST, true)) {
                if (!empty($_POST['images'])) {
                    $this->Slide->processImages($_POST['images']);
                    $message = __('Gallery and images have been saved', SATL_PLUGIN_NAME);
                } else {
                    $message = __('Gallery with no images has been saved', SATL_PLUGIN_NAME);
                }
                $this->redirect($this->url, "message", $message);
            } else {
                $this->render('galleries/save', false, true, 'admin');
            }
        } else {
            $this->Db->model = $this->Gallery->model;
            $this->Gallery->find(array('id' => $_GET['id']));
            $this->render('galleries/save', false, true, 'admin');
        }

    }

    function admin_settings()
    {
        if (!isset($_GET['method'])) {
            $_GET['method'] = "undefined";
        }
        switch ($_GET['method']) {
            case 'reset'            :
                global $wpdb;
                $query = "DELETE FROM `" . $wpdb->prefix . "options` WHERE `option_name` LIKE '" . $this->pre . "%';";

                if ($wpdb->query($query)) {
                    $message = __('All configuration settings have been reset to their defaults', SATL_PLUGIN_NAME);
                    $msg_type = 'message';
                    $this->render_msg($message);
                } else {
                    $message = __('Configuration settings could not be reset', SATL_PLUGIN_NAME);
                    $msg_type = 'error';
                    $this->render_err($message);
                }

                $this->redirect($this->url, $msg_type, $message);
                break;
            default                    :
                if (!empty($_POST)) {
                    foreach ($_POST as $pkey => $pval) {
                        $this->update_option($pkey, $pval);
                    }

                    $message = __('Configuration has been saved', SATL_PLUGIN_NAME);
                    $this->render_msg($message);
                }
                break;
        }

        $this->render('settings', false, true, 'admin');
    }

}

//initialize a Satellite object
$Satellite = new Satellite();
?>
