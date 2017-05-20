<?php       if (!empty($_GET['single'])) {
                $single = $_GET['single'];
                $galleries = $this -> Gallery -> find_all(array('id'=>(int) stripslashes($single)), null, array('id', "ASC"));
            }
            else {
                $galleries = $this -> Gallery -> find_all();
                $single = '';
            }
            if (!empty($_GET['order'])) { $order = $_GET['order']; }
            else { $order = 'slide_order'; }
            if (!empty($_GET['dir'])) { $dir = $_GET['dir']; }
            else { $dir = 'ASC'; }
?>
﻿<div class="wrap"> 
        
	<h2><?php _e('Order Slides', SATL_PLUGIN_NAME); ?></h2>
	<div style="float:none;" class="subsubsub">
            <?php $manage_link = ($single) ? $this -> url .'&single='.$single : $this -> url; ?>
		<a href="<?php echo $manage_link; ?>"><?php _e('&larr; Back to Slides', SATL_PLUGIN_NAME); ?></a>
	</div>
	<?php if (!empty($slides)) :
            foreach ($galleries as $gallery ) {
            echo "<h3>".$gallery -> title . "(#".$gallery -> id.")</h3>";
            echo "Order <a href=?page=satellite-slides&method=order&single=".$gallery -> id."&order=title>Alphabetically</a> | ";
            echo "<a href=?page=satellite-slides&method=order&single=".$gallery -> id."&order=title&dir=DESC>Reverse-Alph</a> | ";
            echo "<a href=?page=satellite-slides&method=order&single=".$gallery -> id."&order=created>Created By</a> | ";
            echo "<a href=?page=satellite-slides&method=order&single=".$gallery -> id."&order=created&dir=DESC>Reverse-Created</a> | ";
            echo "<a href=?page=satellite-slides&method=order&single=".$gallery -> id."&order=slide_order>Slide Order</a><br />";
            $n = 1;
            ?>
            <div class="slide-order-msg">
                <div class="slide-status">Status:</div><div id="slidemessage<?php echo $gallery -> id;?>" class="clearfix red-msg slide-msg"><?php _e('Original Ordering (Unsaved)', SATL_PLUGIN_NAME);?></div>
                <p class="clear">Save by dragging slides</p>
            </div>


            <ul id="slidelist<?php echo $gallery -> id;?>" class="slide-order">
                <?php $slides = $this -> Slide -> find_all(array('section'=>(int) stripslashes($gallery -> id)), null, array($order, $dir)); ?>
                    <?php if (is_array($slides)) : ?>
                    <?php foreach ($slides as $slide) : ?>
                            <li class="lineitem" data-order="<?php echo $slide -> id; ?>">
                                <img src="<?php echo $this -> Html -> image_url($this -> Html -> thumbname($slide -> image, "thumb")); ?>" alt="<?php echo $this -> Html -> sanitize($slide -> title); ?>" />
                                <p class="slide-title"><?php echo $slide -> title; ?></p>
                                <p class="slide-order"><?php echo($n);?></p>
                            </li>
                    <?php
                        $n++;
                        endforeach;
                        endif;
                    ?>
            </ul>

            <script type="text/javascript">
            jQuery(document).ready(function() {
                    var slideOrder;
                    jQuery("ul#slidelist<?php echo $gallery -> id;?>").sortable({
                            start: function(request) {
                                    jQuery("#slidemessage<?php echo $gallery -> id;?>").slideUp();
                            },
                            stop: function(request) {
                                slideOrder = jQuery("ul#slidelist<?php echo $gallery -> id;?>").sortable('toArray', {attribute: 'data-order'});
                                var data = {
                                    action : 'satl_order_slides',
                                    slides_order : slideOrder
                                }
                                jQuery.post(ajaxurl, data, function(response) {
                                    jQuery("#slidemessage<?php echo $gallery -> id;?>").html(response).slideDown("slow");;
                                    setOrder(<?php echo $gallery -> id;?>);
                                });
                            }
                    });
                    jQuery("ul#slidelist<?php echo $gallery -> id;?>").disableSelection();
            });
            </script>
            <?php } ?>
        <script type="text/javascript">
            setOrder = function (order) {
                jQuery("ul#slidelist" + order +" li").each(function(index,li) {
                    jQuery(li).find('.slide-order').html(parseInt(index+1));
                })
            }
        </script>
	<?php else : ?>
		<p style="color:red;"><?php _e('No slides found', SG2_PLUGIN_NAME); ?></p>
	<?php endif; ?>
</div>