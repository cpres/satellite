
<?php
global $satellite_init_ok;
if (!empty($slides)) :

    $style = $this->get_option('styles');
    $images = $this->get_option('Images');
    $imagesbox = $images['imagesbox'];
    $pagelink = $images['pagelink'];
    $responsive = false;//$this->get_option('responsive');
    $respExtra = (isset($respExtra)) ? $respExtra : $style['thumbarea'];
    
    $textloc = $this->get_option('textlocation');
    if (!$frompost) {
        $this->Gallery->loadData($slides[0]->section);
        $sidetext = $this -> Gallery -> capLocation($this->Gallery->data->capposition,$slides[0]->section);
    }

    ?>

    <?php if ($frompost) : ?>
        <!-- =======================================
        THE ORBIT SLIDER CONTENT 
        ======================================= -->
        <div class="<?php echo ( $this->get_option('thumbnails_temp') == 'FR') ? 'full-right' : 'full-left';?><?php echo($responsive) ? ' resp' : ''; ?>">
            <div id="featured<?php echo $satellite_init_ok; ?>"> 
                <?php foreach ($slides as $slider) :  
                    $thumbnail_link = wp_get_attachment_image_src($slider->ID, 'thumbnail', false);
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
                        array( 'frompost'   => true, 
                               'slider'     => $slider, 
                               'fontsize'   => null,
                               'style'      => $style,
                               'i'          => null
                               ), true, 'orbit');?>
            <?php endforeach;  ?>
            </div> <!-- end featured -->

        </div>

         <?php $this -> render('jsinit', array('gallery'=>false,'frompost' => true, 'fullthumb' => true, 'respExtra' => $respExtra), true, 'orbit');?>
        <!--  CUSTOM GALLERY -->
    <?php else : 
      $source = (empty($this->Gallery->data->source)) ? 'satellite' : $this->Gallery->data->source;
    ?>  

        <div class="<?php echo ( $this->get_option('thumbnails_temp') == 'FR') ? 'full-right' : 'full-left';?><?php echo($responsive) ? ' resp' : ''; ?>
            <?php echo($sidetext) ? ' text-' . $sidetext : ''; ?>">
            <div id="featured<?php echo $satellite_init_ok; ?>"> 
                <?php $i = 0; ?>
                <?php foreach ($slides as $slider) :

                    $class= ($images['position'] == "S") ? "stretchCenter" : "absoluteCenter";
                    $thumb = ($source == 'satellite') ? $this->Html->image_url($this->Html->thumbname($slider->image)) : $slider->img_url;
                    echo "<div class='sorbit-wide ".$class."' 
                        data-caption='#custom{$satellite_init_ok}-$i'
                        data-thumb='{$thumb}'>";

                    $this->render('display-image', 
                    array('frompost'  =>false,
                          'slider'    => $slider,
                          'source'    => $source), 
                          true, 'orbit');?>
                </div>
                <?php if ($slider->textlocation != "N") { ?>
                  <?php $this -> render('display-caption', array('frompost'   => false, 
                                                                 'slider'     => $slider, 
                                                                 'fontsize'   => $this->Gallery->data->font,
                                                                 'style'      => $style,
                                                                 'i'          => $i
                                                                 ), true, 'orbit');?> 
                <?php } ?>
                <?php $i = $i +1; ?>
            <?php endforeach; ?>
            </div> <!-- end featured -->
            
        </div>

    <?php $this -> render('jsinit', array('gallery'=>$slides[0]->section, 'frompost' => false, 'fullthumb' => true, 'respExtra' => $respExtra), true, 'orbit');?>

    <?php endif; 
    /******** PRO ONLY **************/
    if ( SATL_PRO && $this->get_option('keyboard') == 'Y') {
            require SATL_PLUGIN_DIR . '/pro/keyboard.html';

    }
    endif; ?>