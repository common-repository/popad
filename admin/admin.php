<?php

defined( 'ABSPATH' ) or die( 'Silence of gold' );


function PopAd_register_post_type() {
	$labels = array(
					'name'                => _x('PopAd', 'PopAd', 'default'),
					'singular_name'       => _x('PopAd', 'PopAd', 'default'),
					'menu_name'           => __('PopAd', 'default'),
					'parent_item_colon'   => __('Parent PopAd', 'default'),
					'all_items'           => __('All PopAd', 'default'),
					'view_item'           => __('View PopAd', 'default'),
					'add_new_item'        => __('Add New PopAd', 'default'),
					'add_new'             => __('Add New', 'default'),
					'edit_item'           => __('Edit PopAd', 'default'),
					'update_item'         => __('Update PopAd', 'default'),
					'search_items'        => __('Search PopAd', 'default'),
					'not_found'           => __('Not Found', 'default'),
					'not_found_in_trash'  => __('Not found in Trash', 'default')
				);
	
	$capabilities = array(
						'edit_post' 				=> 'manage_options',
						'read_post' 				=> 'manage_options',
						'delete_post' 				=> 'manage_options',
						'edit_posts' 				=> 'manage_options',
						'edit_others_posts' 		=> 'manage_options',
						'publish_posts' 			=> 'manage_options',
						'read_private_posts' 		=> 'manage_options',
						'read' 						=> 'manage_options',
						'delete_posts' 				=> 'manage_options',
						'delete_private_posts'  	=> 'manage_options',
						'delete_published_posts' 	=> 'manage_options',
						'delete_others_posts' 		=> 'manage_options',
						'edit_private_posts' 		=> 'manage_options',
						'edit_published_posts' 		=> 'manage_options',
						'create_posts' 				=> 'manage_options'
					);

	$args = array(
				'label'               		=> 'pop_ad',
				'description'         		=> 'Create PopUp Advertisement and Media.',
				'labels'              		=> $labels,
				'supports'            		=> array('title'),
				'register_meta_box_cb' 		=> 'PopAd_banner_meta_box',
				'hierarchical'        		=> false,
				'public'              		=> false,
				'show_ui'             		=> true,
				'show_in_menu'        		=> true,
				'show_in_nav_menus'   		=> false,
				'show_in_admin_bar'   		=> true,
				'can_export'          		=> true,
				'has_archive'         		=> false,
				'exclude_from_search' 		=> true,
				'publicly_queryable'  		=> false,
				'capabilities'				=> $capabilities,
				'capability_type'     		=> 'post',
				'menu_icon' 				=> '',
				'rewrite'            		=> array( 'slug' => 'pop_ad' ),
				'query_var' 				=> false,
				'map_meta_cap' => false
			);

	register_post_type( 'pop_ad', $args );
}
add_action( 'init', 'PopAd_register_post_type');


function PopAd_admin_javascript() {
	global $pagenow;

	wp_enqueue_script('thickbox');

	wp_enqueue_style( 'pop-ad-menu-icon', plugins_url( '/css/css/fontello.css', __FILE__ ), array(), null);

    if ( get_post_type() == 'pop_ad' and ($pagenow == 'post.php' or $pagenow == 'post-new.php') ) {
    	wp_dequeue_script( 'autosave' );
    	wp_enqueue_script( 'pop-ad-admin-js', plugins_url( '/js/admin.js', __FILE__ ), array( 'jquery-effects-blind' ), null, false);
    }

    if ( get_post_type() == 'pop_ad' and $pagenow == 'edit.php' ) {
    	wp_enqueue_style( 'pop-ad-table-media-query', plugins_url( '/css/table_media_query.css', __FILE__ ), array(), null);
    }
}
add_action( 'admin_enqueue_scripts', 'PopAd_admin_javascript' );


function PopAd_admin_menu_icon(){
	?>
		<style>
			.menu-icon-pop_ad .wp-menu-image:before{
				font-family: "pop-ad-plugin-menu-icon";
				content: '\e800';
			}
		</style>
	<?php
}
add_action( 'admin_head', 'PopAd_admin_menu_icon' );


function PopAd_banner_meta_box() {
	add_meta_box('pop_ad_adblock_extension', 'Anti AdBlock Extension', 'PopAd_anti_adblock_extension_meta_box', 'pop_ad', 'side', 'high', null);

	add_meta_box('pop_ad_adsense_extension', 'Google AdSense Extension', 'PopAd_anti_adsense_extension_meta_box', 'pop_ad', 'side', 'high', null);

	add_meta_box('pop_ad_adv_id', 'PopAd ID', 'PopAd_adv_id', 'pop_ad', 'side', 'high', null);
	
	add_meta_box('pop_ad_control_meta_box', 'Control', 'PopAd_control_meta_box', 'pop_ad', 'side', 'low', null);

    add_meta_box('pop_ad_options_meta_box', 'Options', 'PopAd_options_meta_box', 'pop_ad', 'advanced', 'high', null);

    if( !get_option('pop_ad_visual_media_c') or get_option('pop_ad_visual_media_c') == 'show' ){
    	add_meta_box('pop_ad_visual_meta_box', 'Visual Media', 'PopAd_visual_meta_box', 'pop_ad', 'advanced', 'high', null);
    }

    remove_meta_box( 'submitdiv', 'pop_ad', 'side' );
}


function PopAd_adv_id($post){
	if( $post->post_status != 'publish' ){
		$value = '0';
		$color = '#d54e21';
		$label = 'PopAd ID will be here after creating:';
		$onClick = 'disabled="true"';
	}else{
		$value = $post->ID;
		$color = '#008ec2';
		$label = 'Copy PopAd ID and paste it into "Display PopAd" field in your <a target="_blank" href="'.esc_url( admin_url('post-new.php') ).'">Post</a> or <a target="_blank" href="'.esc_url( admin_url('post-new.php?post_type=page') ).'">Page</a> or <a class="thickbox" href="'.esc_url( admin_url('edit.php?post_type=pop_ad&page=pop-ad-general&pop_ad_tuts=cpt&TB_iframe=true&width=753&height=550') ).'">Custom Post Type</a> or <a class="thickbox" href="'.esc_url( admin_url('edit.php?post_type=pop_ad&page=pop-ad-general&TB_iframe=true&width=753&height=550') ).'">General PopAd</a>:';
		$onClick = 'onClick="this.select();"';
	}
	add_thickbox();
	?>
		<p><label for="pop_ad_adv_id"><?php echo $label; ?><input <?php echo $onClick; ?> autocomplete="off" id="pop_ad_adv_id" style="width:100% !important; cursor: text !important; color: <?php echo $color; ?> !important;" type="text" value="<?php echo $value; ?>"></label></p>
	<?php
}


function PopAd_options_meta_box($post){
	wp_nonce_field( 'pop_ad_meta_boxes_save_data', 'pop_ad_meta_boxes_nonce' );

	if( !get_post_meta($post->ID, 'pop_ad_cookie_time', true) and $post->post_status == 'publish' ){
		update_post_meta($post->ID, 'pop_ad_cookie_time', 12);
		$get_pop_ad_cookie_time = 12;
	}elseif( !get_post_meta($post->ID, 'pop_ad_cookie_time', true) and $post->post_status != 'publish' ){
		$get_pop_ad_cookie_time = 12;
	}else{
		$get_pop_ad_cookie_time = get_post_meta( $post->ID, 'pop_ad_cookie_time', true );
	}

	if( !get_post_meta($post->ID, 'pop_ad_views_count', true) ){
		$get_pop_ad_views_count = 0;
		
	}else{
		$get_pop_ad_views_count = get_post_meta( $post->ID, 'pop_ad_views_count', true );
	}

	$pop_ad_banner_link = get_post_meta( $post->ID, 'pop_ad_banner_link', true );
	$pop_ad_adv_link = get_post_meta( $post->ID, 'pop_ad_adv_link', true );
	$pop_ad_link_rel = get_post_meta( $post->ID, 'pop_ad_link_rel', true );
	$pop_ad_cookie_time = get_post_meta( $post->ID, 'pop_ad_cookie_time', true );
	$pop_ad_media_link = get_post_meta( $post->ID, 'pop_ad_media_link', true );
	$pop_ad_media_autoplay = get_post_meta( $post->ID, 'pop_ad_media_autoplay', true );

	$pop_ad_exclude_administrator = get_post_meta( $post->ID, 'pop_ad_exclude_administrator', true );
	$pop_ad_exclude_editor = get_post_meta( $post->ID, 'pop_ad_exclude_editor', true );
	$pop_ad_exclude_author = get_post_meta( $post->ID, 'pop_ad_exclude_author', true );
	$pop_ad_exclude_contributor = get_post_meta( $post->ID, 'pop_ad_exclude_contributor', true );
	$pop_ad_exclude_subscriber = get_post_meta( $post->ID, 'pop_ad_exclude_subscriber', true );
	$pop_ad_exclude_visitor = get_post_meta( $post->ID, 'pop_ad_exclude_visitor', true );

	$pop_ad_exclude_home = get_post_meta( $post->ID, 'pop_ad_exclude_home', true );
	$pop_ad_exclude_frontpage = get_post_meta( $post->ID, 'pop_ad_exclude_frontpage', true );
	$pop_ad_exclude_single = get_post_meta( $post->ID, 'pop_ad_exclude_single', true );
	$pop_ad_exclude_page = get_post_meta( $post->ID, 'pop_ad_exclude_page', true );
	$pop_ad_exclude_category = get_post_meta( $post->ID, 'pop_ad_exclude_category', true );
	$pop_ad_exclude_tag = get_post_meta( $post->ID, 'pop_ad_exclude_tag', true );
	$pop_ad_exclude_attachment = get_post_meta( $post->ID, 'pop_ad_exclude_attachment', true );
	$pop_ad_exclude_search = get_post_meta( $post->ID, 'pop_ad_exclude_search', true );
	$pop_ad_exclude_404 = get_post_meta( $post->ID, 'pop_ad_exclude_404', true );

	$pop_ad_exclude_quote = get_post_meta( $post->ID, 'pop_ad_exclude_quote', true );
	$pop_ad_exclude_aside = get_post_meta( $post->ID, 'pop_ad_exclude_aside', true );
	$pop_ad_exclude_gallery = get_post_meta( $post->ID, 'pop_ad_exclude_gallery', true );
	$pop_ad_exclude_link = get_post_meta( $post->ID, 'pop_ad_exclude_link', true );
	$pop_ad_exclude_image = get_post_meta( $post->ID, 'pop_ad_exclude_image', true );
	$pop_ad_exclude_status = get_post_meta( $post->ID, 'pop_ad_exclude_status', true );
	$pop_ad_exclude_video = get_post_meta( $post->ID, 'pop_ad_exclude_video', true );
	$pop_ad_exclude_audio = get_post_meta( $post->ID, 'pop_ad_exclude_audio', true );
	$pop_ad_exclude_chat = get_post_meta( $post->ID, 'pop_ad_exclude_chat', true );
	?>
		<p><label for="pop_ad_banner_link">
			Banner Link:
			<input autocomplete="off" id="pop_ad_banner_link" style="display:block !important; width:100% !important;" type="text" value="<?php echo esc_attr($pop_ad_banner_link); ?>" name="pop_ad_banner_link">
			<span style="display:block; font-size: 13px; font-style: italic; color:#777;">Enter your image link, <a target="_blank" href="<?php echo esc_url( admin_url('media-new.php') ); ?>">Upload</a>.</span>
		</label></p>	

		<p><label for="pop_ad_adv_link">
			Advertisement Link:
			<input autocomplete="off" id="pop_ad_adv_link" style="display:block !important; width:100% !important;" type="text" value="<?php echo esc_attr($pop_ad_adv_link); ?>" name="pop_ad_adv_link">
			<span style="display:block; font-size: 13px; font-style: italic; color:#777;">Enter your advertisement link, for example enter Affiliate link, etc. You can leave it blank if you want to display the image only.</span>
		</label></p>

		<p style="display:none;" id="pop_ad_link_rel_wrap"><label for="pop_ad_link_rel"><input id="pop_ad_link_rel" type="checkbox" value="1" name="pop_ad_link_rel" <?php checked($pop_ad_link_rel, 1, true); ?>>Rel Nofollow</label></p>		
		
		<p><label for="pop_ad_media_link">
			Media Link:
			<input autocomplete="off" id="pop_ad_media_link" style="display:block !important; width:100% !important;" type="text" value="<?php echo esc_attr($pop_ad_media_link); ?>" name="pop_ad_media_link">
			<span style="display:block; font-size: 13px; font-style: italic; color:#777;">Enter YouTube or Vimeo or SoundCloud link only. Note: If you want to display PopAd Media, you must to remove <label style="color:#0073aa !important; text-decoration:underline !important;" for="pop_ad_banner_link">Banner Link</label> option.</span>
		</label></p>

		<p style="display:none;" id="pop_ad_media_autoplay_wrap"><label for="pop_ad_media_autoplay"><input id="pop_ad_media_autoplay" type="checkbox" value="1" name="pop_ad_media_autoplay" <?php checked($pop_ad_media_autoplay, 1, true); ?>>Media Autoplay</label></p>

		<p><label for="pop_ad_cookie_time">Cookie Time:
			<input autocomplete="off" id="pop_ad_cookie_time" style="display:block !important; max-width:100px !important;" type="text" value="<?php echo esc_attr($get_pop_ad_cookie_time); ?>" name="pop_ad_cookie_time">
			<span style="display:block; font-size: 13px; font-style: italic; color:#777;">Enter cookie time, for example enter 24, will be display PopAd every 24 hours. Default is 12 hours.</span>
		</label></p>

		<p><label for="pop_ad_views_count">
			Impressions:
			<input autocomplete="off" id="pop_ad_views_count" style="display:block !important; max-width:200px !important;" type="text" value="<?php echo esc_attr($get_pop_ad_views_count); ?>" name="pop_ad_views_count">
			<span style="display:block; font-size: 13px; font-style: italic; color:#777;">This statistics of PopAd impressions, you can change the number.</span>
		</label></p>

		<strong class="pop-ad-advanced-toggle" style="color: #0073aa !important; cursor:pointer; text-decoration:underline;">Show Advanced Options</strong>

		<div class="pop-ad-advanced-options" style="display:none;">
			<p>Exclude User:
				<label style="display:block !important;" for="pop_ad_exclude_administrator"><input id="pop_ad_exclude_administrator" type="checkbox" value="1" name="pop_ad_exclude_administrator" <?php checked($pop_ad_exclude_administrator, 1, true); ?>>Administrators.</label>
				<label style="display:block !important;" for="pop_ad_exclude_editor"><input id="pop_ad_exclude_editor" type="checkbox" value="1" name="pop_ad_exclude_editor" <?php checked($pop_ad_exclude_editor, 1, true); ?>>Editors.</label>
				<label style="display:block !important;" for="pop_ad_exclude_author"><input id="pop_ad_exclude_author" type="checkbox" value="1" name="pop_ad_exclude_author" <?php checked($pop_ad_exclude_author, 1, true); ?>>Authors.</label>
				<label style="display:block !important;" for="pop_ad_exclude_contributor"><input id="pop_ad_exclude_contributor" type="checkbox" value="1" name="pop_ad_exclude_contributor" <?php checked($pop_ad_exclude_contributor, 1, true); ?>>Contributors.</label>
				<label style="display:block !important;" for="pop_ad_exclude_subscriber"><input id="pop_ad_exclude_subscriber" type="checkbox" value="1" name="pop_ad_exclude_subscriber" <?php checked($pop_ad_exclude_subscriber, 1, true); ?>>Subscribers.</label>
				<label style="display:block !important;" for="pop_ad_exclude_visitor"><input id="pop_ad_exclude_visitor" type="checkbox" value="1" name="pop_ad_exclude_visitor" <?php checked($pop_ad_exclude_visitor, 1, true); ?>>Visitors.</label>
				<span style="display:block; font-size: 13px; font-style: italic; color:#777;">Use this options if you want to prevent PopAd display to some users, for example if you want to prevent PopAd display to the Editors, choose option "Editors".<br>Another example: If you want to display PopAd to the Editors only, choose all options and do not choose "Editors" option.<br>Default is display PopAd to all users and visitors.</span>
			</p>

			<p>Exclude Screen (for general PopAd):
				<label style="display:block !important;" for="pop_ad_exclude_home"><input id="pop_ad_exclude_home" type="checkbox" value="1" name="pop_ad_exclude_home" <?php checked($pop_ad_exclude_home, 1, true); ?>>Homepage.</label>
				<label style="display:block !important;" for="pop_ad_exclude_frontpage"><input id="pop_ad_exclude_frontpage" type="checkbox" value="1" name="pop_ad_exclude_frontpage" <?php checked($pop_ad_exclude_frontpage, 1, true); ?>>Frontpage.</label>
				<label style="display:block !important;" for="pop_ad_exclude_single"><input id="pop_ad_exclude_single" type="checkbox" value="1" name="pop_ad_exclude_single" <?php checked($pop_ad_exclude_single, 1, true); ?>>All Posts (post type post and all custom post type).</label>
				<label style="display:block !important;" for="pop_ad_exclude_page"><input id="pop_ad_exclude_page" type="checkbox" value="1" name="pop_ad_exclude_page" <?php checked($pop_ad_exclude_page, 1, true); ?>>All Pages (post type page).</label>
				<label style="display:block !important;" for="pop_ad_exclude_category"><input id="pop_ad_exclude_category" type="checkbox" value="1" name="pop_ad_exclude_category" <?php checked($pop_ad_exclude_category, 1, true); ?>>Categories.</label>
				<label style="display:block !important;" for="pop_ad_exclude_tag"><input id="pop_ad_exclude_tag" type="checkbox" value="1" name="pop_ad_exclude_tag" <?php checked($pop_ad_exclude_tag, 1, true); ?>>Tags.</label>
				<label style="display:block !important;" for="pop_ad_exclude_attachment"><input id="pop_ad_exclude_attachment" type="checkbox" value="1" name="pop_ad_exclude_attachment" <?php checked($pop_ad_exclude_attachment, 1, true); ?>>Attachment.</label>
				<label style="display:block !important;" for="pop_ad_exclude_search"><input id="pop_ad_exclude_search" type="checkbox" value="1" name="pop_ad_exclude_search" <?php checked($pop_ad_exclude_search, 1, true); ?>>Search.</label>
				<label style="display:block !important;" for="pop_ad_exclude_404"><input id="pop_ad_exclude_404" type="checkbox" value="1" name="pop_ad_exclude_404" <?php checked($pop_ad_exclude_404, 1, true); ?>>404 Error Page.</label>
				<label style="display:block !important;" for="pop_ad_exclude_quote"><input id="pop_ad_exclude_quote" type="checkbox" value="1" name="pop_ad_exclude_quote" <?php checked($pop_ad_exclude_quote, 1, true); ?>>Post Format Quote.</label>
				<label style="display:block !important;" for="pop_ad_exclude_aside"><input id="pop_ad_exclude_aside" type="checkbox" value="1" name="pop_ad_exclude_aside" <?php checked($pop_ad_exclude_aside, 1, true); ?>>Post Format Aside.</label>
				<label style="display:block !important;" for="pop_ad_exclude_gallery"><input id="pop_ad_exclude_gallery" type="checkbox" value="1" name="pop_ad_exclude_gallery" <?php checked($pop_ad_exclude_gallery, 1, true); ?>>Post Format Gallery.</label>
				<label style="display:block !important;" for="pop_ad_exclude_link"><input id="pop_ad_exclude_link" type="checkbox" value="1" name="pop_ad_exclude_link" <?php checked($pop_ad_exclude_link, 1, true); ?>>Post Format Link.</label>
				<label style="display:block !important;" for="pop_ad_exclude_image"><input id="pop_ad_exclude_image" type="checkbox" value="1" name="pop_ad_exclude_image" <?php checked($pop_ad_exclude_image, 1, true); ?>>Post Format Image.</label>
				<label style="display:block !important;" for="pop_ad_exclude_video"><input id="pop_ad_exclude_video" type="checkbox" value="1" name="pop_ad_exclude_video" <?php checked($pop_ad_exclude_video, 1, true); ?>>Post Format Video.</label>
				<label style="display:block !important;" for="pop_ad_exclude_audio"><input id="pop_ad_exclude_audio" type="checkbox" value="1" name="pop_ad_exclude_audio" <?php checked($pop_ad_exclude_audio, 1, true); ?>>Post Format Audio.</label>
				<label style="display:block !important;" for="pop_ad_exclude_chat"><input id="pop_ad_exclude_chat" type="checkbox" value="1" name="pop_ad_exclude_chat" <?php checked($pop_ad_exclude_chat, 1, true); ?>>Post Format Chat.</label>
				<label style="display:block !important;" for="pop_ad_exclude_status"><input id="pop_ad_exclude_status" type="checkbox" value="1" name="pop_ad_exclude_status" <?php checked($pop_ad_exclude_status, 1, true); ?>>Post Format Status.</label>
				<span style="display:block; font-size: 13px; font-style: italic; color:#777;">Use this options if you want to prevent General PopAd display in some screens, for example if you want to display General PopAd on the entire website and you want to exclude Homepage, choose "Homepage" option, etc.<br>Another example: If you want to display General PopAd in all posts only and you do not want on the entire website, choose all options and do not choose "All Posts" option.<br>This options for General PopAd only, default is display General PopAd in all screens.</span>
			</p>
		</div>
	<?php
}


function PopAd_boxes_meta_save_data( $post_id ) {
    if ( !isset($_POST['pop_ad_meta_boxes_nonce']) or !wp_verify_nonce( $_POST['pop_ad_meta_boxes_nonce'], 'pop_ad_meta_boxes_save_data' ) ) {
        return;
    }

    if ( !current_user_can('manage_options', $post_id) ) {
    	return;
    }

  	$pop_ad_banner_link = sanitize_text_field( $_POST['pop_ad_banner_link'] );
    update_post_meta( $post_id, 'pop_ad_banner_link', $pop_ad_banner_link );

    $pop_ad_adv_link = sanitize_text_field( $_POST['pop_ad_adv_link'] );
    update_post_meta( $post_id, 'pop_ad_adv_link', $pop_ad_adv_link );
 	
    $pop_ad_media_link = sanitize_text_field( $_POST['pop_ad_media_link'] );
    update_post_meta( $post_id, 'pop_ad_media_link', $pop_ad_media_link );

    if( !empty($_POST['pop_ad_cookie_time']) and !preg_match('/^[0-9]+$/', $_POST['pop_ad_cookie_time']) ){
    	$pop_ad_cookie_time = '';
    }else{
    	$pop_ad_cookie_time = sanitize_text_field( $_POST['pop_ad_cookie_time'] );
    }
    update_post_meta( $post_id, 'pop_ad_cookie_time', $pop_ad_cookie_time );

    if( !empty($_POST['pop_ad_views_count']) and !preg_match('/^[0-9]+$/', $_POST['pop_ad_views_count']) ){
    	$pop_ad_views_count = get_post_meta( $post_id, 'pop_ad_views_count', true );
    }else{
    	$pop_ad_views_count = sanitize_text_field( $_POST['pop_ad_views_count'] );
    }
    update_post_meta( $post_id, 'pop_ad_views_count', $pop_ad_views_count );

    if( !empty($_POST['pop_ad_link_rel']) ){
    	$pop_ad_link_rel = sanitize_text_field( $_POST['pop_ad_link_rel'] );
    }else{
    	$pop_ad_link_rel = '';
    }
    update_post_meta( $post_id, 'pop_ad_link_rel', $pop_ad_link_rel );

    if( !empty($_POST['pop_ad_media_autoplay']) ){
    	$pop_ad_media_autoplay = sanitize_text_field( $_POST['pop_ad_media_autoplay'] );
    }else{
    	$pop_ad_media_autoplay = '';
    }
    update_post_meta( $post_id, 'pop_ad_media_autoplay', $pop_ad_media_autoplay );

    if( !empty($_POST['pop_ad_exclude_administrator']) ){
    	$pop_ad_exclude_administrator = sanitize_text_field( $_POST['pop_ad_exclude_administrator'] );
    }else{
    	$pop_ad_exclude_administrator = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_administrator', $pop_ad_exclude_administrator );

    if( !empty($_POST['pop_ad_exclude_editor']) ){
    	$pop_ad_exclude_editor = sanitize_text_field( $_POST['pop_ad_exclude_editor'] );
    }else{
    	$pop_ad_exclude_editor = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_editor', $pop_ad_exclude_editor );

    if( !empty($_POST['pop_ad_exclude_author']) ){
    	$pop_ad_exclude_author = sanitize_text_field( $_POST['pop_ad_exclude_author'] );
    }else{
    	$pop_ad_exclude_author = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_author', $pop_ad_exclude_author );

    if( !empty($_POST['pop_ad_exclude_contributor']) ){
    	$pop_ad_exclude_contributor = sanitize_text_field( $_POST['pop_ad_exclude_contributor'] );
    }else{
    	$pop_ad_exclude_contributor = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_contributor', $pop_ad_exclude_contributor );

    if( !empty($_POST['pop_ad_exclude_subscriber']) ){
    	$pop_ad_exclude_subscriber = sanitize_text_field( $_POST['pop_ad_exclude_subscriber'] );
    }else{
    	$pop_ad_exclude_subscriber = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_subscriber', $pop_ad_exclude_subscriber );

    if( !empty($_POST['pop_ad_exclude_visitor']) ){
    	$pop_ad_exclude_visitor = sanitize_text_field( $_POST['pop_ad_exclude_visitor'] );
    }else{
    	$pop_ad_exclude_visitor = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_visitor', $pop_ad_exclude_visitor );

    if( !empty($_POST['pop_ad_exclude_home']) ){
    	$pop_ad_exclude_home = sanitize_text_field( $_POST['pop_ad_exclude_home'] );
    }else{
    	$pop_ad_exclude_home = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_home', $pop_ad_exclude_home );

    if( !empty($_POST['pop_ad_exclude_frontpage']) ){
    	$pop_ad_exclude_frontpage = sanitize_text_field( $_POST['pop_ad_exclude_frontpage'] );
    }else{
    	$pop_ad_exclude_frontpage = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_frontpage', $pop_ad_exclude_frontpage );

    if( !empty($_POST['pop_ad_exclude_single']) ){
    	$pop_ad_exclude_single = sanitize_text_field( $_POST['pop_ad_exclude_single'] );
    }else{
    	$pop_ad_exclude_single = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_single', $pop_ad_exclude_single );

    if( !empty($_POST['pop_ad_exclude_page']) ){
    	$pop_ad_exclude_page = sanitize_text_field( $_POST['pop_ad_exclude_page'] );
    }else{
    	$pop_ad_exclude_page = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_page', $pop_ad_exclude_page );

    if( !empty($_POST['pop_ad_exclude_category']) ){
    	$pop_ad_exclude_category = sanitize_text_field( $_POST['pop_ad_exclude_category'] );
    }else{
    	$pop_ad_exclude_category = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_category', $pop_ad_exclude_category );

    if( !empty($_POST['pop_ad_exclude_tag']) ){
    	$pop_ad_exclude_tag = sanitize_text_field( $_POST['pop_ad_exclude_tag'] );
    }else{
    	$pop_ad_exclude_tag = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_tag', $pop_ad_exclude_tag );

    if( !empty($_POST['pop_ad_exclude_attachment']) ){
    	$pop_ad_exclude_attachment = sanitize_text_field( $_POST['pop_ad_exclude_attachment'] );
    }else{
    	$pop_ad_exclude_attachment = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_attachment', $pop_ad_exclude_attachment );

    if( !empty($_POST['pop_ad_exclude_search']) ){
    	$pop_ad_exclude_search = sanitize_text_field( $_POST['pop_ad_exclude_search'] );
    }else{
    	$pop_ad_exclude_search = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_search', $pop_ad_exclude_search );

    if( !empty($_POST['pop_ad_exclude_404']) ){
    	$pop_ad_exclude_404 = sanitize_text_field( $_POST['pop_ad_exclude_404'] );
    }else{
    	$pop_ad_exclude_404 = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_404', $pop_ad_exclude_404 );

    if( !empty($_POST['pop_ad_exclude_quote']) ){
    	$pop_ad_exclude_quote = sanitize_text_field( $_POST['pop_ad_exclude_quote'] );
    }else{
    	$pop_ad_exclude_quote = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_quote', $pop_ad_exclude_quote );

    if( !empty($_POST['pop_ad_exclude_aside']) ){
    	$pop_ad_exclude_aside = sanitize_text_field( $_POST['pop_ad_exclude_aside'] );
    }else{
    	$pop_ad_exclude_aside = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_aside', $pop_ad_exclude_aside );

    if( !empty($_POST['pop_ad_exclude_gallery']) ){
    	$pop_ad_exclude_gallery = sanitize_text_field( $_POST['pop_ad_exclude_gallery'] );
    }else{
    	$pop_ad_exclude_gallery = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_gallery', $pop_ad_exclude_gallery );

    if( !empty($_POST['pop_ad_exclude_link']) ){
		$pop_ad_exclude_link = sanitize_text_field( $_POST['pop_ad_exclude_link'] );
    }else{
    	$pop_ad_exclude_link = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_link', $pop_ad_exclude_link );

    if( !empty($_POST['pop_ad_exclude_image']) ){
    	$pop_ad_exclude_image = sanitize_text_field( $_POST['pop_ad_exclude_image'] );
    }else{
    	$pop_ad_exclude_image = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_image', $pop_ad_exclude_image );

    if( !empty($_POST['pop_ad_exclude_status']) ){
    	$pop_ad_exclude_status = sanitize_text_field( $_POST['pop_ad_exclude_status'] );
    }else{
    	$pop_ad_exclude_status = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_status', $pop_ad_exclude_status );

    if( !empty($_POST['pop_ad_exclude_video']) ){
    	$pop_ad_exclude_video = sanitize_text_field( $_POST['pop_ad_exclude_video'] );
    }else{
    	$pop_ad_exclude_video = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_video', $pop_ad_exclude_video );

    if( !empty($_POST['pop_ad_exclude_audio']) ){
    	$pop_ad_exclude_audio = sanitize_text_field( $_POST['pop_ad_exclude_audio'] );
    }else{
    	$pop_ad_exclude_audio = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_audio', $pop_ad_exclude_audio );

    if( !empty($_POST['pop_ad_exclude_chat']) ){
    	$pop_ad_exclude_chat = sanitize_text_field( $_POST['pop_ad_exclude_chat'] );
    }else{
    	$pop_ad_exclude_chat = '';
    }
    update_post_meta( $post_id, 'pop_ad_exclude_chat', $pop_ad_exclude_chat );
}
add_action( 'save_post', 'PopAd_boxes_meta_save_data');


function PopAd_visual_meta_box($post){
	?>
		<p id="pop-ad-visual-wrap">Your media, banner, will be here...</p>
	<?php
}


function PopAd_control_meta_box($post){
	$post_id = $post->ID;
	$info_visual_media = '<span style="display:block; font-size: 13px; font-style: italic; color:#777;">If you want to hide or show Visual Media box, click on the link.</span>';
	if( $post->post_status != 'publish' ){
		$publish = 0;
		$name = "publish";
		$value = "Create";
		$trash_post = null;
		$double_post = null;
	}else{
		$publish = 1;
		$name = "save";
		$value = "Update";
		$wp_nonce = wp_create_nonce( 'trash-post_' . $post_id );
		$delete_link = admin_url("post.php?post=$post_id&action=trash&_wpnonce=$wp_nonce");
		$trash_post = '<p><a style="color:#a00 !important;" href="'.esc_url($delete_link).'">Move PopAd to Trash</a></p>';
		$double_post_link = admin_url("post.php?post=$post_id&pop_ad_double=1");
		$double_post = '<p><a href="'.esc_url($double_post_link).'">Duplicate</a><span style="display:block; font-size: 13px; font-style: italic; color:#777;">If you want to create PopAd with this PopAd options, click on "Duplicate" link.</span></p>';
	}
	?>
		<p><input type="submit" name="<?php echo $name; ?>" id="publish" class="button button-primary button-large" value="<?php echo $value; ?>"></p>
		<?php
			if( $publish == 1 ){
				?>
					<p><a href="<?php echo esc_url(admin_url('post.php?pop_ad_reset_cookie_time='.$post->ID)); ?>">Reset Cookie</a><span style="display:block; font-size: 13px; font-style: italic; color:#777;">If you want to test the PopAd, and you can not wait for hours in <label style="color:#0073aa !important; text-decoration:underline !important;" for="pop_ad_cookie_time">Cookie Time</label> option, click on "Reset Cookie" link to reset cookie time.</span></p>
					
					<?php if ( !get_option('pop_ad_visual_media_c') or get_option('pop_ad_visual_media_c') == 'show' ) : ?>
						<p><a href="<?php echo esc_url(admin_url("post.php?pop_ad_visual_media_c=hide&post=$post_id")); ?>">Hide Visual Media</a><?php echo $info_visual_media; ?></p>
					<?php else : ?>
						<p><a href="<?php echo esc_url(admin_url("post.php?pop_ad_visual_media_c=show&post=$post_id")); ?>">Show Visual Media</a><?php echo $info_visual_media; ?></p>
					<?php endif; ?>
				<?php
			}else{
				?>
					<?php if ( !get_option('pop_ad_visual_media_c') or get_option('pop_ad_visual_media_c') == 'show' ) : ?>
						<p><a href="<?php echo esc_url(admin_url("post-new.php?post_type=pop_ad&pop_ad_visual_media_c=hide")); ?>">Hide Visual Media</a><?php echo $info_visual_media; ?></p>
					<?php else : ?>
						<p><a href="<?php echo esc_url(admin_url("post-new.php?post_type=pop_ad&pop_ad_visual_media_c=show")); ?>">Show Visual Media</a><?php echo $info_visual_media; ?></p>
					<?php endif; ?>
				<?php
			}
		?>
		<?php echo $double_post; ?>
		<?php echo $trash_post; ?>
	<?php
}


function PopAd_anti_adblock_extension_meta_box($post){
	$text_filter = apply_filters('wpt_popad_anti_adblock_meta_box_text', '<p>Do you want to display Banner Ads when AdBlock is enabled?!<br><a target="_blank" href="http://store.wp-plugins.in/PopAd_Anti_AdBlock_Extension">Buy PopAd Anti AdBlock Extension!</a></p>');
	echo $text_filter;
}


function PopAd_anti_adsense_extension_meta_box($post){
	$text_filter = apply_filters('wpt_popad_google_adsense_meta_box', '<p>Do you want to display AdSense in PopAd? <a target="_blank" href="http://store.wp-plugins.in/PopAd_AdSense_Extension">Buy PopAd AdSense Extension!</a></p>');
	echo $text_filter;
}


function PopAd_reset_cookie_time(){
	if( isset($_GET['pop_ad_reset_cookie_time']) and !empty($_GET['pop_ad_reset_cookie_time']) and current_user_can('manage_options') ){
		$id = $_GET['pop_ad_reset_cookie_time'];

		if( get_post_meta( $id, 'pop_ad_cookie_time', true ) ){
			$hours = get_post_meta( $id, 'pop_ad_cookie_time', true );
		}else{
			$hours = 12;
		}

		$total = $hours * 3600;

		setcookie("pop_ad_cookie_$id", "pop_ad_cookie_$id", time() - $total, '/');

		wp_redirect( admin_url("post.php?post=$id&action=edit&pop_ad_reset_cookie_time_a=$hours&message=1") );
		exit();  
	}
}
add_action('admin_init', 'PopAd_reset_cookie_time');


function PopAd_double_post(){
	if( isset($_GET['pop_ad_double']) and isset($_GET['post']) and $_GET['pop_ad_double'] == 1 and current_user_can('manage_options') ){
		$post_id = $_GET['post'];

		$get_post_meta = get_post_meta($post_id);

		$post_meta = array();

		foreach( $get_post_meta as $meta_key => $meta_value ){
   			$post_meta[$meta_key] = $meta_value[0];
		}

		$post_meta['pop_ad_double_post_count'] = 0;

		if( !get_post_meta($post_id, 'pop_ad_double_post_count', true) ){
			update_post_meta($post_id, 'pop_ad_double_post_count', 1);
			$count = " ".get_post_meta($post_id, 'pop_ad_double_post_count', true);
		}else{
			$get_double_post_count = get_post_meta($post_id, 'pop_ad_double_post_count', true);
			update_post_meta($post_id, 'pop_ad_double_post_count', ++$get_double_post_count);
			$count = " ".$get_double_post_count;
		}

		$post_title = "Copy".$count." of PopAd ID ".$post_id;

		$post_args = array(
  						'post_title'    => "Copy$count of PopAd ID $post_id",
  						'post_type'		=> 'pop_ad',
  						'post_status'   => 'publish',
  						'meta_input'	=> $post_meta
					);

		wp_insert_post($post_args);

		wp_redirect( esc_url(admin_url("edit.php?post_type=pop_ad")) );

		exit();  
	}
}
add_action('admin_init', 'PopAd_double_post');