<?php 
header("Content-Type: text/css");
$styles = array();
foreach ($_GET as $skey => $sval) :
	$styles[$skey] = urldecode($sval);
endforeach;
IF (isset($styles['width_temp']) && ($styles['width_temp'] > 1)) { $styles['width'] = $styles['width_temp']; }
IF (isset($styles['height_temp']) && ($styles['height_temp'] > 1)) { $styles['height'] = $styles['height_temp']; }
$height = (is_int($styles['height'])) ? $styles['height'] ."px" : $styles['height']."px";
$width = (is_int($styles['width'])) ? $styles['width'] ."px" : $styles['width']."px";
IF (!$styles['thumbheight']) { $styles['thumbheight'] = "75"; }
if ($styles['background'] == '#000000') { $loadbg = $styles['background']." url('../images/loading.gif')";
} else { $loadbg = $styles['background']." url('../images/spinner.gif')"; }
if (!isset($styles['navbuttons'])) { $styles['navbuttons'] = 0;}
if (!isset($styles['nav'])) { $styles['nav'] = 'on';}
if (!isset($styles['align'])) { $styles['align'] = null;}
IF ($styles['navbuttons'] == 0) { $navright = 'url(../images/right-arrow.png) no-repeat 0 0';$navleft = 'url(../images/left-arrow.png) no-repeat 0 0'; $arrowheight = 100; $arrowwidth = 78;}
IF ($styles['navbuttons'] == 1) { $navright = 'url("../pro/images/right-sq.png") no-repeat 30px 0';$navleft = 'url(../pro/images/left-sq.png) no-repeat 0 0'; $arrowheight= 60;$arrowwidth = 60;}
IF ($styles['navbuttons'] == 2) { $navright = 'url(../pro/images/right-rd.png) no-repeat 30px 0';$navleft = 'url(../pro/images/left-rd.png) no-repeat 0 0'; $arrowheight= 60;$arrowwidth = 60;}
IF ($styles['navbuttons'] == 3) { $navright = 'url(../pro/images/right-pl.png) no-repeat 30px 0';$navleft = 'url(../pro/images/left-pl.png) no-repeat 0 0'; $arrowheight= 50;$arrowwidth = 60;}
IF ($styles['nav'] == 'off') { $navright = 'none'; $navleft = 'none'; $arrowheight = 0; }


$extrathumbarea = (int) $styles['thumbareamargin'];
$brtopspace = (int) $styles['height'] *.69;
$trtopspace = (int) $styles['height'] *.17;
$sattxtwidth = (int) $styles['width'] *.48;
$arrowpush = (int) $styles['navpush'];
$thumbrow = (int) $styles['thumbspacing'];
$orbitThumbMargin = 5;
$sideTextWidth = 250;
$galleryTitles = 175;
$fullthumbheight = (int) $styles['thumbheight'] + (2 * $orbitThumbMargin) + (int) (( 2 * $styles['thumbspacing'] )-4);
IF ($styles['infomin'] == "Y") {
    ?>
    .orbit-caption h5, .orbit-caption p { margin:0 !important; }
<?php } ?>
    
#featured, #featured1, #featured2, #featured3, #featured4, #featured5, #featured6, #featured7, #featured8, #featured9, #featured10 {
    width: <?php echo $width ?>;
    height: <?php echo $height ?>;
    background:<?php echo($loadbg)?> no-repeat center center;
    }
div.satl-wrapper {
    width: <?php echo $width ?>;
    height: <?php echo $height ?>;
    <?php if ($styles['align'] == 'left'){ ?>
        margin: 0 15px 15px 0;
        float: left;
    <?php } elseif ($styles['align'] == 'right'){ ?>
        margin: 0 0 15px 15px;
        float: right;
    <?php } else { ?>
        margin: 0 auto 15px auto;
    <?php } ?>
    background:<?php echo $styles['background']?>; /* VAR BACKGROUND */
    }
div.orbit-default.default-thumbs div.satl-wrapper {
    margin-bottom:<?php echo $fullthumbheight + $styles['thumbmargin']; ?>px;
    }

<?php 
/* Note: If your slider only uses content or anchors, you're going to want to put the width and height declarations on the ".orbit>div" and "div.orbit>a" tags in addition to just the .satl-wrapper */
/* SPECIAL IMAGES */
?>
    
div.sorbit-tall, div.sorbit-wide, div.sorbit-basic {
	background:<?php echo $styles['background']?>; /* VAR BACKGROUND */
}
div.sorbit-tall img {
	/*height: <?php echo $styles['height'] ?>px; /* VAR HEIGHT */
	}
	
div.sorbit-wide {
	/*height: <?php echo $height ?>;
        width: <?php echo $width ?>; */
        max-width: <?php echo $width ?>; 
        }
a.sorbit-link {
    height: <?php echo $styles['height'] ?>px; /* VAR HEIGHT */
    display: block;
}

/* TIMER
   ================================================== */
div.timer {
    display: <?php echo($styles['playshow'] == 'N') ? "none" : "block";?>;
}

/* CAPTIONS
   ================================================== */
.satl-wrapper .orbit-caption {
    <?php if (substr($styles['infobackground'], 0, 1) == '#') :?>
    background: rgba(<?php echo(hex2RGB($styles['infobackground'], true)); ?>,.6);
    <?php else: ?>
    background: <?php echo($styles['infobackground']); ?>;
    <?php endif; ?>
    color: <?php echo $styles['infocolor']; ?>;
}

.satl-wrapper .orbit-caption h5 {
    color:<?php echo $styles['infocolor']; ?>; 
    }

.satl-wrapper .orbit-caption p {
    color: <?php echo $styles['infocolor']; ?>;
}
div.satl-wrapper .sattext {
    width:<?php echo($sattxtwidth)?>px;
    background: rgba(<?php echo(hex2RGB($styles['infobackground'], true)); ?>,.6);
    border: 2px solid rgba(<?php echo(hex2RGB($styles['infocolor'], true)); ?>,.6);
    }
div.satl-wrapper .sattextBR {
    top: <?php echo ($brtopspace)?>px;    
}
div.satl-wrapper .sattextTR {
    top: <?php echo ($trtopspace)?>px;    
}
a.sorbit-link:hover {
    text-decoration:none;}
    
.orbit-default .satl-wrapper div.sattext p { 
    color:<?php echo $styles['infocolor']; ?>; padding:0 8px 3px;
    }
.orbit-default .satl-wrapper div.sattext h5 {
    color:<?php echo $styles['infocolor']; ?>; 
    }
    
.orbit-default.default-thumbs .satl-wrapper {
    height: <?php echo ((int) $styles['height'] + $fullthumbheight); ?>px;
}
.more-img {
    float:right;
}

/* TEXT ON THE SIDE
   ================================================== */
.text-right .satl-wrapper {
    margin-right: <?php echo($sideTextWidth);?>px;
}
.text-right .orbit-caption {
    width: <?php echo($sideTextWidth);?>px;
    margin-right: -<?php echo($sideTextWidth);?>px;
}

/* DIRECTIONAL NAV
   ================================================== */
div.satl-nav span {
    margin-top: -<?php echo( $arrowheight / 2);?>px;
    cursor: pointer; 
    }
div.satl-nav span.right {
    background: <?php echo($navright); ?>;
	/*background: background: url(../images/right-arrow.png) no-repeat 0 0*/
    right: 0;
    <?php if ($arrowpush > 0) ?>
        margin-right: -<?php echo((int)$arrowpush); ?>px;
    }
div.satl-nav span.left {
    background: <?php echo($navleft); ?>;
    left: 0; 
    <?php if ($arrowpush > 0) ?>
        margin-left: -<?php echo((int)$arrowpush); ?>px;
    }

/* BULLET NAV
   ================================================== */
.orbit-bullets li, .orbit-thumbnails li {
    margin: <?php echo ((int) $styles['thumbspacing']) ?>px;
}

/* THUMBNAIL NAV
   ================================================== */

ul.orbit-thumbnails {
    margin: <?php echo( $orbitThumbMargin ); ?>px auto;
}
.thumbholder {
    height: <?php echo($fullthumbheight);?>px;
    margin: <?php echo $styles['thumbmargin']; ?>px auto 0 auto;
    padding-top: <?php echo $styles['height']; ?>px;
    background: <?php echo $styles['background']; ?>;
}
    
.orbit-thumbnails li {
    width: <?php echo($styles['thumbheight']);?>px;
    height: <?php echo($styles['thumbheight']);?>px;
    border: 2px solid <?php echo($styles['background'])?>;
    opacity: .<?php echo($styles['thumbopacity']);?>;
    margin: <?php echo( (int) ($styles['thumbspacing']-2));?>px !important;
}
.orbit-thumbnails li.active {
    border: 2px solid <?php echo($styles['thumbactive']);?>;
    margin: <?php echo( (int) ($styles['thumbspacing']-2));?>px;
}

#slideleft, #slideright {
    height:<?php echo ((int) $styles['thumbheight']); ?>px;
    margin-top:-<?php echo (((int) $styles['thumbheight'] + 5)+$styles['thumbspacing']-4); ?>px;
}
#slideleft { float:left; width:20px; 
    background-color:<?php echo $styles['background']; ?>;
}
#slideright { background:<?php echo $styles['background']; ?> url('../images/scroll-right.gif') center center no-repeat; }

/****************************************
/**** FULL RIGHT & LEFT SECTIONS ***/
    
.full-right, .full-left {
    width:<?php echo ((int)($styles['thumbarea'] + $styles['width'] + $extrathumbarea ) ); ?>px;
}
.full-right .satl-wrapper {
    margin-right:<?php echo ((int)($styles['thumbarea'] + $extrathumbarea )); ?>px !important;
}
.full-right .orbit-thumbnails li, .full-left .orbit-thumbnails li {
    height:<?php echo ((int) $styles['thumbheight']); ?>px;
    width:<?php echo ((int) $styles['thumbheight']); ?>px;
    margin-right:<?php echo ((int) $styles['thumbspacing'] -2) ?>px;
}
.full-right .orbit-thumbnails, .full-left .orbit-thumbnails {
    /*width:<?php echo ((int)($styles['thumbarea']-20)); ?>px !important;*/
}
.full-right div.sorbit-wide img, .full-left div.sorbit-wide img {
/*    margin-top: <?php echo ((int)($styles['thumbspacing'])); ?>px;*/
}
.full-left .orbit {
    float:right;
    margin-left:<?php echo ((int)($styles['thumbarea'] + $extrathumbarea)); ?>px;
}
.full-right .orbit {
    float:left;
}
.full-right .thumbholder, .full-left .thumbholder {
    max-width: <?php echo (int)($styles['thumbarea']); ?>px;
    }
.full-right .thumbholder {
    margin-left:<?php echo ((int)($styles['width'] ));?>px;
    padding-left: <?php echo ($extrathumbarea);?>px;
}
.full-left .thumbholder {
    padding-right: <?php echo ($extrathumbarea );?>px;
    width: <?php echo (int)($styles['thumbarea']); ?>px;
}
div.full-left div.timer {
    right: -<?php echo (int)($styles['thumbarea']- $extrathumbarea ); ?>px;
}
/*.full-right div.orbit-caption {
    width:<?php echo ((int) ($styles['width'])) ?>px;
}*/
.full-left div.orbit-caption {
    /*width:<?php echo ((int) ($styles['width'])) ?>px;*/
    left:<?php echo ((int)($styles['thumbarea'] + $extrathumbarea)); ?>px;
}
.full-left div.sattext {
    left: auto;
}
.full-left div.sattext, .full-right div.sattext {
    width:<?php echo ($sattxtwidth) ?>px; 
}
/*.full-right .satl-nav span.right {
    right:<?php echo ((int)($styles['thumbarea'] + $extrathumbarea)); ?>px
}*/
.full-left .satl-nav span.left {
    left:<?php echo ((int)($styles['thumbarea'] + $extrathumbarea)); ?>px
}
.full-left .satl-nav span.right {
    right:-<?php echo ((int)($styles['thumbarea'] - $extrathumbarea)); ?>px;
}

li > li.has-thumb {
    height: <?php echo($styles['thumbheight']); ?> }
}
    
/******* Galleries and Splash Display ******/

.satl-gal-wrap {
    position: relative;
}
.splash-satl-wrap {
    position: relative;
    width: <?php echo($styles['width']);?>px;
    height: <?php echo($styles['height']);?>px;
    <?php if ($styles['align'] == 'left'){ ?>
        margin: 0 15px 15px 0;
        float: left;
    <?php } elseif ($styles['align'] == 'right'){ ?>
        margin: 0 0 15px 15px;
        float: right;
    <?php } else { ?>
        margin: 0 auto 15px auto;
    <?php } ?>
    
}
.splash-satl-wrap.default-thumbs {
    margin-bottom: <?php echo ((int) $fullthumbheight + $styles['thumbmargin']); ?>px;
}
.splash-satl-wrap.default-thumbs .splash-thumbs {
    margin-top: <?php echo ($styles['height']); ?>px;
    height: <?php echo ((int) $fullthumbheight); ?>px;
}
.splash-thumb-wrap {
    width: <?php echo($styles['width']);?>px;
    overflow:hidden;
}
.splash-satl-wrap .splash-thumbs .the-thumb {
    height:<?php echo $styles['thumbheight']; ?>px;
    width:<?php echo $styles['thumbheight']; ?>px;
    border: 2px solid <?php echo($styles['background'])?>;
    margin:<?php echo ((int) $styles['thumbspacing'] -2) ?>px;
}
.galleries-satl-wrap .splashstart.sorbit-wide {
    left:<?php echo $galleryTitles + 5 ?>px;
    /*padding-left:<?php echo $galleryTitles ?>px;*/
}

    <?php

/**
 * Convert a hexa decimal color code to its RGB equivalent
 *
 * @param string $hexStr (hexadecimal color value)
 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
 */                                                                                                
function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
} ?>
