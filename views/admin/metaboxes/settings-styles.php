<?php
//$this->log_me($this);die();
$styles = $this -> get_option('styles');
?>
<table class="form-table">
	<tbody>
		<tr class="navbuttons">
			<th><label for="styles.navbuttons"><?php _e('Navigational Buttons', SATL_PLUGIN_NAME); ?></label></th>
			<td>
                            <div class="alignleft" style="width:206px"><img src="<?php echo(SATL_PLUGIN_URL.'/images/nav-options.gif')?>"></div>

			<?php if ( SATL_PRO ) {
				require SATL_PLUGIN_DIR . '/pro/settings-navbuttons.php';
			} else {
			?>
                <select disabled>
                    <option>1- Default </option> <?php _e('1- Default', SATL_PLUGIN_NAME); ?>
                </select>
			<?php } ?>
				<span class="howto clear"><?php _e('Choose your nav arrows for left and right transitioning', SATL_PLUGIN_NAME); ?></span>
			</td>
		</tr>
                <tr class="navpush">
                    <th><label for="styles.navpush"><?php _e('Navigational Push', SATL_PLUGIN_NAME); ?></label></th>
			<td>
                            <input style="width:45px;" id="styles.navpush" type="text" name="styles[navpush]" value="<?php echo $styles['navpush']; ?>" /> <?php _e('px', SATL_PLUGIN_NAME); ?>
                            <span class="howto"><?php _e('How far your navigational arrows are pushed away from the slideshow, 0 or 78 are most popular', SATL_PLUGIN_NAME); ?></span>
			</td>
                </tr>
                <tr class="nav_opacity">
                    <th><label for="styles.nav_opacity"><?php _e('Navigation Opacity', SATL_PLUGIN_NAME); ?></label></th>
			<td>
                            <input style="width:45px;" id="styles.nav_opacity" type="text" name="styles[nav_opacity]" value="<?php echo $styles['nav_opacity']; ?>" /> <?php _e('%', SATL_PLUGIN_NAME); ?>
                            <span class="howto"><?php _e('What opacity does the navigation buttons start at? (\'0\' to \'70\', Default: 30)', SATL_PLUGIN_NAME); ?></span>
			</td>
                </tr>
 		<tr class="gal-width">
			<th><label for="styles.width"><?php _e('Gallery Width', SATL_PLUGIN_NAME); ?></label></th>
			<td>
                            <input style="width:45px;" id="styles.width" type="text" name="styles[width]" value="<?php echo $styles['width']; ?>" /> <?php _e('px', SATL_PLUGIN_NAME); ?>
                            <span class="howto"><?php _e('width of the slideshow gallery', SATL_PLUGIN_NAME); ?></span>
			</td>
		</tr>
		<tr class="gal-height">
			<th><label for="styles.height"><?php _e('Gallery Height', SATL_PLUGIN_NAME); ?></label></th>
			<td>
                            <input style="width:45px;" id="styles.height" type="text" name="styles[height]" value="<?php echo $styles['height']; ?>" /> <?php _e('px', SATL_PLUGIN_NAME); ?>
                            <span class="howto"><?php _e('height of the slideshow gallery', SATL_PLUGIN_NAME); ?></span>
			</td>
		</tr>
		<tr class="background">
			<th><label for="styles.background"><?php _e('Slideshow Background', SATL_PLUGIN_NAME); ?></label></th>
			<td>
                            <input type="text" name="styles[background]" value="<?php echo $styles['background']; ?>" id="styles.background" style="width:65px;" />
			</td>
		</tr>
		<tr>
			<th><label for="styles.infotitle"><?php _e('Caption Title', SATL_PLUGIN_NAME); ?></label></th>
			<td>
                            <select name="styles[infotitle]">

                                <option <?php echo (empty($styles['infotitle']) || $styles['infotitle'] == "0") ? 'selected="selected"' : ''; ?> value="0" />None - Hidden </option> <?php _e('1- Default', SATL_PLUGIN_NAME); ?>
                                <option <?php echo ($styles['infotitle'] == "1") ? 'selected="selected"' : ''; ?> value="1" />Small</option> <?php _e('Small', SATL_PLUGIN_NAME); ?>
                                <option <?php echo ($styles['infotitle'] == "2") ? 'selected="selected"' : ''; ?> value="2" />Medium</option> <?php _e('Medium', SATL_PLUGIN_NAME); ?>
                                <option <?php echo ($styles['infotitle'] == "3") ? 'selected="selected"' : ''; ?> value="3" />Large</option> <?php _e('Large', SATL_PLUGIN_NAME); ?>
                                <option <?php echo ($styles['infotitle'] == "4") ? 'selected="selected"' : ''; ?> value="4" />XLarge</option> <?php _e('X-Large', SATL_PLUGIN_NAME); ?>

                            </select>			
                        </td>
		</tr>
		<tr>
			<th><label for="styles.infobackground"><?php _e('Caption Background', SATL_PLUGIN_NAME); ?></label></th>
			<td>
				<input type="text" name="styles[infobackground]" value="<?php echo $styles['infobackground']; ?>" id="styles.infobackground" style="width:65px;" />
			</td>
		</tr>
		<tr>
			<th><label for="styles.infocolor"><?php _e('Caption Text Color', SATL_PLUGIN_NAME); ?></label></th>
			<td>
				<input type="text" name="styles[infocolor]" value="<?php echo $styles['infocolor']; ?>" id="styles.infocolor" style="width:65px;" />
			</td>
		</tr>
		<tr>
			<th><label for="styles.playshow"><?php _e('Show Play Button', SATL_PLUGIN_NAME); ?></label></th>
			<td>
                            <select name="styles[playshow]">

                                <option <?php echo (empty($styles['playshow']) || $styles['playshow'] == "A") ? 'selected="selected"' : ''; ?> value="A" /><?php _e('Always', SATL_PLUGIN_NAME); ?></option> 
                                <option <?php echo ($styles['playshow'] == "P") ? 'selected="selected"' : ''; ?> value="P" /> <?php _e('Only on Auto Play', SATL_PLUGIN_NAME); ?></option>
                                <option <?php echo ($styles['playshow'] == "N") ? 'selected="selected"' : ''; ?> value="N" /><?php _e('Never', SATL_PLUGIN_NAME); ?></option> 
                            </select>			
                        </td>
		</tr>
		<tr>
			<th><label for="styles.infomin"><?php _e('Minimize Caption Bar Height?', SATL_PLUGIN_NAME); ?></label></th>
			<td>
				<label><input <?php echo (empty($styles['infomin']) || $styles['infomin'] == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="styles[infomin]" value="Y" id="styles.infomin_Y" /> <?php _e('Yes, minimize', SATL_PLUGIN_NAME); ?></label>
				<label><input <?php echo ($styles['infomin'] == "N") ? 'checked="checked"' : ''; ?> type="radio" name="styles[infomin]" value="N" id="styles.infomin_N" /> <?php _e('No, keep styling', SATL_PLUGIN_NAME); ?></label>
				<span class="howto"><?php _e('Keep your theme styling for &quot;H5&quot; and &quot;p&quot;? Or minimize them.', SATL_PLUGIN_NAME); ?></span>
			</td>
		</tr>
	</tbody>
</table>