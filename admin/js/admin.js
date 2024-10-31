/*
    PopAd Autoplay Script - By Alobaidi
    http://wp-plugins.in | http://wp-plugins.in/PopAd_Plugin
    Copyright (c) 2016, Alobaidi.
*/


jQuery( function() {

    if( jQuery('#pop_ad_media_link').val().length === 0 ){
        jQuery('#pop_ad_media_autoplay_wrap').css('display', 'none');
    }
    else{
        jQuery('#pop_ad_media_autoplay_wrap').css('display', 'block');
    }


    jQuery("#pop_ad_media_link").on('mousedown keydown', function(){
        if( !jQuery("#pop_ad_media_autoplay_wrap").is(":visible") ){
            jQuery('#pop_ad_media_autoplay_wrap').fadeIn();
        }
    });


    if( jQuery('#pop_ad_adv_link').val().length === 0 ){
        jQuery('#pop_ad_link_rel_wrap').css('display', 'none');
    }
    else{
        jQuery('#pop_ad_link_rel_wrap').css('display', 'block');
    }
    

    jQuery("#pop_ad_adv_link").on('mousedown keydown', function(){
        if( !jQuery("#pop_ad_link_rel_wrap").is(":visible") ){
            jQuery('#pop_ad_link_rel_wrap').fadeIn();
        }
    });


    jQuery(".pop-ad-advanced-toggle").click(function(){
        if( !jQuery(".pop-ad-advanced-options").is(":visible") ){
            jQuery(".pop-ad-advanced-options").toggle('blind');
            jQuery(this).text('Hide Advanced Options');
        }
        else{
            jQuery(".pop-ad-advanced-options").toggle('blind');
            jQuery(this).text('Show Advanced Options');
        }
    });
 
});