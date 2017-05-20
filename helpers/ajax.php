<?php

class SatelliteAjaxHelper extends SatellitePlugin {

    function __construct() {

        add_action('wp_head', array( $this,'gallery_display_javascript'));
        add_action('wp_head', array( $this, 'display_ajaxurl'), 9 );
        add_action('wp_ajax_gallery_display', array( $this, 'gallery_display_callback'));
        add_action('wp_ajax_nopriv_gallery_display', array( $this, 'gallery_display_callback'));        

    }
    
    function display_ajaxurl() {
        ?>
        <script type="text/javascript">
        var ajaxurl = '<?php echo(admin_url( 'admin-ajax.php' ));?>';
        </script>
        <?php
    }
    /**
     * For displaying multiple galleries and handling the AJAX calls from their clicks
     * @echo javascript into header
     * 
     */
    function gallery_display_javascript() {
      global $post;
        ?>

        <script type="text/javascript">
            function showGallerySatellite(id) {
                var data = {
                        action: 'gallery_display',
                        slideshow: id,
                        postID: <?php echo ($post->ID); ?>
                };
                jQuery.post(ajaxurl, data, function(response) {
                        jQuery('.satl-gal-titles .current').removeClass('current');
                        jQuery('.gal'+id).addClass('current');
                        jQuery('.galleries-satl-wrap').html(response);
//                        initslideshow();
                });        
            }
            function showSoloSatellite(id,unique) {
                var data = {
                        action: 'gallery_display',
                        slideshow: id
                };
                jQuery.post(ajaxurl, data, function(response) {
                        jQuery('#splash-satl-wrap-'+unique).html(response);
                });        
            }
        </script>
        <?php 
    }    
    /**
     *
     * @global type $wpdb 
     */
    function gallery_display_callback() {
    	global $wpdb; // this is how you get access to the database

    	$slideshow = intval( $_POST['slideshow'] );
        $origpost = $_POST['slideshow'];

        $Satellite = new Satellite();
        $slides = $Satellite -> Slide -> find_all(array('section'=>(int) stripslashes($slideshow)), null, array('slide_order', "ASC"));
        $displayAjaxSatellite = $Satellite -> render('default', array('slides' => $slides, 'frompost' => false, 'respExtra' => 175, 'orig_post' => $origpost), false, 'orbit');
        echo $displayAjaxSatellite;

	die(); // this is required to return a proper result
    }
    
}
?>
