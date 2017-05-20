<?php
class SatellitePremiumHelper extends SatellitePlugin {
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    public function addProOption($option, $optionName, $pID) {
        if (!empty($option)) {
            $option_array = $this->get_option($optionName);
            if (!is_array($option_array)) { $option = array(); }
            $option_array[$pID] = $option;
            $this->update_option($optionName, $option_array);
        }
    }
    
    /* Used with Conditionally Added Styling
     * @$option = array
     * @$title = string
     * @pId = integer
     */
    public function addProStyling($option,$title,$pID) {
        if (is_array($option)) {
            foreach ($option as $skey => $sval) {
                if ($skey == $pID)
                  if (is_string($sval)){
                    return "&amp;".$title."=" . urlencode($sval);
                  }
            }
        }
        return null;
    }
    /*
     * @$image is the 'title' of the watermark image
     * @$watermark is get_option[Watermark]
     */
    public function doWatermark($image, $watermark) {
        $Html = new SatelliteHtmlHelper;
        $Image = new SatelliteImageHelper;
        $imageurl = SATL_UPLOAD_URL . '/' . $image;
        $imagedir = SATL_UPLOAD_DIR . '/' . $image;
        $waterImg = $Html -> image_id($watermark['image']);

        $stamp = $Image->load($waterImg, true);
        $Image->load($imageurl);
        
        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);
        // Copy the stamp image onto our photo using the margin offsets and the photo 
        // width to calculate positioning of the stamp. 
        if ($watermark['opacity'] == 100) {
          imagecopy($Image->image, $stamp, $Image->getWidth() - $sx - $marge_right, $Image->getHeight() - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
        } else {
          imagecopymerge($Image->image, $stamp, $Image->getWidth() - $sx - $marge_right, $Image->getHeight() - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp),$watermark['opacity']);
        }
        $this->log_me("resaving image with watermark with opacity: ".$watermark['opacity']);
        $Image->save($imagedir);
        // free memory
        imagedestroy($Image->image);

    }

}
?>
