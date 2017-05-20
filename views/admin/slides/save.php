<?php
global $post, $post_ID;
$post_ID = 1;
wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
$single = ($_GET['single']) ? $_GET['single'] : null;

//$slides = $this -> Slide -> find_all(array('section'=>(int) stripslashes($single)), null, array('order', "ASC"));

$shortname = "satl";
$ptypes1 = get_post_types(array('public' => true),'names','and');
$ptypes = array_push($ptypes1, 'resume');
$slide = $this -> Slide -> getData();
//echo (empty($this -> Slide -> data)) ?  "empty " : "not empty";
//print_r($this -> Slide );die();

$slideOptions = $this -> Config -> displayOption('slide', $this -> Slide);

$useLinkOptions = array (
array(  "name"      => "Link To",
        "desc"      => "link/URL to go to when a user clicks the slide eg. http://www.domain.com/mypage/",
        "id"        => "link",
        "type"      => "text",
        "value"     => $slide -> link,
        "std"       => "http://"),
array(  "name"      => "More Image",
        "desc"      => "From here you can select an image if you have a Gallery with the title 'More'",
        "id"        => "more",
        "type"      => "select",
        "value"     => (empty($slide)) ? $slide -> more : null,
        "std"       => "Select an Image",
        "options"   => $this -> Slide -> getGalleryImages("More"))
);
?>  <div class="wrap satl-settings">
	<h2><?php _e('Save a Slide', SATL_PLUGIN_NAME); ?></h2>

	<form action="<?php echo $this -> url; ?>&amp;method=save&amp;single=<?php echo( $single );?>" method="post" enctype="multipart/form-data" class="satl_table">
		<input type="hidden" name="Slide[id]" value="<?php echo (isset($slide -> id)) ? $slide->id : null; ?>" />
		<input type="hidden" name="Slide[slide_order]" value="<?php echo (isset($slide -> slide_order)) ? $slide -> slide_order : 0; ?>" />
		<table id="satl_table">
                    <tbody>
                        <?php if ($slide->image) : ?>
                        <tr><td></td>
                            <td><a href="<?php echo $this -> Html -> image_url($slide -> image); ?>"
                                   title="<?php echo $slide -> title; ?>" class="thickbox">
                                    <img src="<?php echo $this->Html->image_url($this->Html->thumbname($slide->image)); ?>" alt="<?php echo $this -> Html -> sanitize($slide -> title); ?>" />
                                </a>
                            </td>
                        </tr>
                        <?php endif;?>
<!--                        --><?php //$this->log_me($this);die();?>
                        <?php $this -> Form -> display($slideOptions, 'Slide'); ?>
                        <tr>
                            <th><label for="Slide.type.file"><?php _e('Image Type', SATL_PLUGIN_NAME); ?></label></th>
                        <td>
                            <label><input onclick="jQuery('#typediv_file').show(); jQuery('#typediv_url').hide(); jQuery('#typediv_existing').hide();" <?php echo (empty($this -> Slide -> data -> type) || $this -> Slide -> data -> type == "file") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[type]" value="file" id="Slide.type.file" /> <?php _e('Upload File (recommended)', SATL_PLUGIN_NAME); ?></label>
                            <label><input onclick="jQuery('#typediv_url').show(); jQuery('#typediv_file').hide(); jQuery('#typediv_existing').hide();" <?php echo ($this -> Slide -> data -> type == "url") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[type]" value="url" id="Slide.type.url" /> <?php _e('Specify URL', SATL_PLUGIN_NAME); ?></label>
                            <label><input onclick="jQuery('#typediv_existing').show(); jQuery('#typediv_file').hide();jQuery('#typediv_url').hide();" <?php echo ($this -> Slide -> data -> type == "existing") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[type]" value="existing" id="Slide.type.existing" /> <?php _e('Use Existing Image', SATL_PLUGIN_NAME); ?></label>
                            <?php echo (!empty($this -> Slide -> errors['type'])) ? '<div style="color:red;">' . $this -> Slide -> errors['type'] . '</div>' : ''; ?>
                            <span class="howto"><?php _e('do you want to upload an image or specify a local/remote image URL?', SATL_PLUGIN_NAME); ?></span>
                        </td>
                        </tr>
                    </tbody>
                </table>

        <div id="typediv_file" style="display:<?php echo (empty($slide -> type) || $slide -> type == "file") ? 'block' : 'none'; ?>;">
        	<table id="satl_table" class="form-table">
            	<tbody>
                	<tr>
                    	<th><label for="Slide.image_file"><?php _e('Choose Image', SATL_PLUGIN_NAME); ?></label></th>
                        <td>
                        	<input type="file" name="image_file" value="" id="Slide.image_file" />
                            <span class="howto"><?php _e('choose your image file from your computer. JPG, PNG, GIF, SWF are supported.', SATL_PLUGIN_NAME); ?></span>
                            <?php echo (!empty($this -> Slide -> errors['image_file'])) ? '<div style="color:red;">' . $this -> Slide -> errors['image_file'] . '</div>' : ''; ?>

                                <input type="hidden" name="Slide[image_oldfile]" value="<?php echo esc_attr(stripslashes($this -> Slide -> data -> image)); ?>" />

                                <?php echo $this->Config->displayThumbnailInfo($this->Html, $this -> Slide -> data -> image);?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="typediv_url" style="display:<?php echo ($this -> Slide -> data -> type == "url") ? 'block' : 'none'; ?>;">
            <table class="satl_table">
                <tbody>
                    <tr>
                        <th><label for="Slide.image_url"><?php _e('Image URL', SATL_PLUGIN_NAME); ?></label></th>
                        <td>
                            <input class="widefat" type="text" name="Slide[image_url]" value="<?php echo esc_attr($this -> Slide -> data -> image_url); ?>" id="Slide.image_url" />
                            <span class="howto"><?php _e('Local or remote image location eg. http://domain.com/path/to/image.jpg', SATL_PLUGIN_NAME); ?></span>
                            <?php echo (!empty($this -> Slide -> errors['image_url'])) ? '<div style="color:red;">' . $this -> Slide -> errors['image_url'] . '</div>' : ''; ?>
                            <?php echo $this->Config->displayThumbnailInfo($this->Html, $this -> Slide -> data -> image);?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="typediv_existing" style="display:<?php echo ($this -> Slide -> data -> type == "existing") ? 'block' : 'none'; ?>;">
            <table class="satl_table">
                <tbody>
                    <tr>
                        <th><label for="Slide.image_existing"><?php _e('Choose an Image', SATL_PLUGIN_NAME); ?></label></th>
                        <td>
                            <select name="Slide[image_existing]" id="Slide.image_existing" />
                            <!--option value="<?php echo esc_attr($this -> Slide -> data -> image_existing); ?>"-->
                            <?php $slides = $this->Image->getAllCustomImages($this -> Slide); ?>
                            <?php foreach ($slides as $slide) : ?>
                              <?php $check = ($this -> Slide -> data -> image == $slide->image) ? "selected" : null;?>
                              <option value="<?php echo $slide -> image;?>" <?php echo $check;?> ><?php echo $slide -> title;?></option>
                            <?php endforeach; ?>
                            </select>
                            <span class="howto"><?php _e('Choose an image from the ones already uploaded', SATL_PLUGIN_NAME); ?></span>
                            <?php echo (!empty($this -> Slide -> errors['image_existing'])) ? '<div style="color:red;">' . $this -> Slide -> errors['image_existing'] . '</div>' : ''; ?>
                            <?php echo $this->Config->displayThumbnailInfo($this->Html,$this -> Slide -> data -> image);?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    <table>
        <tbody>
                    <tr>
                        <th><label for="Slide_userlink_N"><?php _e('Use Link', SATL_PLUGIN_NAME); ?></label></th>
                        <td>
                                <label><input onclick="jQuery('#Slide_uselink_div').show();" <?php echo ($this -> Slide -> data -> uselink == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[uselink]" value="Y" id="Slide_uselink_Y" /> <?php _e('Yes', SATL_PLUGIN_NAME); ?></label>
                                <label><input onclick="jQuery('#Slide_uselink_div').hide();" <?php echo (empty($this -> Slide -> data -> uselink) || $this -> Slide -> data -> uselink == "N") ? 'checked="checked"' : ''; ?> type="radio" name="Slide[uselink]" value="N" id="Slide_uselink_N" /> <?php _e('No', SATL_PLUGIN_NAME); ?></label>
            <span class="howto"><?php _e('set this to Yes to link this slide to a link/URL of your choice.', SATL_PLUGIN_NAME); ?></span>
                        </td>
                    </tr>
                </tbody>
        </table>

        <div id="Slide_uselink_div" style="display:<?php echo ($this -> Slide -> data -> uselink == "Y") ? 'block' : 'none'; ?>;">
            <table class="">
                    <tbody>
                <?php $this -> Form -> display($useLinkOptions, 'Slide'); ?>
                    </tbody>
            </table>
        </div>

        <p class="submit">
                <input class="button-primary" type="submit" name="submit" value="<?php _e('Save Slide', SATL_PLUGIN_NAME); ?>" />
        </p>
	</form>
</div>