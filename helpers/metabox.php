<?php

class SatelliteMetaboxHelper extends SatellitePlugin {

    var $name = 'Metabox';

    function SatelliteMetaboxHelper() {
        $url = explode("&", $_SERVER['REQUEST_URI']);
        $this->url = $url[0];
    }

    function settings_submit() {
        $this->render('metaboxes/settings-submit', false, true, 'admin');
    }

    function settings_general() {
        $this->render('metaboxes/settings-general', false, true, 'admin');
    }

    function settings_linksimages() {
        $this->render('metaboxes/settings-linksimages', false, true, 'admin');
    }

    function settings_styles() {
        $this->render('metaboxes/settings-styles', false, true, 'admin');
    }
    function settings_thumbs() {
        $this->render('metaboxes/settings-thumbs', false, true, 'admin');
    }

    function settings_posttype() {
        $this->render('metaboxes/settings-posttype', false, true, 'admin');
    }
   
    function settings_advanced() {
        $this->render('metaboxes/settings-advanced', false, true, 'admin');
    }

    function settings_pro() {
        $this->render('metaboxes/settings-pro', false, true, 'admin');
    }

}

?>