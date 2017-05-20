
<?php if (!empty($slide -> post_excerpt) && $this -> get_option('captionlink') == "Y") : ?>
	<a href="<?php echo $slide -> post_excerpt; ?>" title="<?php echo $slide -> post_title; ?>"><img style="height:<?php echo $style['thumbheight'] ?>px;" src="<?php echo $thumbnail_link[0]; ?>" alt="<?php echo $this -> Html -> sanitize($slide -> post_title); ?>" />lo</a>
	
<?php else : ?>
	<a rel="prettyPhoto" href="<?php echo $slide -> guid; ?>" title="<?php echo addslashes($slide -> post_title); ?>"><img style="height:<?php echo $style['thumbheight'] ?>px;" src="<?php echo $thumbnail_link[0]; ?>" alt="<?php echo $this -> Html -> sanitize($slide -> post_title); ?>" />la</a>                                
<?php endif; ?>