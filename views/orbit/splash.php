<?php
global $satellite_init_ok;
if (!empty($slides)) :
    $displayFirstSatellite = $this -> render('default', array('slides' => $slides, 'frompost' => false), false, 'orbit');
    ?>
        <div id="splash-satl-wrap-<?php echo($satellite_init_ok);?>" class="splash-satl-wrap
                <?php echo($this->get_option('thumbnails_temp') == 'Y') ? ' default-thumbs' : ''; ?>
                <?php echo($align) ? ' satl-align-' . $align : ''; ?>
             ">

            <?php 
                echo "<a href='javascript:void(0);' onclick='showSoloSatellite(".intval($slides[0]->section).",".$satellite_init_ok.");'>";
                echo "<img class='absoluteCenter play' src='".SATL_PLUGIN_URL."/images/playbutton.png' alt='Play Slideshow'/>";
                echo "<img class='absoluteCenter splash' src='".$this->Html->image_url($slides[0]->image)."' alt='Play Slideshow'/>";
                echo "</a>";
                echo($this->get_option('thumbnails_temp') == 'Y') ? '<div class="splash-thumb-wrap"><div class="splash-thumbs"></div></div>' : '';
                ?>
            
        </div>
<?php    
endif;

$styles = $this->get_option('styles');
$thumbNum = round( $styles['width'] / $styles['thumbheight']);
?>
<script type="text/javascript">
    var thumbDivs = new Array();
    for (var n=0;n<<?php echo $thumbNum;?>;n++)
    {thumbDivs[n]="<div class='the-thumb'></div>";}
    
    for (var i = 0; i < thumbDivs.length; i++) {
        jQuery('.splash-thumbs').append( thumbDivs[i] )
    }

    
</script>