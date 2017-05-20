<?php 
$quickedit = false;
$version = $this->Version->checkLatestVersion();
$images = $this->get_option('Images'); 
if (!empty($_GET['single'])) {
  $single = $_GET['single'];
  $ordertopic = (isset($_GET['orderby'])) ? $_GET['orderby'] : 'slide_order';
  $orderdirection = ($ordertopic == 'modified') ? "DESC" : "ASC";
  $slides = $this -> Slide -> find_all(array('section'=>(int) stripslashes($single)), null, array($ordertopic, $orderdirection));
} else { $single = false; }
if (!empty($_GET['quickedit'])) {
  $quickedit = true;
}

?>
<div class="wrap scrollSpace satl-settings" ng-app="slideApp" ng-controller="SlideController">
	 <?php 
    if (!$version['latest'] && $version['message'] && SATL_PRO) : ?>
        <div class="plugin-update-tr">
            <div class="update-message">
                    <?php echo $version['message']; ?>
            </div>
        </div>
	<?php endif; ?>

    <h2><?php _e('Manage Slides', SATL_PLUGIN_NAME); ?> <?php echo $this -> Html -> link(__('Add New'), 
                    $this -> url . '&amp;method=save&single='.$single, 
                    array('class' => "btn btn-primary")); ?></h2>
	<?php if (!empty($slides)) : ?>	
                <div class="alignright">
                    <form id="display-gallery" action="<?php echo $this -> url; ?>&amp;method=single" method="POST">
                        <select name="section">
                            <option value="All">All</option>
                            <?php $single = ($_GET['single']) ? $_GET['single'] : null;?>
                            <?php $gals = $this -> Gallery -> find_all(null, array('id','title'), array('gal_order', "ASC") );
          							 ?>

                                <?php if (!empty($gals)) : ?>
                                    <?php foreach ( $gals as $gallery ) {?>
                                        <option <?php echo ((int) $single == $gallery -> id) ? 'selected="selected"' : ''; ?> value="<?php echo($gallery -> id) ?>">Gallery <?php echo($gallery -> id. ": ".$gallery -> title)?></option>
                                    <?php } ?>
                                <?php else : ?>
                                        <option <?php echo ((int) $this -> Slide -> data -> section == '1') ? 'selected="selected"' : ''; ?> value="1">Gallery 1</option>
                                <?php endif; ?>
                        </select>
                        <input type="submit" name="View" value="View" class="hidden"/>
                    </form>
                </div>
                <span class="alignright viewonly" style="padding-top:5px">View Only : </span>

	<?php endif; ?>
        
	
    <div id="gallery-slide-switch">
      <?php _e('Switch Your View:', SATL_PLUGIN_NAME); ?> <a class="btn btn-primary" href="<?php echo(admin_url()."admin.php?page=satellite-galleries&method=save&id=".$single) ?>">Gallery View</a>
    </div>

	<?php if (!empty($slides)) :  ?>
    <h5>Slide Count: <?php echo(count($slides));?></h5>
    <?php if ($single): ?>
    <?php endif; ?>
      
		<form onsubmit="if (!confirm('<?php _e('Are you sure you wish to execute this action on the selected slides?', SATL_PLUGIN_NAME); ?>')) { return false; }" action="<?php echo $this -> url; ?>&amp;method=mass&amp;single=<?php echo($single);?>" method="post" class="satl_table">
			<div class="tablenav">
				<div class="alignleft actions">
					<a href="<?php echo $this -> url; ?>&amp;method=order&single=<?php echo (isset($_GET['single'])) ? $_GET['single'] : ''; ?>" title="<?php _e('Order all your slides', SATL_PLUGIN_NAME); ?>" class="btn btn-info clearfix alignright" style="margin-left:7px;"><?php _e('Order Slides', SATL_PLUGIN_NAME); ?></a>
				
					<select id="satl_bulkaction" name="action" class="action alignleft">
						<option value="">- <?php _e('Bulk Actions', SATL_PLUGIN_NAME); ?> -</option>
						<option value="delete"><?php _e('Delete', SATL_PLUGIN_NAME); ?></option>
            <option value="quickedit" <?php echo ($quickedit) ? "selected=selected" : null ?>><?php _e('Quick Edit', SATL_PLUGIN_NAME); ?></option>
            <?php if (isset($images[resize])):?>
              <option value="resize"><?php _e('Resize to ', SATL_PLUGIN_NAME); echo($images['resize'].'px'); ?></option>
            <?php endif;?>
						<?php if ($this->canPremiumDoThis('watermark')) :?>
              <option value="watermark"><?php _e('Watermark', SATL_PLUGIN_NAME); ?></option>
            <?php endif; ?>
					</select>
					<input type="submit" class="btn btn-primary alignleft" value="<?php _e('Apply', SATL_PLUGIN_NAME); ?>" name="execute" />
				</div>
			</div>
		
			<div class="widefat">
        <ul>
          <li class="slide-holder row-fluid">
            <div class="fl-l loader-check check-column"><input type="checkbox" name="checkboxall" id="checkboxall" value="checkboxall" /></div>
            <div class="fl-l loader-image">Image</div>
            <div class="fl-l loader-title"><a href="<?php echo ($this->url."&single=".$single."&orderby=title")?>">Title</a></div>
            <div class="fl-r loader-date"><a href="<?php echo ($this->url."&single=".$single."&orderby=modified")?>">Modified</a></div>
            <div class="fl-r loader-uselink">Use Link</div>
            <div class="fl-r loader-order"><a href="<?php echo ($this->url."&single=".$single."&orderby=slide_order")?>">Order</a></div>
            <div class="fl-r loader-section">Gallery</div>
          </li>
        </ul>  
				<div class="thbody">
          <div  infinite-scroll='loadMore()' infinite-scroll-distance='2'>
            <?php
              if ($quickedit) {
                $this -> render('slides/display-quickedit', array(), true, 'admin');
              } else {
                $this -> render('slides/display-standard', array('single'=>$single), true, 'admin');
              }
             ?>
          </div>
          <div>
            <ul>
              <li class="slide-holder row-fluid">
                <div class="fl-l loader-check check-column"><input type="checkbox" name="checkboxall" id="checkboxall" value="checkboxall" /></div>
                <div class="fl-l loader-image"><?php _e('Image', SATL_PLUGIN_NAME); ?></div>
                <div class="fl-l loader-title"><?php _e('Title', SATL_PLUGIN_NAME); ?></div>
                <div class="fl-r loader-date"><?php _e('Modified', SATL_PLUGIN_NAME); ?></div>
                <div class="fl-r loader-uselink"><?php _e('Link', SATL_PLUGIN_NAME); ?></div>
                <div class="fl-r loader-order"><?php _e('Order', SATL_PLUGIN_NAME); ?></div>
                <div class="fl-r loader-section"><?php _e('Gallery', SATL_PLUGIN_NAME); ?></div>
              </li>
            </ul>  
          </div>
          <?php $this -> render('lazyload', array('slides' => $slides), true, 'admin'); ?>
          <?php else : ?>
            <p style="color:red;"><?php _e('No slides found', SATL_PLUGIN_NAME); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </form>
    <?php _e('Pssst, scrolling loads more images.', SATL_PLUGIN_NAME); ?>
    <img src="<?php echo(SATL_PLUGIN_URL.'/images/Satellite-Logo-sm.png');?>" style="height:100px" class="alignright"/>