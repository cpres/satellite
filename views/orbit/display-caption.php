<?php global $satellite_init_ok; ?>
<?php
$images = $this->get_option('Images');
$description = ($frompost) ? $slider->post_content: $slider->description;
$textlocation = ($frompost) ? '': $slider->textlocation;
$title = ($frompost) ? $slider->post_title : $slider->title;
$spanid = ($frompost) ? "post-".$slider->ID :"custom".$satellite_init_ok."-".$i;
$pagelink = $images['pagelink'];
//$this->log_me($slider);
?>
<span class="orbit-caption<?php echo ($textlocation == 'BR' || $textlocation == 'TR') ? ' sattext sattext' . $textlocation : '' ?>
    <?php echo($this->get_option('thumbnails_temp') == 'Y') ? ' thumb-on' : ''; ?>" id='<?php echo ($spanid); ?>'>
    
    <h5 class="orbit-title<?php echo($style['infotitle']) ?> size-<?php echo $fontsize;?>"><?php echo $title; ?></h5>
    <p class="size-<?php echo $fontsize;?>"><?php echo $description ?> </p>
<?php if (!$frompost && $slider->uselink == "Y" && !empty($slider->link) && $slider->more) : ?>
        <div class="more-img">
            <a href="<?php echo $slider->link; ?>" title="<?php echo $slider->title; ?>" target="<?php echo ($pagelink == "S") ? "_self" : "_blank" ?>">
                <img src="<?php echo $this->Html->image_id($slider->more); ?>" />
            </a>
        </div>
<?php endif; ?>
</span>  
