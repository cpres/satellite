<?php
class SatelliteGallery extends SatelliteDbHelper
{
    var $table;
    var $model = 'Gallery';
    var $specials = array('More', 'Watermark');
    var $controller = "galleries";
    var $plugin_name = SATL_PLUGIN_NAME;
    var $data = array();
    var $errors = array();
    var $fields = array(
        'id' => "INT(11) NOT NULL AUTO_INCREMENT",
        'title' => "VARCHAR(150) CHARACTER SET utf8 NOT NULL DEFAULT ''",
        'description' => "TEXT CHARACTER SET utf8",
        'image' => "VARCHAR(75) NOT NULL DEFAULT ''",
        'source' => "VARCHAR(40) NOT NULL DEFAULT ''",
        'capposition' => "VARCHAR(40) NOT NULL DEFAULT ''",
        'theme' => "VARCHAR(40) NOT NULL DEFAULT ''",
        'caphover' => "BOOLEAN NOT NULL DEFAULT 0",
        'pausehover' => "BOOLEAN NOT NULL DEFAULT 0",
        'font' => "VARCHAR(200) CHARACTER SET utf8 NOT NULL DEFAULT ''",
        'capanimation' => "VARCHAR(40) NOT NULL DEFAULT ''",
        'gal_order' => "INT(11) NOT NULL DEFAULT '0'",
        'created' => "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
        'modified' => "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
        'key' => "PRIMARY KEY  (id)",
    );

    function SatelliteGallery($data = array())
    {
        global $wpdb;

        //$this -> table = $wpdb -> prefix . strtolower($this -> pre) . "_" . $this -> controller;
        $this->table = $wpdb->prefix . "satl_" . $this->controller;
        $this->check_table($this->model);
        if (!empty($data)) {
            foreach ($data as $dkey => $dval) {
                $this->{$dkey} = $dval;
            }
        }

        return true;
    }

    function defaults()
    {
        $defaults = array(
            'gal_order' => 0,
            'created' => SatelliteHtmlHelper::gen_date(),
            'modified' => SatelliteHtmlHelper::gen_date(),
        );

        return $defaults;
    }

    function validate($data = null)
    {
        $this->errors = array();

        if (!empty($data)) {
            $data = (empty($data[$this->model])) ? $data : $data[$this->model];

            foreach ($data as $dkey => $dval) {
                if (!empty($this->data)) {
                    $this->data->{$dkey} = stripslashes($dval);
                }
            }

            if (empty($data['title'])) {
                $this->errors['title'] = __('Please enter a title', SATL_PLUGIN_NAME);
            }
            if (empty($data['source'])) {
                $this->errors['type'] = __('Please select a gallery source', SATL_PLUGIN_NAME);
            } elseif ($data['source'] == "satellite") {
              // validation for custom gallery
            } elseif ($data['source'] == "post") {
              // validation for post
            }
        } else {
            $this->errors[] = __('No data was posted', SATL_PLUGIN_NAME);
        }
        return $this->errors;
    }

    /**
     *
     * @global type $wpdb
     * @return type int
     */
    function latestSection()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . "satl_galleries";
        $latest = $this->find(null, 'id');
        return $latest->id;
    }

    /**
     *
     * @param type $gallery @integrer
     * @return type @string
     */
    public function capLocation($position, $gallery)
    {
        if ($position == "On Right") :
            $briefLocation = "right";
        elseif ($position = "Overlayed") :
            $briefLocation = "overlayed";
        else :
            $briefLocation = $position;
        endif;

        return $briefLocation;
    }

    public function loadData($gallery)
    {
      $data = $this->find(array('id' => $gallery), 'caphover,
                                                    pausehover,
                                                    capposition,
                                                    capanimation,
                                                    title,
                                                    theme,
                                                    description,
                                                    font,
                                                    source,
                                                    id');

        return $data;
    }
    
    public function getSource($id) {
      $data = $this->find(array('id' => $gallery), 'source');
      return $data->source;
    }

    /*
     * $gal : @string eg "More"
     */
    public function getGalleryIDByTitle($gal)
    {
        $gallery = $this->find(array('title' => $gal), 'title,id');
        $this->log_me($gal);
        if (isset($gallery->id))
            return $gallery->id;
        else
            return null;
    }

    /*
     * return @array of all galleries
     */
    public function getGalleries()
    {
        $galleries = $this->find_all('', 'title,id');
        if (!empty($galleries)) {
            foreach ($galleries as $gallery)
                $galArray[] = array('title' => $gallery->title, 'id' => $gallery->id);
            if ($galArray) {
                return $galArray;
            }
        }

        return null;

    }

    /*
     * Returns true if gallery is special like "More or Watermark"
     * No resizing or watermarking
     *
     * @param int $galId
     * @return bool
     */
    public function isSpecialGallery($galId)
    {
        $specials = $this->get_option('specials');
        if (empty($specials)) {
            $specials = $this->registerSpecials(true);
        }
        if (!empty($specials)) {
            foreach ($specials as $special) {
                $this->log_me("checking special gallery " . $special . " against galId" . $galId);
                if ($galId == $special) {
                    $this->log_me("uploading to a special gallery : " . $galId);
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public function registerSpecials($ret = false)
    {
        // TODO : on gallery creation run registerSpecials
        $this->log_me("Registering special galleries");
        $specarray = array();
        foreach ($this->specials as $special) {
            $specarray[] = $this->getGalleryIDByTitle($special);
        }
        $this->update_option('specials', $specarray);
        if ($ret) {
            return $specarray;
        }
    }

}

?>