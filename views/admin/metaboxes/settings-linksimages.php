<?php
    $Config = new SatelliteConfigHelper;
    $Form = new SatelliteFormHelper;
    $options = $Config -> displayOption('images', 'Images');

?>
<table class="form-table">
	<tbody>
    <?php $Form -> display($options, 'Images'); ?>
  	<?php if ( SATL_PRO ) {		?>
		<tr>
        	<th><label for="captionlink_N"><?php _e('Use Caption Field as a Link?', SATL_PLUGIN_NAME); ?></label></th>
            <td>
                <label><input <?php echo ($this -> get_option('captionlink') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="captionlink" value="S" id="captionlink_Y" /> <?php _e('Yes', SATL_PLUGIN_NAME); ?></label>
            	<label><input <?php echo ($this -> get_option('captionlink') == ("N"||"")) ? 'checked="checked"' : ''; ?> type="radio" name="captionlink" value="B" id="captionlink_N" /> <?php _e('No', SATL_PLUGIN_NAME); ?></label>
            	<span class="howto"><?php _e('If using the <strong>Wordpress Image Gallery</strong> you can still link out to a new page by using the Caption Field', SATL_PLUGIN_NAME); ?></span>
            </td>
        </tr>
        
		<?php } ?>
        
    </tbody>
</table>