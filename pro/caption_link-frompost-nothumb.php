<?php if (!empty($slide -> post_excerpt) && $this -> get_option('captionlink') == "Y") : ?>
	<a href="<?php echo $slide -> post_excerpt; ?>" title="<?php echo $slide -> post_title; ?>">cap</a>
<?php else: ?>
	<a href="<?php echo $slide -> guid; ?>" title="<?php echo $slide -> post_title; ?>"> </a>
<?php endif; ?>