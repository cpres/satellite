<table class="form-table">
    <tbody>
        <tr>
            <th><label for="transition"><?php _e('Default Transition', SATL_PLUGIN_NAME); ?></label></th>
            <td>
                    <select name="transition" class="satellite_trans">
                        <option <?php echo ($this->get_option('transition') == "N") ? 'selected' : ''; ?> value="N"><?php _e('None', SATL_PLUGIN_NAME); ?></option> 
                        <option <?php echo ($this->get_option('transition') == "FB") ? 'selected' : ''; ?> value="FB"><?php _e('Fade Blend', SATL_PLUGIN_NAME); ?></option> 
                        <option <?php echo ($this->get_option('transition') == "FE") ? 'selected' : ''; ?> value="FE"><?php _e('Fade Empty', SATL_PLUGIN_NAME); ?></option> 
                        <option <?php echo ($this->get_option('transition') == "OHS") ? 'selected' : ''; ?> value="OHS"><?php _e('Horizontal-Slide', SATL_PLUGIN_NAME); ?></option> 
                        <option <?php echo ($this->get_option('transition') == "OVS") ? 'selected' : ''; ?> value="OVS"><?php _e('Vertical-Slide', SATL_PLUGIN_NAME); ?></option> 
                        <option <?php echo ($this->get_option('transition') == "OHP") ? 'selected' : ''; ?> value="OHP"><?php _e('Horizontal-Push', SATL_PLUGIN_NAME); ?></option> 
                        <option <?php echo ($this->get_option('transition') == "OVP") ? 'selected' : ''; ?> value="OVP"><?php _e('Vertical-Push', SATL_PLUGIN_NAME); ?></option> 
                    </select>
                <span class="howto"><?php _e('Orbits do not allow thumbnails', SATL_PLUGIN_NAME); ?></span>
            </td>
        </tr>
        <tr>
            <th><label for="responsive"><?php _e('Responsive Slideshow', SATL_PLUGIN_NAME); ?></label></th>
            <td>
                <label><input <?php echo ( $this->get_option('responsive') == 1 ) ? 'checked="checked"' : ''; ?> type="radio" name="responsive" value="1" /> <?php _e('Enable', SATL_PLUGIN_NAME); ?></label>
                <label><input <?php echo ( $this->get_option('responsive') == 0 ) ? 'checked="checked"' : $this->get_option('responsive'); ?> type="radio" name="responsive" value="0" /> <?php _e('Disable', SATL_PLUGIN_NAME); ?></label>
            </td>
        </tr>                
        <tr>
            <th><label for="autoslideY"><?php _e('Auto Slide', SATL_PLUGIN_NAME); ?></label></th>
            <td>
                <label><input onclick="jQuery('#autoslide_div').show();jQuery('#autoslide2_div').show();" <?php echo ($this->get_option('autoslide') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="autoslide" value="Y" id="autoslide2Y" /> <?php _e('Yes', SATL_PLUGIN_NAME); ?></label>
                <label><input onclick="jQuery('#autoslide_div').hide();jQuery('#autoslide2_div').hide();" <?php echo ($this->get_option('autoslide') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="autoslide" value="N" id="autoslide2N" /> <?php _e('No', SATL_PLUGIN_NAME); ?></label>
            </td>
        </tr>
    </tbody>
</table>
<script>
    jQuery(".satellite_trans").change(function () {
        //alert(jQuery("select option:selected").val());
        jQuery('#multislide_style_div').hide();
        if (jQuery("select option:selected").val() == 'OM') {
            jQuery('#multislide_style_div').show();
        }
        else {
            jQuery('#multislide_style_div').hide();
        }
    }).change();

</script>
    <div id="autoslide2_div" style="display:<?php echo ($this->get_option('autoslide') == "Y") ? 'block' : 'none'; ?>;">
        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="autospeed"><?php _e('Auto Speed', SATL_PLUGIN_NAME); ?></label></th>
                    <td>
                        <input type="text" style="width:45px;" name="autospeed2" value="<?php echo $this->get_option('autospeed2'); ?>" id="autospeed2" /> <?php _e('speed', SATL_PLUGIN_NAME); ?>
                        <span class="howto"><?php _e('default:5000 recommended:2000-12000', SATL_PLUGIN_NAME); ?><br/><?php _e('lower number for quicker length of time between sliding of images', SATL_PLUGIN_NAME); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <table class="form-table">
        <tbody>
            <tr class="duration">
                <th><label for="duration"><?php _e('Transition Speed', SATL_PLUGIN_NAME); ?></label></th>
                <td>
                    <input style="width:45px;" type="text" name="duration" value="<?php echo $this->get_option('duration'); ?>" id="duration" /> <?php _e('duration', SATL_PLUGIN_NAME); ?>
                    <span class="howto"><?php _e('default:700 recommended:300-2000', SATL_PLUGIN_NAME); ?><br/><?php _e('lower number for quicker transition of images', SATL_PLUGIN_NAME); ?></span>
                </td>
            </tr>
                <!--tr class="bullcenter">
                    <th><label for="bullcenter"><?php _e('Bullet Centering', SATL_PLUGIN_NAME); ?></label></th>
                    <td>
                        <label><input <?php echo ($this->get_option('bullcenter') == "true") ? 'checked="checked"' : ''; ?> type="radio" name="bullcenter" value="true" /> <?php _e('On', SATL_PLUGIN_NAME); ?></label>
                        <label><input <?php echo ($this->get_option('bullcenter') == "false") ? 'checked="checked"' : ''; ?> type="radio" name="bullcenter" value="false" /> <?php _e('Off', SATL_PLUGIN_NAME); ?></label>
                    </td>
                </tr-->
            <tr class="info">
                <th><label for="info"><?php _e('Show Captions', SATL_PLUGIN_NAME); ?></label></th>
                <td>
                    <label><input onclick="jQuery('#info_div').show();" <?php echo ( $this->get_option('information') == "Y" ) ? 'checked="checked"' : ''; ?> type="radio" name="information" value="Y" /> <?php _e('Yes', SATL_PLUGIN_NAME); ?></label>
                    <label><input onclick="jQuery('#info_div').hide();" <?php echo ( $this->get_option('information') == "N" ) ? 'checked="checked"' : $this->get_option('information'); ?> type="radio" name="information" value="N" /> <?php _e('No', SATL_PLUGIN_NAME); ?></label>
                </td>
            </tr>                
        </tbody>
    </table>
    <div id="info_div" style="display:<?php echo ($this->get_option('information') == "Y") ? 'block' : 'none'; ?>;">
        <table class="form-table">
            <tbody>
<!--                <tr>
                    <th><label for="infospeed"><?php _e('Information Speed', SATL_PLUGIN_NAME); ?></label></th>
                    <td>
                        <input type="text" style="width:45px;" name="infospeed" value="<?php echo $this->get_option('infospeed'); ?>" id="infospeed" />
                        <span class="howto"><?php _e('speed at which the information will slide in', SATL_PLUGIN_NAME); ?></span>
                    </td>
                </tr>-->
              <tr>
                    <th><label for="showhoverP"><?php _e('Caption Display Settings', SATL_PLUGIN_NAME); ?></label></th>
                    <td>
                        <?php $showh = $this->get_option('showhover'); ?>
                        <!--label><input <?php echo (empty($showh) || $this->get_option('showhover') == "S" ) ? 'checked="checked"' : ''; ?> type="radio" name="showhover" value="S" id="showhoverS" /> <?php _e('Scroll Up', SATL_PLUGIN_NAME); ?></label-->
                        <label><input <?php echo ( empty($showh) || $this->get_option('showhover') == "P" ) ? 'checked="checked"' : ''; ?> type="radio" name="showhover" value="P" id="showhoverP" /> <?php _e('Permanently Show', SATL_PLUGIN_NAME); ?></label>
                        <label><input <?php echo ( $this->get_option('showhover') == "H" ) ? 'checked="checked"' : ''; ?> type="radio" name="showhover" value="H" id="showhoverH" /> <?php _e('Mouse Hover Only', SATL_PLUGIN_NAME); ?></label>
                        <span class="howto"><?php _e('How do you want to display the information (caption) bar?', SATL_PLUGIN_NAME); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>    
    <div id="multislide_style_div" style="display:<?php echo ($this->get_option('transition') == "OM") ? 'block' : 'none'; ?>;">
        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="multicols"><?php _e('Number of Images in a Row', SATL_PLUGIN_NAME); ?></label></th>
                    <td>
                        <select name="multicols" class="satellite_trans">
                            <?php for ($i = 1; $i < 20; $i++) { ?>
                                <option <?php echo ($this->get_option('multicols') == $i) ? 'selected' : ''; ?> value="<?php echo($i); ?>"><?php echo($i); ?></option>
                            <?php } ?>
                        </select>
                        <span class="howto"><?php _e('Number of columns in your multi-image slider', SATL_PLUGIN_NAME); ?></span>
                    </td>
                </tr>
                <tr>
                    <th><label for="dropshadow"><?php _e('Drop Shadow', SATL_PLUGIN_NAME); ?></label></th>
                    <td>
                        <label><input onclick="jQuery('#dropshadow_div').show();" <?php echo ($this->get_option('dropshadow') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="dropshadow" value="Y" id="dropshadowY" /> <?php _e('Yes', SATL_PLUGIN_NAME); ?></label>
                        <label><input onclick="jQuery('#dropshadow_div').hide()" <?php echo ($this->get_option('dropshadow') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="dropshadow" value="N" id="dropshadowN" /> <?php _e('No', SATL_PLUGIN_NAME); ?></label>
                    </td>
                </tr>			
            </tbody>
        </table>
    </div>
    <div id="information_div" style="display:<?php echo ($this->get_option('information') == "Y") ? 'block' : 'none'; ?>;">
        <table class="form-table">
            <tbody>
                <!--tr>
                    <th><label for="infospeed"><?php _e('Caption Speed', SATL_PLUGIN_NAME); ?></label></th>
                    <td>
                        <input type="text" style="width:45px;" name="infospeed" value="<?php echo $this->get_option('infospeed'); ?>" id="infospeed" />
                        <span class="howto"><?php _e('speed at which the information will slide in', SATL_PLUGIN_NAME); ?></span>
                    </td>
                </tr-->
                <!--tr>
                    <th><label for="showhover"><?php _e('Information Display Settings', SATL_PLUGIN_NAME); ?></label></th>
                    <td>
                        <?php $showh = $this->get_option('showhover'); ?>
                        <label><input <?php echo (empty($showh) || $this->get_option('showhover') == "S") ? 'checked="checked"' : ''; ?> type="radio" name="showhover" value="S" id="showhoverS" /> <?php _e('Scroll Up', SATL_PLUGIN_NAME); ?></label>
                        <label><input <?php echo ($this->get_option('showhover') == "P") ? 'checked="checked"' : ''; ?> type="radio" name="showhover" value="P" id="showhoverP" /> <?php _e('Permanently Show', SATL_PLUGIN_NAME); ?></label>
                        <label><input <?php echo ($this->get_option('showhover') == "H") ? 'checked="checked"' : ''; ?> type="radio" name="showhover" value="H" id="showhoverH" /> <?php _e('Mouse Hover Only', SATL_PLUGIN_NAME); ?></label>
                        <span class="howto"><?php _e('How do you want to display the information (caption) bar?', SATL_PLUGIN_NAME); ?></span>
                    </td>
                </tr-->
            </tbody>
        </table>
    </div>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="thumbnailsN"><?php _e('Show Thumbnails', SATL_PLUGIN_NAME); ?></label></th>
                <td>
                    <label><input onclick="jQuery('#thumbnails_div').show();" <?php echo ($this->get_option('thumbnails') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="thumbnails" value="Y" id="thumbnailsY" /> <?php _e('Yes', SATL_PLUGIN_NAME); ?></label>
                    <label><input onclick="jQuery('#thumbnails_div').hide();" <?php echo ($this->get_option('thumbnails') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="thumbnails" value="N" id="thumbnailsN" /> <?php _e('No', SATL_PLUGIN_NAME); ?></label>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                <div id="thumbnails_div" style="display:<?php echo ($this->get_option('thumbnails') != "N" ) ? 'block' : 'none'; ?>;">
                <table class="form-table">
                    <tbody>
                        <tr class="thumbposition">
                            <th><label for="thubmposition"><?php _e('Default Thumbnail Position', SATL_PLUGIN_NAME); ?></label></th>
                            <td>
                                <!--label><input <?php echo ($this->get_option('thumbposition') == "top") ? 'checked="checked"' : ''; ?> type="radio" name="thumbposition" value="top" id="thumbpositiontop" /> <?php _e('Top', SATL_PLUGIN_NAME); ?></label-->
                                <label><input <?php echo ($this->get_option('thumbposition') == "B") ? 'checked="checked"' : ''; ?> type="radio" name="thumbposition" value="B" id="thumbpositionbottom" /> <?php _e('Bottom', SATL_PLUGIN_NAME); ?></label>
                                <label><input <?php echo ($this->get_option('thumbposition') == "FL") ? 'checked="checked"' : ''; ?> type="radio" name="thumbposition" value="FL" id="thumbpositionfl" /> <?php _e('Full Left', SATL_PLUGIN_NAME); ?></label>
                                <label><input <?php echo ($this->get_option('thumbposition') == "FR") ? 'checked="checked"' : ''; ?> type="radio" name="thumbposition" value="FR" id="thumbpositionfr" /> <?php _e('Full Right', SATL_PLUGIN_NAME); ?></label>
                            </td>
                        </tr>
                        <!--tr class="thumbscrollspeed">
                            <th><label for="thumbscrollspeed"><?php _e('Thumbnails Scroll Speed', SATL_PLUGIN_NAME); ?></label></th>
                            <td>
                                <input class="widefat" style="width:45px;" name="thumbscrollspeed" value="<?php echo $this->get_option('thumbscrollspeed'); ?>" id="thumbscrollspeed" /> <?php _e('speed', SATL_PLUGIN_NAME); ?>
                                <span class="howto"><?php _e('default:5 recommended:1-20', SATL_PLUGIN_NAME); ?></span>
                            </td>
                        </tr-->
                    </tbody>
                </table>
                </div>
                </td>
            </tr>
            
        </tbody>
    </table>