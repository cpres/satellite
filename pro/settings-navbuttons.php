<?php
$nb = (isset($styles['navbuttons'])) ? $styles['navbuttons'] : null;
?>
<select name="styles[navbuttons]">

	<option <?php echo (empty($nb) || $styles['navbuttons'] == "0") ? 'selected="selected"' : ''; ?> value="0" />1- Default </option> <?php _e('1- Default', SATL_PLUGIN_NAME); ?>
	<option <?php echo ($nb == "1") ? 'selected="selected"' : ''; ?> value="1" />2- Box</option> <?php _e('2- Box', SATL_PLUGIN_NAME); ?>
	<option <?php echo ($nb == "2") ? 'selected="selected"' : ''; ?> value="2" />3- Circle</option> <?php _e('3- Circle', SATL_PLUGIN_NAME); ?>
	<option <?php echo ($nb == "3") ? 'selected="selected"' : ''; ?> value="3" />4- Simple</option> <?php _e('4- Simple', SATL_PLUGIN_NAME); ?>
	<option <?php echo ($nb == "N") ? 'selected="selected"' : ''; ?> value="N" />None</option> <?php _e('None', SATL_PLUGIN_NAME); ?>

</select>