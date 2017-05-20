<?php

if (!empty($slides)) :
    $displayFirstSatellite = $this -> render('default', array('slides' => $slides, 'frompost' => false, 'respExtra' => 175), false, 'orbit');
    $displaySplash = $this -> get_option('splash');
    ?>
    <div class='satl-gal-wrap'>
        <div class='satl-gal-titles'>
            <?php 
            $i=0;
            foreach ($galleries as $gallery) : 
                $info = $this -> Gallery -> find(array("id" => $gallery));
                $current = (isset($_POST['slideshow']) && $_POST['slideshow'] == $info->id) ? 'current' : '';
                if ($i == 0)
                    $firstID = $info->id;
                ?>
                <div class='satl-gal-title gal<?php echo($info->id);?>'>
                    <a href="javascript:void(0);" onclick="showGallerySatellite(<?php echo($info->id);?>);">
                        <?php echo ($info->title);?>
                    </a>
                </div>
                <?php 
                $i++;
            endforeach; ?>
        </div>
        <div class="galleries-satl-wrap">
            <?php 
            if ($displaySplash) : 
                echo "<div class='splashstart sorbit-wide absoluteCenter'";
                echo "<a href='javascript:void(0);' onclick='showGallerySatellite(".$firstID.");'>";
                echo "<img class='absoluteCenter play' src='".SATL_PLUGIN_URL."/images/playbutton.png' alt='Play Slideshow'/>";
                echo "<img class='absoluteCenter splash' src='".$this->Html->image_url($slides[0]->image)."' alt='Play Slideshow'/>";
                echo "</a>";
                echo "</div>";
            else :
                echo $displayFirstSatellite; 
            endif;?>
        </div>
        
    </div>
    <div style="clear:both;"></div>
<?php    
endif;


