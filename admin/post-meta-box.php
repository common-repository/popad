<?php

defined( 'ABSPATH' ) or die( 'Silence of gold' );


function PopAd_post_meta_box(){
    add_meta_box('pop-ad-post-target', 'Display PopAd', 'PopAd_post_target', 'post', 'side', 'high', null);
    add_meta_box('pop-ad-page-target', 'Display PopAd', 'PopAd_post_target', 'page', 'side', 'high', null);
}
add_action( 'add_meta_boxes', 'PopAd_post_meta_box' );


function PopAd_post_target($post){
    wp_nonce_field( 'pop_ad_meta_box_save_data', 'pop_ad_meta_box_nonce' );
    $pop_ad_target_id = get_post_meta( $post->ID, 'pop_ad_target_id', true );
    add_thickbox();
    ?>
    <p><label for="pop_ad_target_id">
        PopAd ID:<input autocomplete="off" id="pop_ad_target_id" style="display:block !important; width:100% !important;" type="text" value="<?php echo esc_attr($pop_ad_target_id); ?>" name="pop_ad_target_id">
        <span style="display:block; font-size: 13px; font-style: italic; color:#777;">Enter PopAd ID to display your PopAd in this <?php echo get_post_type(); ?>.<br>If you have <a class="thickbox" href="<?php echo esc_url( admin_url('edit.php?post_type=pop_ad&page=pop-ad-general&TB_iframe=true&width=753&height=550') ); ?>">General PopAd</a>, and you want to exclude this <?php echo get_post_type(); ?> from General PopAd, just enter "ex".<br>If you have General PopAd, and you want to display another PopAd in this <?php echo get_post_type(); ?>, just enter another PopAd ID.</span>
    </label></p>
    <?php
}


function PopAd_post_meta_save_data( $post_id ) {
    if ( !isset($_POST['pop_ad_meta_box_nonce']) or !wp_verify_nonce( $_POST['pop_ad_meta_box_nonce'], 'pop_ad_meta_box_save_data') ) {
        return;
    }

    if ( !current_user_can('edit_post', $post_id) or !current_user_can('edit_page', $post_id) ) {
        return;
    }

    if( !empty($_POST['pop_ad_target_id']) and !preg_match('/^[0-9]+$/', $_POST['pop_ad_target_id']) and strtolower($_POST['pop_ad_target_id']) != 'ex' ){
        $pop_ad_target_id = '';
    }else{
        $pop_ad_target_id = sanitize_text_field( $_POST['pop_ad_target_id'] );
    }
    update_post_meta( $post_id, 'pop_ad_target_id', $pop_ad_target_id );
}
add_action( 'save_post', 'PopAd_post_meta_save_data');