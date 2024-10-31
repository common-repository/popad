/*
    PopAd AJAX Script - By Alobaidi
    http://wp-plugins.in | http://wp-plugins.in/PopAd_Plugin
    Copyright (c) 2016, Alobaidi.
*/


jQuery(window).load(function() {

    if( jQuery('.pop-ad-content *').length == 1 && jQuery(window).width() > 768 ){
        jQuery("#pop-ad-wrap").fadeOut(500).queue(function() {
            jQuery(this).remove();
            alert(PopAd_Object_MSG.PopAd_Empty_Message);
        });
    }


    if( jQuery(window).width() < 769 ){
        jQuery("#pop-ad-wrap").remove();
    }


    jQuery("#pop-ad-wrap").bind('click', function(event){
        if( jQuery(event.target).attr('class') == 'pop-ad-wrap' || jQuery(event.target).attr('class') == 'pop-ad-content' || jQuery(event.target).attr('class') == 'pop-ad-close' ){
            jQuery("#pop-ad-wrap").fadeOut(500).queue(function() {
                jQuery('.pop-ad-close').click();
            });
        }
    });


    jQuery(".pop-ad-close").one( 'click', function(){
        var PopAd_AJAX_URL = jQuery(this).attr("pop-ad-ajax");
        jQuery.ajax({
            type: 'GET',
            url: PopAd_AJAX_URL,
            data: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function(PopAdSetCookie) {
                jQuery('#pop-ad-wrap').remove();
            }
        });
    });


    jQuery(document).keyup(function(e) {
        if ( e.keyCode == 27 && jQuery('#pop-ad-wrap').is(':visible') ){
            jQuery('.pop-ad-close').click();
        }
    });

});