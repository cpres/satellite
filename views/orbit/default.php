
<?php
global $satellite_init_ok;
global $post;
if (!empty($slides)) :
    $style = $this->get_option('styles');
    $images = $this->get_option('Images');
    $imagesbox = $images['imagesbox'];
    $pagelink = $images['pagelink'];
    $textloc = $this->get_option('textlocation');
    $responsive = $this->get_option('responsive');
    $respExtra = (isset($respExtra)) ? $respExtra : 0;
    $align = $this->get_option('align');
    $pID = (isset($orig_post)) ? $orig_post : $post->ID;

    if (!$frompost) {
        $this->Gallery->loadData($slides[0]->section);
        $sidetext = $this -> Gallery -> capLocation($this->Gallery->data->capposition,$slides[0]->section);
    }
    ?>


    <?php if ($frompost) : ?>

        <!-- =======================================
        THE ORBIT SLIDER CONTENT 
        ======================================= -->
        <div class="orbit-default
                <?php echo($this->get_option('thumbnails_temp') == 'Y') ? ' default-thumbs' : ''; ?>
                <?php echo(isset($align[$pID])) ? ' satl-align-' . $align[$pID] : ''; ?>
                <?php echo($responsive) ? ' resp' : ''; ?>
             ">
            <div id="featured<?php echo $satellite_init_ok; ?>"> 
                <?php foreach ($slides as $slider) :
                    $thumbnail_link = wp_get_attachment_image_src($slider->ID, 'thumbnail', false);
                    $attachment_link = get_attachment_link($slider->ID);
                    
                    $class= ($images['position'] == "S") ? "stretchCenter" : "absoluteCenter";
                    
                    echo "<div class='sorbit-wide ".$class."'  
                            data-caption='#post-{$slider->ID}' 
                            data-thumb='{$thumbnail_link[0]}'>";

                    $this->render('display-image', 
                      array('frompost'  =>  true,
                            'slider'    => $slider,
                            'source'    => 'post'), true, 'orbit');?>
 
            </div>

                <?php $this -> render('display-caption', 
                        array(  'frompost'   => true, 
                                'slider'     => $slider, 
                                'fontsize'   => null,
                                'style'      => $style,
                                'i'          => null
                                ), true, 'orbit');?>

            <?php endforeach; ?>
            </div> <!-- end featured -->

        </div>
        <?php $this -> render('jsinit', array('gallery'=>false,'frompost' => true,'respExtra' => 0), true, 'orbit');?>

        <!--  CUSTOM GALLERY -->
    <?php else : 
        $source = (empty($this->Gallery->data->source)) ? 'satellite' : $this->Gallery->data->source;
      ?>
        <div class="orbit-default
        <?php echo($this->get_option('thumbnails_temp') == 'Y') ? ' default-thumbs' : ''; ?>
                <?php echo(isset($sidetext)) ? ' text-' . $sidetext : ''; ?>
                <?php echo(isset($align[$pID])) ? ' satl-align-' . $align[$pID] : ''; ?>
                <?php echo(isset($responsive)) ? ' resp' : ''; ?>
             ">
            <div id="featured<?php echo $satellite_init_ok; ?>"> 
                <?php $i = 0; ?>
                <?php foreach ($slides as $slider) : ?>     
                    <?php
                $class= ($images['position'] == "S") ? "stretchCenter" : "absoluteCenter";
                $thumb = ($source == 'satellite') ? $this->Html->image_url($this->Html->thumbname($slider->image)) : $slider->img_url;
                
                echo "<div id='satl-custom-{$this->Gallery->data->id}{$slider->id}' class='sorbit-wide ".$class."' 
                    data-caption='#custom{$satellite_init_ok}-$i'
                    data-thumb='{$thumb}'>";

                    $this->render('display-image', 
                      array('frompost'  =>false,
                            'slider'    => $slider,
                            'source'    => $source), 
                            true, 'orbit');?>

                </div>
            <?php
            if ($sidetext != ( "Disabled" )) :
                if ($slider->textlocation != "N") :
                    ?>
                        <?php $this -> render('display-caption', 
                              array(  'frompost'   => false, 
                                      'slider'     => $slider, 
                                      'fontsize'   => $this->Gallery->data->font,
                                      'style'      => $style,
                                      'i'          => $i
                                      ), true, 'orbit');?>

                    <?php else : ?>
                        <span class="sattext-none" id='custom<?php echo ($satellite_init_ok . '-' . $i); ?>'>
                        </span>
                <?php
                endif;
            endif;
            $i = $i + 1;
        endforeach;
        ?>
        </div>

        </div>
        <?php $this -> render('jsinit', array('gallery'=>$slides[0]->section,'frompost' => false, 'respExtra' => $respExtra, 'pID' => $pID), true, 'orbit');?>
    <?php
    endif;
    /*     * ****** PRO ONLY ************* */
    if (SATL_PRO && $this->get_option('keyboard') == 'Y') {
        require SATL_PLUGIN_DIR . '/pro/keyboard.html';
    }

endif;
?>