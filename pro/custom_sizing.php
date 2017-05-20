<?php
//echo "custom sizing 2 oh yeah";die();
    $styler = array();
	$styler = $this -> get_option('styles');
	$pID = $GLOBALS['post']->ID;
    $nav = stripslashes($r['nav']);
        
//    echo $align;
//    echo "<br/>width: ".$w;
//    echo "<br/>nav: ".$nav;
//    echo "<br/>pID".$pID;

    $options = array(
                array('option'=>$r['autospeed'],     'name'=>'autospeed_temp'),
                array('option'=>$r['animspeed'],     'name'=>'animspeed_temp'),
                array('option'=>$r['infobackground'],'name'=>'infobackground'),
                array('option'=>$r['infocolor'],     'name'=>'infocolor'),
                array('option'=>$r['background'],    'name'=>'background'),
                array('option'=>$r['nav'],           'name'=>'nav_temp'),
                array('option'=>$r['align'],         'name'=>'align')
                );
    foreach ($options as $option) {
        $this->Premium->addProOption($option['option'],$option['name'], $pID);
    }

	if (!empty( $w ) || !empty( $width )) {
        if (isset($width)) { $w = $width; }
		$width_temp = $this -> get_option('width_temp');
		$new_array[$pID] = $w;
		if (!is_array($width_temp)) { $width_temp = array(); }
                $width_temp[$pID] = $w;
		$this -> update_option('width_temp', $width_temp);
	}
	if (!empty($h) || !empty($height)) {
		if (isset($height)) { $h = $height; }
		$height_temp = $this -> get_option('height_temp');
		if (!is_array($height_temp)) { $height_temp = array(); }
		$height_temp[$pID] = $h;
		$this -> update_option('height_temp', $height_temp);
	}