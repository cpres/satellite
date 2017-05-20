<?php
class SatelliteSlide extends SatelliteDbHelper
{
    var $table;
    var $model = 'Slide';
    var $controller = "slides";
    var $plugin_name = SATL_PLUGIN_NAME;
    var $data = array();
    var $errors = array();
    var $fields = array(
        'id' => "INT(11) NOT NULL AUTO_INCREMENT",
        'title' => "VARCHAR(150) CHARACTER SET utf8 NOT NULL DEFAULT ''",
        'description' => "TEXT CHARACTER SET utf8",
        'alt_text' => "VARCHAR(150) CHARACTER SET utf8 NULL DEFAULT ''",
        'image' => "VARCHAR(75) NOT NULL DEFAULT ''",
        'type' => "ENUM('file','url','existing') NOT NULL DEFAULT 'file'",
        'section' => "INT(5) NOT NULL DEFAULT '1'",
        'image_url' => "VARCHAR(200) NOT NULL DEFAULT ''",
        'uselink' => "ENUM('Y','N') NOT NULL DEFAULT 'N'",
        'link' => "VARCHAR(200) NOT NULL DEFAULT ''",
        'textlocation' => "VARCHAR(5) NOT NULL DEFAULT 'D'",
        'more' => "INT(11) NULL",
        'slide_order' => "INT(11) NOT NULL DEFAULT '0'",
        'created' => "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
        'modified' => "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
        'key' => "PRIMARY KEY  (id)",
    );

    function SatelliteSlide($data = array())
    {
        global $wpdb;

        $this->table = $wpdb->prefix . "satl_" . $this->controller;
        $this->check_table($this->model);
        if (!empty($data)) {
            foreach ($data as $dkey => $dval) {
                $this->{$dkey} = ($dval) ? $dval : null;
            }
        }

        return true;
    }

    function defaults()
    {
        $defaults = array(
            'slide_order' => 0,
            'created' => SatelliteHtmlHelper::gen_date(),
            'modified' => SatelliteHtmlHelper::gen_date(),
        );

        return $defaults;
    }

    function validate($data = null)
    {
        $this->errors = array();
        $this->log_me('validating satellite slide');

        if (!empty($data)) {
            $data = (empty($data[$this->model])) ? $data : $data[$this->model];
            
            $HtmlHelper = new SatelliteHtmlHelper();

            foreach ($data as $dkey => $dval) {
                $this->data->{$dkey} = stripslashes($dval);
            }
//            $this->log_me($this->data);
            if (empty($data['title'])) {
                $this->errors['title'] = __('Please fill in a title', SATL_PLUGIN_NAME);
            }
            //if (empty($description)) { $this -> errors['description'] = __('Please fill in a description', SATL_PLUGIN_NAME); }
            if (empty($data['type'])) {
                $this->errors['type'] = __('Please select an image type', SATL_PLUGIN_NAME);
            } //if (empty($section)) { $section = '1'; }

            elseif ($data['type'] == "file") {
                if (!empty($r['image_oldfile']) && empty($_FILES['image_file']['name'])) {
                    $imagename = str_replace(" ", "-", $r['image_oldfile']);

                    $imagepath = SATL_UPLOAD_DIR . '/';
                    $imagefull = $imagepath . $imagename;

                    $this->data->image = $imagename;
                } else {
                    if ($_FILES['image_file']['error'] <= 0) {
                        $imagename = str_replace(" ", "-", $_FILES['image_file']['name']);
                        $imagepath = SATL_UPLOAD_DIR . '/';
                        $imagefull = $imagepath . $imagename;

                        if (!is_uploaded_file($_FILES['image_file']['tmp_name'])) {
                            $this->errors['image_file'] = __('The image did not upload, please try again', SATL_PLUGIN_NAME);
                        } elseif (!move_uploaded_file($_FILES['image_file']['tmp_name'], $imagefull)) {
                            $this->errors['image_file'] = __('Image could not be moved from TMP to ' . SATL_UPLOAD_URL . ', please check permissions', SATL_PLUGIN_NAME);
                        } else {
                            $this->data->image = $imagename;
                            
                            $this->_imageProcess($imagefull);
                            
                        }
                    } else {
                        switch ($_FILES['image_file']['error']) {
                            case UPLOAD_ERR_INI_SIZE        :
                            case UPLOAD_ERR_FORM_SIZE        :
                                $this->errors['image_file'] = __('The image file is too large', SATL_PLUGIN_NAME);
                                break;
                            case UPLOAD_ERR_PARTIAL        :
                                $this->errors['image_file'] = __('The image was partially uploaded, please try again', SATL_PLUGIN_NAME);
                                break;
                            case UPLOAD_ERR_NO_FILE        :
                                $this->errors['image_file'] = __('No image was chosen for uploading, please choose an image', SATL_PLUGIN_NAME);
                                break;
                            case UPLOAD_ERR_NO_TMP_DIR        :
                                $this->errors['image_file'] = __('No TMP directory has been specified for PHP to use, please ask your hosting provider', SATL_PLUGIN_NAME);
                                break;
                            case UPLOAD_ERR_CANT_WRITE        :
                                $this->errors['image_file'] = __('Image cannot be written to disc, please ask your hosting provider', SATL_PLUGIN_NAME);
                                break;
                        }
                    }
                }
            } elseif ($data['type'] == "url") {
                if (empty($data['image_url'])) {
                    $this->errors['image_url'] = __('Please specify an image', SATL_PLUGIN_NAME);
                } else {
                    if ($image = wp_remote_fopen($data['image_url'])) {
                        $filename = str_replace(" ", "-", basename($data['image_url']));
                        $filepath = SATL_UPLOAD_DIR . '/';

                        $filefull = $filepath . $filename;
                        if (!file_exists($filefull)) {
                            $fh = @fopen($filefull, "w");
                            @fwrite($fh, $image);
                            @fclose($fh);
                            $this->data->image = $filename;
                            $this->_imageProcess($filefull);

                        }
                    }
                }
            }
            elseif ($type == "existing") {
                if (empty($image_existing)) {
                    $this->errors['image_existing'] = __('Please try a different image', SATL_PLUGIN_NAME);
                } else {
                    $imagepath = SATL_UPLOAD_DIR . '/';
                    $imagefull = $imagepath . $image_existing;

                    $this->data->image = $image_existing;
                }
            }
        } else {
            $this->errors[] = __('No data was posted', SATL_PLUGIN_NAME);
        }
        return $this->errors;
    }
    
    private function _imageProcess($file_dir_path) {
        $Gallery = new SatelliteGallery();
        $HtmlHelper = new SatelliteHtmlHelper();
//        $this->log_me("Processing: ".$this->data->image);
        // No resizing or watermarking on our Special Galleries like More and Watermark
        if (!$Gallery->isSpecialGallery($this->data->section)) {
            $imagepath = SATL_UPLOAD_DIR . '/';
            $name = $HtmlHelper->strip_ext($this->data->image, 'filename');
            $ext = $HtmlHelper->strip_ext($this->data->image, 'ext');
            $Image = new SatelliteImageHelper;
            $images = $this->get_option('Images');
            $Image->load($file_dir_path);
            $Image->resizeToBox($images[resize]);
            $Image->save($file_dir_path);
            $Image->applyWatermark($this->data->image, $this->data->section);

            $thumbfull = $imagepath . $name . '-thumb.' . strtolower($ext);

            $imagethumbs = wp_get_image_editor($file_dir_path);
            if ( ! is_wp_error( $imagethumbs ) ) {
              $imagethumbs->resize( 150, 150, true );
              $imagethumbs->save( $thumbfull );
            } else {
              $this->log_me('thumb error');
              $this->log_me(is_wp_error( $imagethumbs ));
            }
        }
        @chmod($imagefull, 0755);
    }

    function full_copy($source, $target)
    {
        if (is_dir($source)) {
            //@mkdir( $target );
            $d = dir($source);
            while (FALSE !== ($entry = $d->read())) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                $Entry = $source . '/' . $entry;
                if (is_dir($Entry)) {
                    full_copy($Entry, $target . '/' . $entry);
                    continue;
                }
                copy($Entry, $target . '/' . $entry);
            }

            $d->close();
        } else {
            copy($source, $target);
        }
    }

    public function processImages($images, $section = false)
    {
        $HtmlHelp = new SatelliteHtmlHelper();
        $this->log_me('starting processImages');
        
        $imgarray = explode(",", $images);
        if (!$section) {
            $section = $this->latestSection();
        }
        $i = 0;
//        $this->log_me($imgarray);
        foreach ($imgarray as $image) {
            $file = basename($image);
            $this->log_me($file);
            $name = $HtmlHelp->strip_ext($file, 'filename');
            $data = array('title' => $name, 'section' => $section, 'type' => 'url', 'image_url' => $image, 'use_link' => 'N', 'slide_order' => $i);
            $slidedata = array('Slide' => $data);
            $this->log_me($data);
            if ($this->save($data, true)) {
                error_log($name . " has successfully saved");
                continue;
            } else {
                error_log("processImages has failed");
                return false;
            }
            $i++;
        }
        return true;
    }

    public function slideCount($gallery)
    {
        $images = $this->find_all(array('section' => $gallery), array('id', 'section'));
        if (is_array($images)) {
            return count($images);
        } else {
            return 0;
        }

    }

    /*
     * $gal : @string name of Gallery, i.e. "More"
     */
    public function getGalleryImages($gal)
    {
        $Gallery = new SatelliteGallery;

        $more = ($galID = $Gallery->getGalleryIDByTitle($gal)) ? $this->find_all(array('section' => $galID), 'title,section,id') : null;
        $imgarray = null;
        if (is_array($more)) {
            foreach ($more as $moreimg)
                $imgarray[] = array('title' => $moreimg->title, 'id' => $moreimg->id);
        } else {
            $msg = __('Gallery not yet created', SATL_PLUGIN_NAME);
            $imgarray[] = array('title' => $msg, 'id' => 0);
        }
        return $imgarray;
    }

    public function resizeById($record_id)
    {
        if (!empty($record_id) && $record = $this->find(array('id' => $record_id))) {
            $images = $this->get_option('Images');
            $Image = new SatelliteImageHelper;
            $imagepath = SATL_UPLOAD_DIR . '/';
            $imagefull = $imagepath . $record->image;
            //run normal resize process
            $Image->load($imagefull);
            //delete the photo here?)
            error_log("Resizing by id " . $record->image . " to the size of " . $images[resize]);
            $Image->resizeToBox($images[resize]);
            error_log("Starting Delete of " . $record_id);
            $this->delete($record_id, false);
            error_log("Starting Post-Delete save of " . $record_id);
            $Image->save($imagefull);
        }
    }

    public function watermarkById($record_id)
    {
        if (!empty($record_id) && $record = $this->find(array('id' => $record_id))) {
            $Image = new SatelliteImageHelper;
            error_log("watermarking name " . $record->image . " section of: " . $record->section);
            $imagepath = SATL_UPLOAD_DIR . '/';
            $imagefull = $imagepath . $record->image;
            $Image->load($imagefull);
            $Image->applyWatermark($record->image, $record->section);
        }
    }

    public function quickSaveSlide($id, $title, $gallery)
    {

        $conditions = array('id' => $id);
        $this->save_field('title', $title, $conditions);
        $this->save_field('section', intval($gallery), $conditions);
        $this->save_field('modified', SatelliteHtmlHelper::gen_date(), $conditions);
    }

    public function getData()
    {
        if (!empty($this->data)) {
            return $this->data;
        } else {
            $this->data = new stdClass();
            $this->data->link = null;
            $this->data->image = null;
            $this->data->section = null;
            $this->data->title = null;
            $this->data->description = null;
            $this->data->textlocation = null;
            $this->data->alt_text = null;
            $this->data->type = null;
            $this->data->uselink = null;

        }
//        print_r($this->data);die();
        return $this->data;
    }

}

?>