<?php

    class SatelliteFormHelper extends SatellitePlugin {

    public function display($newfields, $model) {

        foreach ($newfields as $value) {
            $valId = (isset($value['id'])) ? $value['id'] : null;
            switch ( $value['type'] ) {
                case 'open':
                    echo $this -> open();
                    break;
                case 'checkbox':
                    echo $this->checkbox($model.'.'.$valId, $value);
                    break;
                case 'close':
                    echo $this -> close();
                    break;
                case 'select':
                    echo $this -> select($model.'.'.$valId, $value);
                    break;
                case 'text':
                    echo $this -> text($model.'.'.$valId, $value);
                    break;
                case 'upload':
                    echo $this -> upload($model.'.'.$valId, $value);
                    break;
                case 'textarea':
                    echo $this -> textarea($model.'.'.$valId, $value);
                    break;
                default:
                    error_log("Satellite could not find a form value for ".$value['type']);
            }
        }
        
    }

    function hidden($name = '', $args = array()) {
        global $wpcoHtml;
        $defaults = array(
            'value' => (empty($args['value'])) ? $this->Html->field_value($name) : $args['value'],
        );
        $r = wp_parse_args($args, $defaults);

        ob_start();
        ?><input type="hidden" name="<?php echo $this->Html->field_name($name); ?>" value="<?php echo $r['value']; ?>" id="<?php echo $name; ?>" /><?php
        $hidden = ob_get_clean();
        return $hidden;
    }
    
    /**
     *
     * @param type $name
     * @param type $args
     * @return type $field
     */
    
    function text($name = '', $args = array()) {
        $Html = new SatelliteHtmlHelper;

        $defaults = array(
            'id' => (empty($args['id'])) ? $name : $args['id'],
            'width' => '100%',
            'class' => "widefat",
            'error' => true,
            'value' => (empty($args['value'])) ? $Html -> field_value($name) : $args['value'],
            'autocomplete' => "on",
        );

        $r = wp_parse_args($args, $defaults);

        ob_start();

        ?>
        <?php //echo $Html->field_value($name); ?>
            <tr>
                <th class="verttop"><label><strong><?php echo $r['name']; ?></strong></label></th>
                <td>
                    <input style="width:400px;" class="<?php echo $r['class']; ?>"name="<?php echo $Html->field_name($name); ?>" id="<?php echo $r['id']; ?>" type="<?php echo $r['type']; ?>" value="<?php echo ($r['value']) ? $r['value'] : $r['std']; ?>" />
                    <?php echo ($r['error'] == true) ? '<div style="color:red;">' . $Html->field_error($name) . '</div>' : ''; ?>
                    <span class="howto"><?php echo(isset($r['desc']) ? $r['desc'] : '') ; ?></span>
                </td>
            </tr>
        
        <?php

        $text = ob_get_clean();
        return $text;
    }
    
    function open() { ?>
        <table width="100%" border="0" id="satl_table" style="">
    <?php 
    }
    
    /**
     *
     * @param type $name
     * @param type $args
     * @return type $field
     */
    
    function checkbox($name = '', $args = array()) {
        $Html = new SatelliteHtmlHelper;

        $defaults = array(
            'id' => (empty($args['id'])) ? $name : $args['id'],
            'width' => '100%',
            'class' => "widefat",
            'error' => true,
            'value' => (empty($args['value'])) ? $Html -> field_value($name) : $args['value'],
            'autocomplete' => "on",
        );

        $r = wp_parse_args($args, $defaults);

        ob_start();
        ?>
        	<tr>
              <th class="verttop"><label><strong><?php echo $r['name']; ?></strong></label></th>
              <td>

                  <?php $checked = ($r['value']) ? "checked=\"checked\"" : ""; ?>
                  <input type="hidden" name="<?php echo $Html->field_name($name); ?>" value="0" />
                  <input type="checkbox" name="<?php echo $Html->field_name($name); ?>" id="<?php echo $r['id']; ?>" value="1" <?php echo $checked; ?> />
                  <span class="howto"><?php echo($r['desc']); ?></span>
              </td>

          </tr>
        <?php
        if ($r['error'] == true) {
            echo $Html->field_error($name);
        }

        $checkbox = ob_get_clean();
        return $checkbox;
    }
    /**
     * end form
     */
    
    function close() {
        echo "</table><br />";
    }
    
    /**
     *
     * @param type $name
     * @param type $args
     * @return type $field
     */

    function textarea($name = '', $args = array()) {
        $defaults = array(
            'error' => true,
            'width' => '100%',
            'class' => "widefat",
            'rows' => 4,
            'cols' => "100%",
        );
        
        $r = wp_parse_args($args, $defaults);
        $Html = new SatelliteHtmlHelper;
        
        ob_start();
        ?>
            <tr>
                <th class="verttop"><label><strong><?php echo $r['name']; ?></strong></label></th>
                <td>
                    <textarea class="<?php echo $r['class']; ?>" name="<?php echo $Html->field_name($name); ?>" rows="<?php echo $r['rows']; ?>" style="width:<?php echo $r['width']; ?>;" cols="<?php echo $r['cols']; ?>" id="<?php echo ($r['id']); ?>"><?php echo ($r['value']); ?></textarea>
                    <span class="howto"><?php echo($r['desc']); ?></span>
                </td>
            </tr>
            <?php
        if ($r['error'] == true) {
            echo $Html->field_error($name);
        }

        $textarea = ob_get_clean();
        return $textarea;
    }
    /**
     *
     * @param type $name
     * @param type $args
     * @return type $field
     */
    function select($name = '', $args = array()){
        $Html = new SatelliteHtmlHelper;
        $defaults = array(
            'error' => true,
            'class' => "widefat",
            'width' => "140",
            //'value' => (empty($args['value'])) ? '' : $args['value'],
        );
        
        $r = wp_parse_args($args, $defaults);
        
        ob_start();
//        $this->log_me($r['options']);
        ?>
            <tr>
                <th class="verttop"><label><strong><?php echo $r['name']; ?></strong></label></th>
                <td >
                    <select class="<?php echo $r['class']; ?>" style="width:<?php echo $r['width']; ?>px;" name="<?php echo $Html->field_name($name); ?>" id="<?php echo $r['id']; ?>">
                <?php if ( ! $Html->findInOptions($r['std'],$r['options']) ) : ?>
                        <option value="" ><?php echo($r['std']); ?></option> 
                <?php endif; ?>
                <?php foreach ($r['options'] as $option) : 
                  
                  ?>
                        <option value="<?php echo($option['id']); ?>"<?php 
                        if ( $r['value'] == $option['id'] ) { echo ' selected=selected'; }
                        elseif ( $r['value'] == null && $r['std'] == $option['id'] ) { echo ' selected=selected'; }
                        echo ">".$option['title']; 
                        ?></option>
                 <?php endforeach; ?>
                    </select>
                    <span class="howto"><?php echo($r['desc']); ?></span>
                </td>
            </tr>
    <?php 
        $select = ob_get_clean();
        return $select;
    } 
    
    /**
     *
     * @param type $name
     * @return type $field
     */
    
    function upload($name = '', $args = array()) {
        $defaults = array(
            'error' => true,
            'class' => "widefat",
            'width' => "140",
            //'value' => (empty($args['value'])) ? '' : $args['value'],
        );
        
        $r = wp_parse_args($args, $defaults);
        $SG = new SatelliteGallery;
        $info = $SG->loadData($_REQUEST['id']);
                
        ob_start();        
        ?>
        <tr id="uploader" <?php echo ($info->source != 'satellite' && !empty($info->source)) ? 'style="display:none"' : '' ?>>
            <th class="verttop"><label><strong><?php echo $r['name']; ?></strong></label></th>
        <td>
            <?php
            $resize = false;
            // adjust values here
            $id = "images"; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == “img1” then $_POST[“img1”] will have all the image urls
            $svalue = ""; // this will be initial value of the above form field. Image urls.
            $multiple = true; // allow multiple files upload
            $width = ($resize) ? 1024 : null; // If you want to automatically resize all uploaded images then provide width here (in pixels)
            $height = ($resize) ? 1024 : null; // If you want to automatically resize all uploaded images then provide height here (in pixels)
            ?>

            <label>Upload and Order Images</label>
            <input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>" />
            <div class="plupload-upload-uic hide-if-no-js <?php if ($multiple): ?>plupload-upload-uic-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-upload-ui">
                <input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php esc_attr_e('Select Files'); ?>" class="btn btn-primary" />
                <span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span>
                <?php if ($width && $height): ?>
                        <span class="plupload-resize"></span><span class="plupload-width" id="plupload-width<?php echo $width; ?>"></span>
                        <span class="plupload-height" id="plupload-height<?php echo $height; ?>"></span>
                <?php endif; ?>
                <div class="filelist"></div>
            </div>
            <div class="plupload-thumbs <?php if ($multiple): ?>plupload-thumbs-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-thumbs">
            </div>
            <span class="howto clear"><?php echo($r['desc']); ?></span>
            <div class="clear"></div>

        </td>
        </tr>
        <?php 
        $upload = ob_get_clean();
        return $upload;
    }    
    
    /**
     *
     * @param type $name
     * @param type $args
     * @return type $field
     */
    function submit($name = '', $args = array()) {
        $Html = new SatelliteHtmlHelper;
        $defaults = array('class' => "button-primary");
        $r = wp_parse_args($args, $defaults);

        ob_start();
        ?><input class="<?php echo $r['class']; ?>" type="submit" name="<?php echo $Html->sanitize($name); ?>" value="<?php echo $name; ?>" /><?php
        $submit = ob_get_clean();
        return $submit;
    }

}
?>