<?php
class SatelliteHtmlHelper extends SatellitePlugin
{

    function link($name = '', $href = '/', $args = array())
    {
        $defaults = array(
            'title' => (empty($args['title'])) ? $name : $args['title'],
            'target' => "_self",
            'class' => "wpco",
            'rel' => "",
            'onclick' => "",
        );

        $r = wp_parse_args($args, $defaults);

        ob_start();

        ?><a class="<?php echo $r['class']; ?>"
             rel="<?php echo $r['rel']; ?>" <?php echo (!empty($r['onclick'])) ? 'onclick="' . $r['onclick'] . '"' : ''; ?>
             href="<?php echo $href; ?>" target="<?php echo $r['target']; ?>"
             title="<?php echo $r['title']; ?>"><?php echo $name; ?></a><?php

        $link = ob_get_clean();
        return $link;
    }

    function filename($url = null)
    {
        if (!empty($url)) {
            return basename($url);
        }

        return false;
    }

    function thumbname($filename = null, $append = "thumb")
    {
        if (!empty($filename)) {
            $name = $this->strip_ext($filename, "name");
            $ext = strtolower($this->strip_ext($filename, "ext"));

            return $name . '-' . $append . '.' . $ext;
        }

        return false;
    }

    function image_url($filename = null)
    {
        if (!empty($filename)) {
            if (file_exists(SATL_UPLOAD_DIR . '/' . $filename)) {
                return SATL_UPLOAD_URL . '/' . $filename;
            } else {
                $this->log_me('could not find file: '.$filename);
                return SATL_PLUGIN_URL . "/images/placeholder.png";
            }

        }

        return false;
    }
    
    function image_dir($filename = null)
    {
        if (!empty($filename)) {
            if (file_exists(SATL_UPLOAD_DIR . '/' . $filename)) {
                return SATL_UPLOAD_DIR . '/' . $filename;
            } else {
                return SATL_PLUGIN_DIR . "/images/placeholder.png";
            }

        }

        return false;
    }
    
    function image_id($id = null)
    {
        if (!empty($id)) {
            $Slide = new SatelliteSlide;
            if ($image = $Slide->find(array('id' => (int)stripslashes($id)), 'id,image')) {
              return $this->image_url($image->image);
            }
        }
        return false;
    }

    function field_name($name = '')
    {
        if (!empty($name)) {
            $Html = new SatelliteHtmlHelper;
            if ($mn = $Html->strip_mn($name)) {
                return $mn[1] . '[' . $mn[2] . ']';
            }
        }

        return $name;
    }

    function field_error($name = '', $el = "p")
    {
        if (!empty($name)) {
            if ($mn = $this->strip_mn($name)) {
                $errors = array();

                switch ($mn[1]) {
                    case 'Slide'        :
                        $model = new SatelliteSlide;
                        $errors = $model->validate($_POST);
                        break;
                    case 'Gallery'      :
                        $model = new SatelliteGallery;
                        $errors = $model->validate($_POST);
                        break;
                }

                if (!empty($errors[$mn[2]])) {
                    $error = '<' . $el . ' class="' . $this->pre . 'error">' . $errors[$mn[2]] . '</' . $el . '>';

                    return $error;
                }
            }
        }

        return false;
    }

    /**
     * @param null $name - eg 'Slide.title'
     * @return bool
     */
    function field_value($name = null)
    {
        // $mn example = array('Slide.title','Slide','title')
        if ($mn = $this->strip_mn($name)) {
            if (in_array($mn[1],$this->models) && isset($this->{$mn[1]}) && is_object($this->{$mn[1]})) {
                $value = $this->{$mn[1]}->data->{$mn[2]};
                return $value;
            }
        }

        return false;
    }

    function retainquery($add = '')
    {
        $url = $_SERVER['REQUEST_URI'];

        if (($urls = @explode("?", $url)) !== false) {
            if (!empty($urls[1])) {
                if (!empty($add)) {
                    if (($adds = explode("&", str_replace("&amp;", "&", $add))) !== false) {
                        foreach ($adds as $qstring) {
                            if (($qparts = @explode("=", $qstring)) !== false) {
                                if (!empty($qparts[0])) {
                                    if (preg_match("/\&?" . $qparts[0] . "\=([0-9a-z+]*)/i", $urls[1], $matches)) {
                                        $urls[1] = preg_replace("/\&?" . $qparts[0] . "\=([0-9a-z+]*)/i", "", $urls[1]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $urls[1] = preg_replace("/\&?" . $this->pre . "message\=([0-9a-z+]*)/i", "", $urls[1]);
        $url = $urls[0];
        $url .= '?';
        $url .= (empty($urls[1])) ? '' : $urls[1] . '&amp;';
        $url .= $add;

        return preg_replace("/\?(\&)?/si", "?", $url);
    }

    public function strip_ext($filename = '', $return = 'ext')
    {
        if (!empty($filename)) {
            $path_parts = pathinfo($filename);
            if ($return == 'ext') {
                return $path_parts['extension'];
            } else {
                return $path_parts['filename'];
            }
        }
        return false;
    }

    function strip_mn($name = '')
    {
        if (!empty($name)) {
            if (preg_match("/^(.*?)\.(.*?)$/si", $name, $matches)) {
                return $matches;
            }
        }

        return false;
    }

    public static function gen_date($format = "Y-m-d H:i:s", $time = false)
    {
        $time = (empty($time)) ? time() : $time;
        $date = date($format, $time);

        return $date;
    }

    public static function array_to_object($array = array())
    {
        $object = new stdClass();

        foreach ($array as $key => $value)
        {
            $object->$key = $value;
        }
        return $object;

    }

    function sanitize($string = '', $sep = '-')
    {
        if (!empty($string)) {
            $string = preg_replace("[^0-9a-z" . $sep . "]", "", strtolower(str_replace(" ", $sep, $string)));
            $string = preg_replace("/" . $sep . "[" . $sep . "]*/i", $sep, $string);

            return $string;
        }

        return false;
    }

    function findInOptions($needle, $haystacks = array())
    {
        foreach ($haystacks as $stack) {
            if (is_array($stack) && in_array($needle, $stack)) {
                return true;
            }
        }
        return false;
    }

}

?>