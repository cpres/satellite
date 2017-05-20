<?php
//$this->log_me($this);die();
    $Config = new SatelliteConfigHelper;
    $Form = new SatelliteFormHelper;
    $waterOptions = $Config -> displayOption('watermark', 'Watermark');
    $preloadOptions = $Config -> displayOption('preloader', 'Preloader');
?>
<table class="form-table">
    <tbody>
        <?php 
        if ($this->canPremiumDoThis('watermark')) {
            $Form->display($waterOptions, 'Watermark');
        }
        $Form->display($preloadOptions, 'Preloader');
        ?>
        <tr>
            <th><label for="keyboard"><?php _e('Keyboard Recognition', SATL_PLUGIN_NAME); ?></label></th>
            <td>
                <label><input <?php echo ($this->get_option('keyboard') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="keyboard" value="Y" /> <?php _e('On', SATL_PLUGIN_NAME); ?></label>
                <label><input <?php echo ($this->get_option('keyboard') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="keyboard" value="N" /> <?php _e('Off', SATL_PLUGIN_NAME); ?></label>
                <span class="howto"><?php _e('Left and Right keystrokes will scroll slideshow to next or previous', SATL_PLUGIN_NAME); ?></span>
            </td>
        </tr>        
        <tr>
            <th><label for="manager"><?php _e('Who can edit?', SATL_PLUGIN_NAME); ?></label></th>
            <td>
                <select name="manager">
                    <option <?php echo ($this->get_option('manager') == "manage_options") ? 'selected' : ''; ?> value="manage_options">Administrator</option> 
                    <option <?php echo ($this->get_option('manager') == "edit_pages") ? 'selected' : ''; ?> value="edit_pages">Editor</option> 
                    <option <?php echo ($this->get_option('manager') == "publish_posts") ? 'selected' : ''; ?> value="publish_posts">Author</option> 
                </select>
            </td>
        </tr>        
    </tbody>
</table>
