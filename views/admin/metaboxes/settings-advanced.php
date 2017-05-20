<?php
    $Config = new SatelliteConfigHelper;
    $Form = new SatelliteFormHelper;
    $options = $Config -> displayOption('advanced', 'Advanced');
?>

<table class="form-table">
    <tbody>
    <?php $Form -> display($options, 'Advanced'); ?>

        <tr>
            <th><label for="embedss"><?php _e('Enable slideshow as embed?', SATL_PLUGIN_NAME); ?></label></th>
            <td>
                <select name="embedss" class="satellite_trans">
                    <option <?php echo ($this->get_option('embedss') == "Y") ? 'selected' : ''; ?> value="Y"><?php _e('Yes', SATL_PLUGIN_NAME); ?></option> 
                    <option <?php echo ($this->get_option('embedss') == "N") ? 'selected' : ''; ?> value="N"><?php _e('No', SATL_PLUGIN_NAME); ?></option> 
                </select>
                <span class="howto"><?php _e('[slideshow] will work as an embed tag', SATL_PLUGIN_NAME); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="wpattach"><?php _e('Enable WP Attachment Page?', SATL_PLUGIN_NAME); ?></label></th>
            <td>
                <select name="wpattach" class="satellite_trans">
                    <option <?php echo ($this->get_option('wpattach') == "Y") ? 'selected' : ''; ?> value="Y"><?php _e('Yes', SATL_PLUGIN_NAME); ?></option> 
                    <option <?php echo ($this->get_option('wpattach') == "N") ? 'selected' : ''; ?> value="N"><?php _e('No', SATL_PLUGIN_NAME); ?></option> 
                </select>

            	<span class="howto"><?php _e('If yes, Wordpress images will open to their associated attachment page', SATL_PLUGIN_NAME); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="satwiz"><?php _e('Satellite Embed Wizard: ', SATL_PLUGIN_NAME); ?><img src="<?php echo(SATL_PLUGIN_URL.'/images/icon.png');?>" /></label></th>
            <td>
                <select name="satwiz" class="satellite_trans">
                    <option <?php echo ($this->get_option('satwiz') == "Y") ? 'selected' : ''; ?> value="Y"><?php _e('On', SATL_PLUGIN_NAME); ?></option> 
                    <option <?php echo ($this->get_option('satwiz') == "N") ? 'selected' : ''; ?> value="N"><?php _e('Off', SATL_PLUGIN_NAME); ?></option> 
                </select>

            	<span class="howto"><?php _e('Enable the [satellite] post embed wizard option. Turn off if editor toolbar breaks.', SATL_PLUGIN_NAME); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="shortreq"><?php _e('Shortcode Requirement: ', SATL_PLUGIN_NAME); ?></label></th>
            <td>
                <select name="shortreq" class="satellite_trans">
                    <option <?php echo ($this->get_option('shortreq') == "Y") ? 'selected' : ''; ?> value="Y"><?php _e('On', SATL_PLUGIN_NAME); ?></option> 
                    <option <?php echo ($this->get_option('shortreq') == "N") ? 'selected' : ''; ?> value="N"><?php _e('Off', SATL_PLUGIN_NAME); ?></option> 
                </select>

            	<span class="howto"><?php _e('Load the CSS and Javascript on every page, not just when using this plugin.', SATL_PLUGIN_NAME); ?></span>
            </td>
        </tr>
    </tbody>
</table>