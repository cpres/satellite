<?php global $satellite_init_ok, $post; ?>
<?php
$images = $this->get_option('Images');
$imagesbox = $images['imagesbox'];
$ID = ($frompost) ? $slider->ID : $slider->id;
$title = ($frompost) ? $slider->post_title : $slider->title;
$attachment_link = ($frompost) ? get_attachment_link($ID) : '';
$pagelink = $images['pagelink'];

list($imagelink,$width,$height) = $this->Image->getImageData($ID,$slider,$frompost,$source);

if ($images['position'] == "S" || $images['position'] == "C") {
  $crop = ($images['position'] == "C") ? true : false;
  if ($height > 0) {
    $size = $this->Image->getImageStretch($GLOBALS['post']->ID,$width,$height,$crop);
  } else {
    $size = null;
  }
  if ($images['position'] == "C") 
    $position = $size;
  else
    $position = "absoluteCenter stretchCenter " .$size;
} else {
  $position = "absoluteCenter noStretch";
}
$rel = "";
$class= "";
if ($imagesbox == "T") {
  $class="thickbox";
} elseif ($imagesbox == "L") {
  $rel = ($frompost) ? "lightbox[".$slider->post_parent."]" : "lightbox[".$slider->section."]";
  $class = "lightbox";
} elseif ($imagesbox == "P") {
  $rel = ($frompost) ? "prettyPhoto[".$slider->post_parent."]" : "prettyPhoto[".$slider->section."]";
  $class = "prettyphoto";
}
?>

<?php if (!$frompost && $slider->uselink == "Y" && !empty($slider->link)) : ?>
  <a class="sorbit-link" href="<?php echo $slider->link; ?>" title="<?php echo $slider->title; ?>" target="<?php echo ($pagelink == "S") ? "_self" : "_blank" ?>">
<?PHP elseif ($frompost && $this->get_option('wpattach') == 'Y') : ?>
  <a class="sorbit-link <?php echo $class; ?>" href="<?php echo $attachment_link; ?>" rel="<?php echo $rel; ?>" title="<?php echo $title; ?>">
<?PHP elseif ($imagesbox != "N" && ! $this->get_option('nolinker')) : ?>
  <a class="sorbit-link <?php echo $class; ?>" href="<?php echo $imagelink; ?>" rel="<?php echo $rel; ?>" title="<?php echo $title; ?>">
<?PHP endif; ?>
<img class="<?php echo($position);?>"
  src="<?php echo $imagelink; ?>" 
  alt="<?php echo $title; ?>" />
<?PHP if (($imagesbox != "N" && ! $this->get_option('nolinker')) || $slider->uselink == "Y")  { ?></a><?PHP } ?>
