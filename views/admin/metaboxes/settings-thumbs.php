<?php 
$styles = $this -> get_option('styles');
$Config = new SatelliteConfigHelper;
$Form = new SatelliteFormHelper;
$thumbOptions = $Config -> displayOption('thumbnail', 'Thumbnail');
error_log($thumbOptions);
?>

<table class="form-table">
	<tbody>
        <?php

            $Form->display($thumbOptions, 'Thumbnail');

        ?>
            <tr class="thumbheight">
                    <th><label for="styles.thumbheight"><?php _e('Thumbnail Height', SATL_PLUGIN_NAME); ?></label></th>
                    <td>
                            <input style="width:45px;" id="styles.thumbheight" type="text" name="styles[thumbheight]" value="<?php echo ($styles['thumbheight'] > 0) ? $styles['thumbheight'] : "75"; ?>" /> <?php _e('px', SATL_PLUGIN_NAME); ?>
                            <span class="howto"><?php _e('height of your thumbnails', SATL_PLUGIN_NAME); ?></span>
                    </td>
            </tr>
            <tr class="thumbspacing">
                <th><label for=""><?php _e('Thumbnail Spacing', SATL_PLUGIN_NAME); ?></label></th>
                <td>
                    <input type="text" style="width:45px;" name="styles[thumbspacing]" value="<?php echo $styles['thumbspacing']; ?>" id="thumbspacing" /> <?php _e('px', SATL_PLUGIN_NAME); ?>
                    <span class="howto"><?php _e('horizontal margin/spacing between thumbnails', SATL_PLUGIN_NAME); ?></span>
                </td>
            </tr>
            <tr class="thumbopacity">
                <th><label for="thumbopacity"><?php _e('Thumbnail Opacity', SATL_PLUGIN_NAME); ?></label></th>
                <td>
                    <input style="width:45px;" type="text" name="styles[thumbopacity]" value="<?php echo $styles['thumbopacity']; ?>" id="thumbopacity" /> <?php _e('&#37; <!-- percentage -->', SATL_PLUGIN_NAME); ?>
                    <span class="howto"><?php _e('default opacity of thumbnails when they are not hovered', SATL_PLUGIN_NAME); ?></span>
                </td>
            </tr>
            <tr class="thumbactive">
                <th><label for="thumbactive"><?php _e('Thumbnail Active Border', SATL_PLUGIN_NAME); ?></label></th>
                <td>
                    <input style="width:65px;" type="text" name="styles[thumbactive]" value="<?php echo $styles['thumbactive']; ?>" id="thumbactive" />
                    <span class="howto"><?php _e('border color (hexidecimal) for the active image thumbnail. default:#FFFFFF', SATL_PLUGIN_NAME); ?></span>
                </td>
            </tr>
            <tr class="thumbarea">
                <th><label for=""><?php _e('Thumbnail Area Width', SATL_PLUGIN_NAME); ?></label></th>
                <td>
                    <input type="text" style="width:45px;" name="styles[thumbarea]" value="<?php echo ($styles['thumbarea'] > 0) ? $styles['thumbarea'] : "300"; ?>" id="thumbarea" /> <?php _e('px', SATL_PLUGIN_NAME); ?>
                    <span class="howto"><?php _e('FullRight or FullLeft only: slideshow width + thumb area = full page width :)', SATL_PLUGIN_NAME); ?></span>
                </td>

            </tr>
            <tr class="thumbareamargin">
                <th><label for=""><?php _e('Thumbnail Area Margin', SATL_PLUGIN_NAME); ?></label></th>
                <td>
                    <input type="text" style="width:45px;" name="styles[thumbareamargin]" value="<?php echo ($styles['thumbareamargin'] > 0) ? $styles['thumbareamargin'] : "20"; ?>" id="thumbareamargin" /> <?php _e('px', SATL_PLUGIN_NAME); ?>
                    <span class="howto"><?php _e('FullRight or FullLeft only: how far should thumbnails be from image?', SATL_PLUGIN_NAME); ?></span>
                </td>
            </tr>
            <tr class="thumbmargin">
                <th><label for=""><?php _e('Thumbnails Margin', SATL_PLUGIN_NAME); ?></label></th>
                <td>
                    <input type="text" style="width:45px;" name="styles[thumbmargin]" value="<?php echo ($styles['thumbmargin'] > 0) ? $styles['thumbmargin'] : "10"; ?>" id="thumbareamargin" /> <?php _e('px', SATL_PLUGIN_NAME); ?>
                    <span class="howto"><?php _e('How far should the thumbnail row be from the image?)', SATL_PLUGIN_NAME); ?></span>
                </td>
            </tr>

	</tbody>
</table>