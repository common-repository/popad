<?php

defined( 'ABSPATH' ) or die( 'Silence of gold' );


function PopAd_ajax_set_cookie(){
	if( isset($_GET['pop_ad_ajax']) and $_GET['pop_ad_ajax'] == 'close' and isset($_GET['pop_ad_id']) ){

		if( !empty($_GET['pop_ad_id']) ){
			$id = $_GET['pop_ad_id'];

			$get_post_status = get_post_status($id);

			if( empty($get_post_status) or $get_post_status != 'publish' ){
				wp_redirect( home_url('/'), 301 );
				exit();
			}
		}else{
			wp_redirect( home_url('/'), 301 );
			exit();
		}

		if( isset($_COOKIE["pop_ad_cookie_$id"]) ) {
			wp_redirect( home_url('/'), 301 );
			exit();
		}

		if( !isset($_COOKIE["pop_ad_cookie_$id"]) ) {
			$pop_ad_views_count = get_post_meta( $id, 'pop_ad_views_count', true );
			update_post_meta($id, 'pop_ad_views_count', ++$pop_ad_views_count);
		}

		if( get_post_meta( $id, 'pop_ad_cookie_time', true ) ){
			$hours = get_post_meta( $id, 'pop_ad_cookie_time', true );
		}else{
			$hours = 12;
		}

		if( $hours == 0 or $hours == '0' or empty($hours) ){
			$hours = 12;
		}

		$total = $hours * 3600;

		setcookie("pop_ad_cookie_$id", "pop_ad_cookie_$id", time() + $total, '/');

		exit();

	}
}
add_action('init', 'PopAd_ajax_set_cookie');


function PopAd_include_css_and_js(){
	wp_enqueue_style( 'pop-ad-style', plugins_url( '/css/style.css', __FILE__ ), array(), null);
	wp_enqueue_script( 'pop-ad-ajax', plugins_url( '/js/ajax.js', __FILE__ ), array('jquery'), null, false);

	if( get_option('pop_ad_adsense_extension_activate') ){
		$pop_ad_empty_message = 'Please enter Banner Link or Media Link or AdSense code in PopAd Options.';
	}else{
		$pop_ad_empty_message = 'Please enter Banner Link or Media Link in PopAd Options.';
	}

	wp_localize_script( 'pop-ad-ajax', 'PopAd_Object_MSG', array('PopAd_Empty_Message' => $pop_ad_empty_message ) );
}
add_action('wp_enqueue_scripts', 'PopAd_include_css_and_js');


function PopAd_if_user_role($role_name){
    if( is_user_logged_in() ){
        $get_user_id = get_current_user_id();
        $get_user_data = get_userdata($get_user_id);
        $get_roles = implode($get_user_data->roles);
        if( $role_name == $get_roles ){
            return true;
        }
    }
}


function PopAd_Display($popad_id){
	ob_start();

	$PopAd_Target_ID = $popad_id;

	if( get_post_status($PopAd_Target_ID) != 'publish' or get_post_type($PopAd_Target_ID) != 'pop_ad' ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_administrator', true ) ){
		if( PopAd_if_user_role('administrator') ){
			return false;
		}
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_editor', true ) ){
		if( PopAd_if_user_role('editor') ){
			return false;
		}
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_author', true ) ){
		if( PopAd_if_user_role('author') ){
			return false;
		}
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_contributor', true ) ){
		if( PopAd_if_user_role('contributor') ){
			return false;
		}
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_subscriber', true ) ){
		if( PopAd_if_user_role('subscriber') ){
			return false;
		}
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_visitor', true ) ){
		if( !is_user_logged_in() ){
			return false;
		}
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_home', true ) and is_home() ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_frontpage', true ) and is_front_page() ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_single', true ) and is_single() ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_page', true ) and is_page() ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_category', true ) and is_category() ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_tag', true ) and is_tag() ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_attachment', true ) and is_attachment() ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_search', true ) and is_search() ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_404', true ) and is_404() ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_quote', true ) and has_post_format('quote', null) ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_aside', true ) and has_post_format('aside', null) ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_gallery', true ) and has_post_format('gallery', null) ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_link', true ) and has_post_format('link', null) ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_image', true ) and has_post_format('image', null) ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_status', true ) and has_post_format('status', null) ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_video', true ) and has_post_format('video', null) ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_audio', true ) and has_post_format('audio', null) ){
		return false;
	}

	if( get_post_meta( $PopAd_Target_ID, 'pop_ad_exclude_chat', true ) and has_post_format('chat', null) ){
		return false;
	}

	if( get_post_meta($PopAd_Target_ID, 'pop_ad_banner_link', true) ){
		$Get_PopAd_Banner_Link = get_post_meta($PopAd_Target_ID, 'pop_ad_banner_link', true);

		if( !preg_match("/(http:\/\/)|(https:\/\/)|(ftp:\/\/)/", $Get_PopAd_Banner_Link) ){
			$Get_PopAd_Banner_Link = "http://".$Get_PopAd_Banner_Link;
		}

		$Get_PopAd_Banner_Link_Filter = apply_filters('wpt_popad_anti_adblock_banner_link', $Get_PopAd_Banner_Link, $PopAd_Target_ID);
		$Get_PopAd_Banner_Image = '<img class="pop-ad-img" src="'.esc_url($Get_PopAd_Banner_Link_Filter).'">';
	}else{
		$Get_PopAd_Banner_Link = 'null';
		$Get_PopAd_Banner_Image = null;
	}

	if( !get_post_meta($PopAd_Target_ID, 'pop_ad_banner_link', true) and get_post_meta($PopAd_Target_ID, 'pop_ad_media_link', true) ){
		$PopAd_Video_Link = get_post_meta($PopAd_Target_ID, 'pop_ad_media_link', true);
		$PopAd_Iframe = '<iframe class="pop-ad-media" src="'.PopAd_media_embed($PopAd_Video_Link, $PopAd_Target_ID).'"></iframe>';
	}else{
		$PopAd_Iframe = null;
	}

	if( get_post_meta($PopAd_Target_ID, 'pop_ad_adv_link', true) ){
		$PopAd_Adv_Link = get_post_meta($PopAd_Target_ID, 'pop_ad_adv_link', true);

		if( !preg_match("/(http:\/\/)|(https:\/\/)|(ftp:\/\/)/", $PopAd_Adv_Link) ){
			$PopAd_Adv_Link = "http://".$PopAd_Adv_Link;
		}

		if( get_post_meta($PopAd_Target_ID, 'pop_ad_link_rel', true) ){
			$PopAd_Rel = ' rel="nofollow"';
		}else{
			$PopAd_Rel = null;
		}
		
		$A_Before = '<a'.$PopAd_Rel.' class="pop-ad-link" href="'.esc_url($PopAd_Adv_Link).'" target="_blank">';
		$A_After = '</a>';
	}else{
		$A_Before = null;
		$A_After = null;
	}
	
	$PopAd_AJAX_LINK = esc_url( home_url('/?pop_ad_ajax=close&pop_ad_id='.$PopAd_Target_ID) );

	if( !isset($_COOKIE["pop_ad_cookie_$PopAd_Target_ID"]) ) {
		?>
			<div id="pop-ad-wrap" class="pop-ad-wrap">
				<div class="pop-ad-content">
					<div class="pop-ad-close" pop-ad-ajax="<?php echo esc_attr($PopAd_AJAX_LINK); ?>"></div>
					<?php if( get_post_meta($PopAd_Target_ID, 'pop_ad_banner_link', true) ) : ?>
						<?php echo $A_Before; ?>
							<?php echo $Get_PopAd_Banner_Image; ?>
						<?php echo $A_After; ?>
					<?php elseif( !get_post_meta($PopAd_Target_ID, 'pop_ad_banner_link', true) and !get_post_meta($PopAd_Target_ID, 'pop_ad_media_link', true) ) : ?>
						<?php echo apply_filters('wpt_popad_google_adsense_code', $PopAd_Target_ID); ?>
					<?php else : ?>
						<?php echo $PopAd_Iframe; ?>
					<?php endif; ?>
				</div>
			</div>
			<style type="text/css">
				#wpadminbar{
					z-index: 999999999999999999;
				}
				<?php
					if( get_option('wptpreloader_image') ){
						?>
							#pop-ad-wrap{
								z-index: 99997;
							}
						<?php
					}
				?>
			</style>
		<?php
	}

	return ob_get_clean();
}


function PopAd_display_to_theme(){
	if( is_singular() ){

		global $post;

		$get_pop_ad_target_id = get_post_meta($post->ID, 'pop_ad_target_id', true);

		if( strtolower($get_pop_ad_target_id) == 'ex' ){
			return false;
		}

		if( $get_pop_ad_target_id ){
			echo PopAd_Display($get_pop_ad_target_id);
		}
		else{
			if( get_option('pop_ad_general_id') ){
				$popad_id = get_option('pop_ad_general_id');
				echo PopAd_Display($popad_id);
			}
		}

	}

	else{
		if( get_option('pop_ad_general_id') ){
			$popad_id = get_option('pop_ad_general_id');
			echo PopAd_Display($popad_id);
		}
	}
}
add_action('wp_footer', 'PopAd_display_to_theme');