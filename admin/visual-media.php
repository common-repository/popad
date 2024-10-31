<?php

defined( 'ABSPATH' ) or die( 'Silence of gold' );


function PopAd_visual_media() {
    global $pagenow;
    if ( get_post_type() == 'pop_ad' and ($pagenow == 'post.php' or $pagenow == 'post-new.php') ) {
    	?>
    		<script type="text/javascript">
    			/*
    				PopAd Visual Media Script - By Alobaidi
    				http://wp-plugins.in | http://wp-plugins.in/PopAd_Plugin
    				Copyright (c) 2016, Alobaidi.
				*/

    			jQuery( function() {

    				function PopAd_JS_Visual_Media(pop_ad_media_link_js){
    					jQuery.ajax({
            				type: 'GET',
 
            				url: pop_ad_media_link_js,
 
            				data: false,
 
            				cache: false,
 
            				contentType: false,
 
            				processData: false,
 
            				success: function(PopAdVisualMediaResult) {
                				jQuery('#pop-ad-visual-wrap').html(PopAdVisualMediaResult);
            				}
        				});
    				}


    				if( jQuery('#pop_ad_banner_link').val().length === 0 && jQuery('#pop_ad_media_link').val().length === 0 ){
    					jQuery('#pop-ad-visual-wrap').html('Your media, banner, will be here...');
    				}else{
    					if( jQuery('#pop_ad_banner_link').val().length > 0 ){
                            if( jQuery('#pop_ad_adv_link').val().length > 0 ){
                                var pop_ad_banner_link_visual = jQuery('#pop_ad_banner_link').val();
                                var pop_ad_adv_link_visual = jQuery('#pop_ad_adv_link').val();
                                jQuery('#pop-ad-visual-wrap').html('<a target="_blank" href="'+pop_ad_adv_link_visual+'"><img src="'+pop_ad_banner_link_visual+'"></a>');
                            }else{
                                var pop_ad_banner_link_visual = jQuery('#pop_ad_banner_link').val();
                                jQuery('#pop-ad-visual-wrap').html('<img src="'+pop_ad_banner_link_visual+'">');
                            }
    					}
    					if( jQuery('#pop_ad_banner_link').val().length === 0 && jQuery('#pop_ad_media_link').val().length > 0 ){
    						var pop_ad_media_link_visual = jQuery('#pop_ad_media_link').val();
        					var PopAd_AJAX_Media_Embed_Link = "<?php echo esc_js( admin_url('/?pop_ad_visual_media=') ); ?>" + pop_ad_media_link_visual;
        					PopAd_JS_Visual_Media(PopAd_AJAX_Media_Embed_Link);
    					}
    				}


    				jQuery("#pop_ad_banner_link").on('change keypress keyup paste drop', function(){
                        var pop_ad_banner_link_visual = jQuery('#pop_ad_banner_link').val();
    					if( jQuery('#pop_ad_adv_link').val().length === 0 ){
                            jQuery('#pop-ad-visual-wrap').html('<img src="'+pop_ad_banner_link_visual+'">');
                        }else{
                            var pop_ad_adv_link_visual = jQuery('#pop_ad_adv_link').val();
                            jQuery('#pop-ad-visual-wrap').html('<a target="_blank" href="'+pop_ad_adv_link_visual+'"><img src="'+pop_ad_banner_link_visual+'"></a>');
                        }
    					if( jQuery('#pop_ad_banner_link').val().length === 0 ){
    						jQuery('#pop-ad-visual-wrap').html('Your media, banner, will be here...');
    						if( jQuery('#pop_ad_media_link').val().length > 0 ){
    							var pop_ad_media_link_visual_ch = jQuery('#pop_ad_media_link').val();
        						var PopAd_AJAX_Media_Embed_Link_ch = "<?php echo esc_url( admin_url('/?pop_ad_visual_media=') ); ?>" + pop_ad_media_link_visual_ch;
    							PopAd_JS_Visual_Media(PopAd_AJAX_Media_Embed_Link_ch);
    						}
    					}
    				});


                    jQuery("#pop_ad_adv_link").on('change keypress keyup paste drop', function(){
                        if( jQuery('#pop_ad_banner_link').val().length > 0 ){
                            var pop_ad_banner_link_visual = jQuery('#pop_ad_banner_link').val();
                            if( jQuery('#pop_ad_adv_link').val().length === 0 ){
                                jQuery('#pop-ad-visual-wrap').html('<img src="'+pop_ad_banner_link_visual+'">');
                            }else{
                                var pop_ad_adv_link_visual = jQuery('#pop_ad_adv_link').val();
                                jQuery('#pop-ad-visual-wrap').html('<a target="_blank" href="'+pop_ad_adv_link_visual+'"><img src="'+pop_ad_banner_link_visual+'"></a>');
                            }
                        }
                    });


    				jQuery("#pop_ad_media_link").on('change paste drop', function(){
    					if( jQuery('#pop_ad_banner_link').val().length === 0 ){
    						if( jQuery('#pop_ad_media_link').val().length === 0 ){
    							jQuery('#pop-ad-visual-wrap').html('Your media, banner, will be here...');
    						}else{
								var pop_ad_media_link_visual_ch = jQuery('#pop_ad_media_link').val();
        						var PopAd_AJAX_Media_Embed_Link_ch = "<?php echo esc_url( admin_url('/?pop_ad_visual_media=') ); ?>" + pop_ad_media_link_visual_ch;
    							PopAd_JS_Visual_Media(PopAd_AJAX_Media_Embed_Link_ch);
    						}
    					}
    				});

    			});
    		</script>
    	<?php
    }
}
add_action( 'admin_head', 'PopAd_visual_media' );


function PopAd_visual_media_ajax_result(){
	if( isset($_GET['pop_ad_visual_media']) and current_user_can('manage_options') ){
		$media_link = $_GET['pop_ad_visual_media'];
		echo '<iframe style="display:block !important; width:100% !important; max-width:100% !important; height:400px !important;" src="'.PopAd_media_embed($media_link, 0, 0).'">';
		exit();
	}
}
add_action('admin_init', 'PopAd_visual_media_ajax_result');


function PopAd_visual_media_control(){
    if( isset($_GET['pop_ad_visual_media_c']) and $_GET['pop_ad_visual_media_c'] == 'hide' and current_user_can('manage_options') ){
        update_option('pop_ad_visual_media_c', 'hide');
        if( isset($_GET['post']) ){
            $post_id = $_GET['post'];
            $url = admin_url("post.php?&post=$post_id&action=edit&pop_ad_visual_media_a=1&message=1");
        }else{
            $url = admin_url("post-new.php?post_type=pop_ad&pop_ad_visual_media_a=1&message=1");
        }
        wp_redirect($url);
        exit();
    }

    if( isset($_GET['pop_ad_visual_media_c']) and $_GET['pop_ad_visual_media_c'] == 'show' and current_user_can('manage_options') ){
        update_option('pop_ad_visual_media_c', 'show');
        if( isset($_GET['post']) ){
            $post_id = $_GET['post'];
            $url = admin_url("post.php?&post=$post_id&action=edit&pop_ad_visual_media_a=1&message=1");
        }else{
            $url = admin_url("post-new.php?post_type=pop_ad&pop_ad_visual_media_a=1&message=1");
        }
        wp_redirect($url);
        exit();
    }
}
add_action('admin_init', 'PopAd_visual_media_control');