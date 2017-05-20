<?php

class SatellitePlugin 
{

    var $plugin_name;
    var $plugin_base;
    var $pre = 'Satellite';
    var $shortname = 'satl';
    var $debugging = false;
    var $errorlog = true;
    var $menus = array();
    private $_latestOrbit = 'jquery.orbit-1.3.1.js';
    private $_latestOrbitMin = 'orbit-min.js';
    var $cssfile = 'orbit-css.php';
    var $staticCSSFile = 'orbit-1.3.1.css';
    var $cssadmin = 'admin-styles.css';
    var $sections = array(
        'satellite' => 'satellite-slides',
        'settings' => 'satellite',
        'newgallery' => 'satellite-galleries',
    );
    var $helpers = array('Ajax','Config','Db','Form','Html','Image','Metabox','Premium','Version');
    var $models = array('Slide','Gallery');

    function register_plugin($name, $base) {
        $this->plugin_base = rtrim(dirname($base), '/');
        $this->initialize_classes();
        $this->initialize_options();

        if (function_exists('load_plugin_textdomain')) {
            $currentlocale = get_locale();
            if (!empty($currentlocale)) {
                $moFile = dirname(__FILE__) . "/languages/" . SATL_PLUGIN_NAME . "-" . $currentlocale . ".mo";
                if (@file_exists($moFile) && is_readable($moFile)) {
                    load_textdomain(SATL_PLUGIN_NAME, $moFile);
                }
            }
        }
        if ($this->debugging == true) {
            global $wpdb;
            $wpdb->show_errors();
            error_reporting(E_ALL); 
            @ini_set('display_errors', 1);
        }
        $this->add_action('wp_head', 'enqueue_scripts', 1);
        $this->add_action('admin_head', 'add_admin_styles');
        $this->add_action("admin_head", 'plupload_admin_head');
        $this->add_action('admin_init', 'admin_scripts');
        $this->add_filter('the_posts', 'conditionally_add_scripts_and_styles'); // the_posts gets triggered before wp_head
        $this->add_action("wp_head", 'print_styles');
        $this->add_action('wp_ajax_plupload_action', "g_plupload_action");
        $this->add_action('wp_ajax_satl_order_slides', 'satl_ajax_reorder');
        $this->add_action('wp_ajax_slideshow_tinymce', 'ajax_tinymce', 10, 1);
        
        return true;
    }
    function add_admin_styles() {
        $adminStyleUrl = SATL_PLUGIN_URL . '/css/' . $this -> cssadmin . '?v=' . SATL_VERSION;
        $page = (isset($_GET['page'])) ? $_GET['page'] : null;
        wp_register_style(SATL_PLUGIN_NAME . "_adstyle", $adminStyleUrl);
        wp_enqueue_style(SATL_PLUGIN_NAME . "_adstyle");
        if ($page == "satellite-slides" || $page == "satellite-galleries") {
//          wp_enqueue_style('bootstrap',SATL_PLUGIN_URL. '/css/bootstrap.min.css?v=' . SATL_VERSION);
        }

    }
    
    function print_styles() {

        if ($this->get_option('play')) :

            echo '<style type="text/css">';
            echo '  div.timer { opacity: 0; }';
            echo '</style>';

        endif;

    }

    function conditionally_add_scripts_and_styles($posts){
            if (empty($posts)) return $posts;
            $loadGoogleFonts = false;

            $shortcode_found = false; // use this flag to see if styles and scripts need to be enqueued
            
            foreach ($posts as $post) {
                if (    ( stripos($post->post_content, '[gpslideshow') !== false ) ||
                        ( stripos($post->post_content, '[satellite') !== false) ||
                        ( stripos($post->post_content, '[slideshow') !== false && $this->get_option('embedss') == "Y" )
                ) {
                        $shortcode_found = true; // bingo!
                        $pID = $post->ID;
                        break;
                }
            }
            // If this page has the embed or under Advanced Setting we have Shortcode Requirement = Off
            if ($shortcode_found || $this->get_option('shortreq') == 'N') {
                        
            $satlStyleFile = SATL_PLUGIN_DIR . '/css/' . $this -> cssfile;
            $satlStyleUrl = SATL_PLUGIN_URL . '/css/' . $this -> cssfile . '?v=' . SATL_VERSION . '&amp;pID=' . $pID;
            $satlStyleStaticUrl = SATL_PLUGIN_URL . '/css/' . $this -> staticCSSFile . '?v=' . SATL_VERSION;
            
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
                $satlStyleUrl = str_replace("http:", "https:", $satlStyleUrl);
            }

            if (file_exists($satlStyleFile)) {
                if ($styles = $this->get_option('styles')) {
                  foreach ($styles as $skey => $sval) {
                      $satlStyleUrl .= "&amp;" . $skey . "=" . urlencode($sval);
                  }
                }

                if ( class_exists( 'SatellitePremiumHelper' ) ) {
                    $width_temp = $this->get_option('width_temp');
                    $height_temp = $this->get_option('height_temp');
                    $align = $this->get_option('align');
                    $nav_temp = $this->get_option('nav_temp');
                    $background = $this->get_option('background');
                    $infobackground = $this->get_option('infobackground');
                    $infocolor = $this->get_option('infocolor');

                    $satlStyleUrl .= $this->Premium->addProStyling($width_temp,'width_temp',$pID);
                    $satlStyleUrl .= $this->Premium->addProStyling($height_temp,'height_temp',$pID);
                    $satlStyleUrl .= $this->Premium->addProStyling($background,'background',$pID);
                    $satlStyleUrl .= $this->Premium->addProStyling($align,'align',$pID);
                    $satlStyleUrl .= $this->Premium->addProStyling($infobackground,'infobackground',$pID);
                    $satlStyleUrl .= $this->Premium->addProStyling($infocolor,'infocolor',$pID);
                    $satlStyleUrl .= $this->Premium->addProStyling($nav_temp,'nav',$pID);
                }
                
                wp_register_style(SATL_PLUGIN_NAME . "_style", $satlStyleUrl);
                wp_register_style(SATL_PLUGIN_NAME . "_styleStatic", $satlStyleStaticUrl);
                
            }
            if ($loadGoogleFonts) {
                $this->load_fonts();
                //wp_register_style(SATL_PLUGIN_NAME.'_googleFonts', 'http://fonts.googleapis.com/css?family=Cabin:400,600,400italic,600italic|Cabin+Condensed|Scada:400,700');
                //wp_register_style(SATL_PLUGIN_NAME.'_googleFonts', 'http://fonts.googleapis.com/css?family=Cabin+Condensed');
                //wp_enqueue_style( SATL_PLUGIN_NAME.'_googleFonts');

            }

                    // enqueue here
            wp_enqueue_style(SATL_PLUGIN_NAME . "_styleStatic");
            wp_enqueue_style(SATL_PLUGIN_NAME . "_style");
            $advanced = $this->get_option('Advanced');
            $orbitjss = ($advanced['debug']) ? $this->_latestOrbit : $this->_latestOrbitMin;

            wp_enqueue_script(SATL_PLUGIN_NAME . "_script", '/' . PLUGINDIR . '/' . SATL_PLUGIN_NAME . '/js/' . $orbitjss,
                    array('jquery'),
                    SATL_VERSION);
                
        }
        return $posts;
    }
    
    function load_fonts() {
        wp_register_style(SATL_PLUGIN_NAME.'_googleFonts', 'http://fonts.googleapis.com/css?family=Cabin:400,600,400italic,600italic|Cabin+Condensed|Scada:400,700');
        wp_enqueue_style( SATL_PLUGIN_NAME.'_googleFonts' );
    }
    
	function ajax_tinymce() {
        $this->log_me('running tinymce off ajax');
		$this -> render('tinymce-modal', false, true, 'admin');
		
		exit();
		die();
	}

    function init_class($name = null, $params = array()) {
        if (!empty($name)) {
            $name = $this->pre . $name;
            if (class_exists($name)) {
                if ($class = new $name($params)) {
                    return $class;
                }
            }
        }
        $this->init_class('Country');
        return false;
    }

    function initialize_classes() {
        if (!empty($this->helpers)) {
            foreach ($this->helpers as $helper) {
                $hfile = dirname(__FILE__) . '/helpers/' . strtolower($helper) . '.php';
                if (file_exists($hfile)) {
                    require_once($hfile);
                    if (empty($this->{$helper}) || !is_object($this->{$helper})) {
                        $classname = $this->pre . $helper . 'Helper';
                        if (class_exists($classname)) {
                            $this->{$helper} = new $classname;
                        }
                    }
                }
            }
        }
        if (!empty($this->models)) {
            foreach ($this->models as $model) {
                $mfile = dirname(__FILE__)  . '/models/' . strtolower($model) . '.php';
                if (file_exists($mfile)) {
                    require_once($mfile);
                    if (empty($this->{$model}) || !is_object($this->{$model})) {
                        $classname = $this->pre . $model;

                        if (class_exists($classname)) {
                            $this->{$model} = new $classname;
                        }
                    }
                }
            }
        }
    }

    function initialize_options() {
      
        // Before the configuration page has ever been saved, slideshow relies on these
      
        $styles = array(
            'width' => "450",
            'height' => "300",
            'thumbheight' => "75",
            'thumbarea' => "275",
            'thumbareamargin' => "30",
            'thumbmargin' => "2",
            'thumbspacing' => "5",
            'thumbactive' => "#FFFFFF",
            'thumbopacity' => "70",
            'align' => "none",
            'border' => "1px solid #CCCCCC",
            'background' => "#000000",
            'infotitle' => "2",
            'infobackground' => "#000000",
            'infocolor' => "#FFFFFF",
            'playshow'  => "A",
            'nav_opacity' => "30",
            'navpush'   => "0",
            'infomin' => "Y"
        );
        $watermark = array(
            'watermarkloc' => 'BR'
        );
        $images = array(
            'imagesbox'   => 'T',
            'position'    => 'A',
            'resize'      => 1024,
            'pagelink'    =>  'S'
        );
        $preloader = array(
            'quantity'    => 1,
        );
        $advanced = array(
            'jquery'    => 0
        );
        $this->add_option('Watermark', $watermark);
        $this->add_option('Images', $images);
        $this->add_option('styles', $styles);
        $this->add_option('Preloader', $preloader);
        $this->add_option('Advanced', $advanced);
//        $this->add_option('Awesome', null);
        
        //General Settings
        $this->add_option('autospeed', 10);
        $this->add_option('autoslide', "Y");
        $this->add_option('fadespeed', 10);
        $this->add_option('captionlink', "N");
        $this->add_option('information', "Y");
        $this->add_option('infospeed', 10);
        $this->add_option('embedss', "Y");
        $this->add_option('nav_opacity', 30);
        $this->add_option('navhover', 70);
        $this->add_option('nolinker', false);
        $this->add_option('nolinkpage', 0);
        $this->add_option('responsive', 1);
        $this->add_option('satwiz', "Y");
        $this->add_option('shortreq', "Y");
        $this->add_option('showhover', "P");
        $this->add_option('splash', "N");
        $this->add_option('stldb_version', "1.0");
        $this->add_option('thumbnails', "N");
        $this->add_option('thumbposition', "bottom");
        $this->add_option('thumbscrollspeed', 5);
        $this->add_option('transition', "FB");
        $this->add_option('wpattach', "N");

        // Orbit Only
        $this->add_option('autospeed2', 5000);
        $this->add_option('duration', 700);
        $this->add_option('othumbs', "B");
        $this->add_option('bullcenter', "true");
        //Multi-ImageSlide
        $this->add_option('multicols', 3);
        $this->add_option('dropshadow', 'N');
        //Full Right / Left
        $this->add_option('thumbarea', 250);
        //Premium
        $this->add_option('custslide', 10);
        $this->add_option('keyboard', 'N');
        $this->add_option('manager', 'manage_options');
        $this->add_option('nav', "on");
        //$this->add_option('orbitinfo', 'Y');
        //$this->add_option('orbitinfo_temp', 'Y');
    }

    function render_msg($message = '') {
        $this->render('msg-top', array('message' => $message), true, 'admin');
    }

    function render_err($message = '') {
        $this->render('err-top', array('message' => $message), true, 'admin');
    }

    function redirect($location = '', $msgtype = '', $message = '') {
        $url = $location;
        $url = (isset($_GET['single'])) ? $url."&single=".$_GET['single'] : $url;
        if ($msgtype == "message") {
            $url .= '&' . $this->pre . 'updated=true';
        } elseif ($msgtype == "error") {
            $url .= '&' . $this->pre . 'error=true';
        }
        if (!empty($message)) {
            $url .= '&' . $this->pre . 'message=' . urlencode($message);
        }
        $url = (empty($url)) ? get_option('home') : $url;

        echo '<script type="text/javascript">';
        echo 'window.location = "'.$url.'"';
        echo '</script>';
        flush();
    }

    function paginate($model = null, $fields = '*', $sub = null, $conditions = null, $searchterm = null, $per_page = 10, $order = array('modified', "DESC")) {
        global $wpdb;

        if (!empty($model)) {
            global $paginate;
            $paginate = $this->vendor('Paginate');
            $paginate->table = $this->{$model}->table;
            $paginate->sub = (empty($sub)) ? $this->{$model}->controller : $sub;
            $paginate->fields = (empty($fields)) ? '*' : $fields;
            $paginate->where = (empty($conditions)) ? false : $conditions;
            $paginate->searchterm = (empty($searchterm)) ? false : $searchterm;
            $paginate->per_page = $per_page;
            $paginate->order = $order;
            $paging_page = (isset($_GET[$this->pre . 'page'])) ? $_GET[$this->pre . 'page'] : null;
            $data = $paginate->start_paging($paging_page);
            if (!empty($data)) {
                $newdata = array();
                foreach ($data as $record) {
                    $newdata[] = $this->init_class($model, $record);
                }
                $data = array();
                $data[$model] = $newdata;
                $data['Paginate'] = $paginate;
            }
            return $data;
        }
        return false;
    }

    function lazyload($model = null, $fields = '*', $sub = null, $conditions = null, $searchterm = null, $per_page = 10, $order = array('modified', "DESC")) {
        global $wpdb;

        if (!empty($model)) {
            global $lazyload;
            $lazyload = $this->vendor('Lazyload');
            $lazyload->table = $this->{$model}->table;
            $lazyload->sub = (empty($sub)) ? $this->{$model}->controller : $sub;
            $lazyload->fields = (empty($fields)) ? '*' : $fields;
            $lazyload->where = (empty($conditions)) ? false : $conditions;
            $lazyload->searchterm = (empty($searchterm)) ? false : $searchterm;
            $lazyload->per_page = $per_page;
            $lazyload->order = $order;
            $data = $lazyload->start_loading($_GET[$this->pre . 'page']);
            if (!empty($data)) {
                $newdata = array();
                foreach ($data as $record) {
                    $newdata[] = $this->init_class($model, $record);
                }
                $data = array();
                $data[$model] = $newdata;
                $data['Lazyload'] = $lazyload;
            }
            return $data;
        }
        return false;
    }
    
    function vendor($name = '', $folder = '') {
        if (!empty($name)) {
            $filename = 'class.' . strtolower($name) . '.php';
            $filepath = rtrim(dirname(__FILE__), '/') . '/vendors/' . $folder . '';
            $filefull = $filepath . $filename;
            if (file_exists($filefull)) {
                require_once($filefull);
                $class = 'Satellite' . $name;
                if (${$name} = new $class) {
                    return ${$name};
                }
            }
        }
        return false;
    }

    function check_uploaddir() {
        if (!file_exists(SATL_UPLOAD_DIR)) {
            if (@mkdir(SATL_UPLOAD_DIR, 0777)) {
                @chmod(SATL_UPLOAD_DIR, 0755);
                return true;
            } else {
                $message = __('Uploads folder named "' . SATL_PLUGIN_NAME . '" cannot be created inside "' . SATL_UPLOAD_DIR, SATL_PLUGIN_NAME);
                $this->render_msg($message);
            }
        }
        return false;
    }
    
    function check_sgprodir() {
        $sgprodir = SATL_UPLOAD_DIR.'/../slideshow-gallery-pro/';
        if (file_exists($sgprodir) && $this->is_empty_folder(SATL_UPLOAD_DIR)) {
            if ($this->is_empty_folder($sgprodir)) {
                return false;
            }
            $message = __('Transitioning from <strong>Slideshow Gallery Pro</strong>? <a href="admin.php?page=satellite-slides&method=copysgpro">Copy Files</a> from your previous custom galleries', SATL_PLUGIN_NAME);
            $this->render_msg($message);
        }
        return false;
    }
    
    function is_empty_folder($folder){
        $c=0;
        if(is_dir($folder) ){
            $files = opendir($folder);
            while ($file=readdir($files)){$c++;}
            if ($c>2){
                return false;
            }else{
                return true;
            }
        }       
    }

    function add_action($action, $function = null, $priority = 10, $params = 1) {
        if (add_action($action, array($this, (empty($function)) ? $action : $function), $priority, $params)) {
            return true;
        }
        return false;
    }

    function add_filter($filter, $function = null, $priority = 10, $params = 1) {
        if (add_filter($filter, array($this, (empty($function)) ? $filter : $function), $priority, $params)) {
            return true;
        }
        return false;
    }
    
    function admin_scripts() {
            if (!empty($_GET['page']) && in_array($_GET['page'], (array) $this->sections)) {
                wp_enqueue_script('autosave');
                $method = (isset($_GET['method'])) ? $_GET['method'] : null;


                if ($_GET['page'] == 'satellite') {
//                    wp_enqueue_script('common');
                    wp_enqueue_script('wp-lists');
                    wp_enqueue_script('postbox');

                    wp_enqueue_script('settings-editor', '/' . PLUGINDIR . '/' . SATL_PLUGIN_NAME . '/js/settings-editor.js', array('jquery'), SATL_VERSION);
                    wp_enqueue_script('admin', '/' . PLUGINDIR . '/' . SATL_PLUGIN_NAME . '/js/admin.js', array('jquery'), SATL_VERSION);
                }

                if ($_GET['page'] == "satellite-slides" && $method == "order") {
                    wp_enqueue_script('jquery-ui-sortable');
                }
                
                if ($_GET['page'] == "satellite-slides") {
                    $this->run_angular();
//                    wp_enqueue_script('bootstrap','http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js');
                    wp_enqueue_script('admin', '/' . PLUGINDIR . '/' . SATL_PLUGIN_NAME . '/js/admin.js', array('jquery'), SATL_VERSION);
                }
                
                if ($_GET['page'] == "satellite-galleries") {
                    wp_enqueue_script('plupload-all');
                    wp_enqueue_script('jquery-ui-sortable');
                    wp_enqueue_script('admin', '/' . PLUGINDIR . '/' . SATL_PLUGIN_NAME . '/js/admin.js', array('jquery'), SATL_VERSION);
                }
//                wp_enqueue_scripts();
                //wp_enqueue_script('jquery-ui-sortable');

                add_thickbox();
            }
    }
    
    function run_angular() {
      wp_enqueue_script('angular', '/' . PLUGINDIR . '/' . SATL_PLUGIN_NAME . '/js/angular.min.js', array('jquery'), SATL_VERSION);
      wp_enqueue_script('infinitescroll', '/' . PLUGINDIR . '/' . SATL_PLUGIN_NAME . '/js/ng-infinite-scroll.min.js', array('jquery'), SATL_VERSION);

    }

    function enqueue_scripts() {
      $this->log_me("enqueuing scripts");
        $advanced = $this->get_option('Advanced');
        $jquery = isset($advanced['jquery']) ? $advanced['jquery'] : false;
      if ($jquery != 0) {
          wp_deregister_script( 'jquery' );
          if ($jquery == 1) {
              wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
          } elseif ($jquery == 10) {
              wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
          } else {
              wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
          }

      }
      wp_enqueue_script('jquery');

      if (SATL_PRO && ($this->get_option('preload') == 'Y')) {
          wp_register_script('satellite_preloader', '/' . PLUGINDIR . '/' . SATL_PLUGIN_NAME . '/pro/preloader.js');
          wp_enqueue_script('satellite_preloader');
      }

      $images = $this->get_option('Images');
      if ($images['imagesbox'] == "T")
          add_thickbox();

      return true;
    }
    
    function plupload_admin_head() {
        //Thank you Krishna!! http://www.krishnakantsharma.com/
        // place js config array for plupload
        $plupload_init = array(
            'runtimes' => 'html5,silverlight,flash,html4',
            'browse_button' => 'plupload-browse-button', // will be adjusted per uploader
            'container' => 'plupload-upload-ui', // will be adjusted per uploader
            'drop_element' => 'drag-drop-area', // will be adjusted per uploader
            'file_data_name' => 'async-upload', // will be adjusted per uploader
            'multiple_queues' => true,
            'max_file_size' => wp_max_upload_size() . 'b',
            'url' => admin_url('admin-ajax.php'),
            'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
            'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
            'filters' => array(array('title' => __('Allowed Files'), 'extensions' => '*')),
            'multipart' => true,
            'urlstream_upload' => true,
            'multi_selection' => false, // will be added per uploader
             // additional post data to send to our ajax hook
            'multipart_params' => array(
                '_ajax_nonce' => "", // will be added per uploader
                'action' => 'plupload_action', // the ajax action name
                'imgid' => 0 // will be added per uploader
            )
        );

        echo "<script type='text/javascript'>";
        echo "var base_plupload_config=".json_encode($plupload_init);
        echo "</script>";

    }
    
    function g_plupload_action() {

        // check ajax noonce
        $imgid = $_POST["imgid"];
        check_ajax_referer($imgid . 'pluploadan');

        // handle file upload
        $status = wp_handle_upload($_FILES[$imgid . 'async-upload'], array('test_form' => true, 'action' => 'plupload_action'));

        // send the uploaded file url in response
        echo $status['url'];
        exit;
    }

    function add_option($name = '', $value = '') {
        if (add_option($this->pre . $name, $value)) {
            return true;
        }
        return false;
    }

    function update_option($name = '', $value = '') {
        if (update_option($this->pre . $name, $value)) {
             return true;
        }
        return false;
    }

    function get_option($name = '', $stripslashes = true) {
        if ($option = get_option($this->pre . $name)) {
            if ( !is_array($option) && @unserialize($option) !== false) {
                return unserialize($option);
            }
            if ($stripslashes == true) {
                $option = stripslashes_deep($option);
            }
            return $option;
        }
        return false;
    }

    function check_table( $model = null ) {
        global $wpdb;
        
        if ( !empty($model) && (SATL_VERSION != $this->get_option($model.'_version'))) {
            $this->update_option($model.'_version', SATL_VERSION);
            if ( !empty($this->fields) && is_array($this->fields ) ) {
                if ( /*!$wpdb->get_var("SHOW TABLES LIKE '" . $this->table . "'") ||*/ $this->get_option($model.'db_version') != SATL_VERSION ) {
                    $query = "CREATE TABLE " . $this->table . " (\n";
                    $c = 1;

                    foreach ( $this->fields as $field => $attributes ) {
                        if ( $field != "key" ) {
                            $query .= "`" . $field . "` " . $attributes . "";
                            //$query .= "`".$field . "` " . $attributes ;
                        } else {
                            $query .= "" . $attributes . "";
                        }
                        if ($c < count($this->fields)) {
                            $query .= ",\n";
                        }
                        $c++;
                    }
                    $query .= ");";

                    if (!empty($query)) {
                        $this->table_query[] = $query;
                    }
                    if (SATL_PRO) {
                        if ( class_exists( 'SatellitePremium' ) ) {
                            $satlprem = new SatellitePremium;
                            $satlprem->check_pro_dirs();
                        }
                    }
                    $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM ".$this->table);
                    
                    if (!empty($this->table_query)) {
                        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                        dbDelta($this->table_query, true);
                        $this -> update_option($model.'db_version', SATL_VERSION);
                        $this -> update_option('stldb_version', SATL_VERSION);
                        $this->log_me("Updated slideshow satellite databases");
                        if ($rowcount == 0) {
                          $this->tableTransition($this->table);
                        }
                    }
                } else {
                    //echo "this model db version: ".$this->get_option($model.'db_version');
                    $field_array = $this->get_fields($this->table);

                    foreach ($this->fields as $field => $attributes) {
                        if ($field != "key") {
                            $this->add_field($this->table, $field, $attributes);
                        }
                    }
                }


            }
        }

        return false;
    }
    
    function get_fields($table = null) {
        global $wpdb;

        if (!empty($table)) {
            $fullname = $table;
            
            if (($tablefields = $wpdb->get_results( 'SHOW COLUMNS FROM '.$table, OBJECT )) !== false) {
                $columns = count($tablefields);
                $field_array = array();
                for ($i = 0; $i < $columns; $i++) {
                    $fieldname = $tablefields[$i]->Field;
                    $field_array[] = $fieldname;
                }

                return $field_array;
            }
        }
        return false;
    }

    function delete_field($table = '', $field = '') {
        global $wpdb;

        if (!empty($table)) {
            if (!empty($field)) {
                $query = "ALTER TABLE `" . $wpdb->prefix . "" . $table . "` DROP `" . $field . "`";

                if ($wpdb->query($query)) {
                    return false;
                }
            }
        }

        return false;
    }

    function change_field($table = '', $field = '', $newfield = '', $attributes = "TEXT NOT NULL") {
        global $wpdb;

        if (!empty($table)) {
            if (!empty($field)) {
                if (!empty($newfield)) {
                    $field_array = $this->get_fields($table);

                    if (!in_array($field, $field_array)) {
                        if ($this->add_field($table, $newfield)) {
                            return true;
                        }
                    } else {
                        $query = "ALTER TABLE `" . $table . "` CHANGE `" . $field . "` `" . $newfield . "` " . $attributes . ";";

                        if ($wpdb->query($query)) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    function add_field($table = '', $field = '', $attributes = "TEXT NOT NULL") {
      global $wpdb;

      if (!empty($table)) {
        if (!empty($field)) {
          $field_array = $this->get_fields($table);

          if (!empty($field_array)) {
            if (!in_array($field, $field_array)) {
              $query = "ALTER TABLE `" . $table . "` ADD `" . $field . "` " . $attributes . ";";
              if ($wpdb->query($query)) {
                return true;
              }
            }
          }
        }
      }
      return false;
    }

    function render($file = '', $params = array(), $output = true, $folder = 'admin') {
        if (!empty($file)) {
          $filename = $file . '.php';
          $filepath = $this->plugin_base() . '/views/' . $folder . "/";
          $filefull = $filepath . $filename;
          if (file_exists($filefull)) {
            if (!empty($params)) {
                foreach ($params as $pkey => $pval) {
                    ${$pkey} = $pval;
                }
            }
            if ($output == false) {
                ob_start();
            }
            include($filefull);
            if ($output == false) {
                $data = ob_get_clean();
                return $data;
            } else {
                flush();
                return true;
            }
          }
        }
        return false;
    }

    public function satl_ajax_reorder() {

        if (!empty($_REQUEST['slides_order'])) {
            $slideOrder = $_REQUEST['slides_order'];
            foreach ($slideOrder as $order => $slide_id) {
                $this -> Slide -> save_field('slide_order', $order, array('id' => $slide_id));
            }

            _e('Slides have been reordered', SATL_PLUGIN_NAME);

        }

        die();
    }

    /**
     * Add Settings link to plugins - code from GD Star Ratings
     */
    function add_satl_settings_link($links, $file) {
        static $this_plugin;

        if (!$this_plugin)
            $this_plugin = plugin_basename(__FILE__);

        if ($file == $this_plugin) {
            $settings_link = '<a href="admin.php?page=satellite">' . __("Settings", SATL_PLUGIN_NAME) . '</a>';
            array_unshift($links, $settings_link);
        }
        return $links;
    }
    
    /*
     * Mainly used on transition from gallery_slides to satl_slides -- V 2.1 Update
     */
    function tableTransition($new_table, $old_table = null) {
      global $wpdb;
      
      if (!$old_table) {
        $gallery_table = $wpdb -> prefix.'gallery_slides';
        $countGT = $wpdb->get_var("SELECT COUNT(*) FROM ".$gallery_table);
        if ($countGT > 0){
          $old_table = $gallery_table;
        } else { return false; }
      }
      $this->log_me("Running table transition, and moving all slide information over from ".$old_table." to the new table: ".$new_table);
      $result = $wpdb->query("INSERT INTO ". $new_table ." SELECT id, title, description, "
            ."image, type, section, image_url, uselink, link, textlocation, more,"
            ."slide_order, created, modified FROM ". $old_table); 
    }
    
    public function canPremiumDoThis($action) {
      if (SATL_PRO && class_exists('SatellitePremiumHelper')) {
        switch ($action) {
          case 'watermark':
            return method_exists('SatellitePremiumHelper','doWatermark');
            break;
          default:
            return false;
            break;
        }
      }
      return false;
    }

    public function log_me($message) {
        if (WP_DEBUG === true) {
            if (is_array($message) || is_object($message)) {
                error_log(print_r($message, true));
            } else {
                error_log($message);
            }
        }
    }

    function plugin_base() {
        return rtrim(dirname(__FILE__), '/');
    }

    function url() {
        return rtrim(WP_PLUGIN_URL, '/') . '/' . substr(preg_replace("/\\" . "/" . "/si", "/", $this->plugin_base()), strlen(ABSPATH));
    }

    function debug($var = array()) {
        if ($this->debugging) {
            echo '<pre>' . print_r($var, true) . '</pre>';
            return true;
        }

        return false;
    }



}