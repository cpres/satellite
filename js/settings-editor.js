jQuery(document).ready(function() {
	jQuery(".if-js-closed").removeClass("if-js-closed").addClass("closed");
	postboxes.add_postbox_toggles("slideshow_page_gallery-settings");
});
jQuery(document).ready( function() {
        jQuery('.postbox h3').prepend('<a class="togbox">+</a> ');
        jQuery('#post-body-content .postbox').addClass('closed');
        jQuery('.postbox h3').click( function() {
            var that = this;
            var isclosed = jQuery(this).parent().hasClass('closed');
            jQuery(this).find('a.togbox').html(isclosed ? '+' : '-');
        });
        /*if(jQuery("textarea[@name=event_notes]").val()!="") {
            jQuery("textarea[@name=event_notes]").parent().parent().removeClass('closed');
        }*/
                
    });