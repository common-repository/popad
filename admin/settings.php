<?php

defined( 'ABSPATH' ) or die( 'Silence of gold' );


function PopAd_general_settings_submenu() {
    if( isset($_GET['post_type']) and $_GET['post_type'] == 'pop_ad' and isset($_GET['page']) and $_GET['page'] == 'pop-ad-general' and isset($_GET['pop_ad_tuts']) and $_GET['pop_ad_tuts'] == 'cpt' ){
        $pop_ad_add_submenu_page_title = 'Display PopAd in Custom Post Type';
    }else{
        $pop_ad_add_submenu_page_title = 'General PopAd';
    }
    add_submenu_page("edit.php?post_type=pop_ad", $pop_ad_add_submenu_page_title, $pop_ad_add_submenu_page_title, 'manage_options', 'pop-ad-general', 'PopAd_general_settings_page' );
}
add_action('admin_menu', 'PopAd_general_settings_submenu');


function PopAd_general_settings_options(){
    add_settings_section('pop_ad_settings_section', false, false, 'pop_ad_options');
    add_settings_field( "pop_ad_general_id", 'Display PopAd', "PopAd_general_id", "pop_ad_options", "pop_ad_settings_section", array('label_for' => 'pop_ad_general_id') );
    register_setting( 'pop_ad_settings_section', 'pop_ad_general_id', 'PopAd_general_id_validation' );
}
add_action( 'admin_init', 'PopAd_general_settings_options' );


function PopAd_general_settings_page(){
	?>
		<div class="wrap">
            <?php
                if( isset($_GET['post_type']) and $_GET['post_type'] == 'pop_ad' and isset($_GET['page']) and $_GET['page'] == 'pop-ad-general' and isset($_GET['pop_ad_tuts']) and $_GET['pop_ad_tuts'] == 'cpt' ){
                    ?>
                        <div class="postbox">
                            <div class="inside">
                                <h2>Display PopAd in Custom Post Type</h2>
                                <p>To display <strong>"Display PopAd" Box</strong> in your custom post type, just copy this code and paste it into your <strong>functions.php</strong> file:</p>
                                <pre style="background-color:#f9f9f9 !important;white-space:pre-line !important;margin:0 !important;padding:12px !important;border:1px solid #eee !important;">
                                    function PopAd_to_my_custom_post_type(){
                                        add_meta_box('pop-ad-YOUR_CUSTOM_POST_TYPE_NAME_HERE-target', 'Display PopAd', 'PopAd_post_target', 'YOUR_CUSTOM_POST_TYPE_NAME_HERE', 'side', 'high', null);
                                    }
                                    add_action( 'add_meta_boxes', 'PopAd_to_my_custom_post_type' );
                                </pre>
                                <p>Change <strong>"YOUR_CUSTOM_POST_TYPE_NAME_HERE"</strong> to your custom post type name.</p>
                                <p><strong>Another Way:</strong> In your custom post type, create a new custom field, in <strong>"Name"</strong> field enter <strong>"pop_ad_target_id"</strong>, and in <strong>"Value"</strong> field enter your <strong>PopAd ID</strong>.</p>
                                <p><strong>Note:</strong> General PopAd is working with all custom post types, automatically.</p>
                            </div>
                        </div>
                    <?php
                }
                else{
                    ?>
                        <h2>General PopAd</h2>

                        <?php
                            if( isset($_GET['settings-updated']) and $_GET['settings-updated'] == 'true' ){
                                ?>
                                    <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
                                        <p><strong>Settings saved.</strong></p>
                                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                                    </div>
                            <?php
                        }
                        ?>

                        <p>If you want to display PopAd on the entire website (all pages, all posts, homepage, etc), enter PopAd ID:</p>

                        <form method="post" action="options.php">
                            <?php
                                settings_fields("pop_ad_settings_section");
                                do_settings_sections("pop_ad_options");
                                submit_button();
                            ?>
                        </form>
                    <?php
                }
            ?>

            <div class="tool-box">
                <h3 class="title">Recommended Links</h3>
                <p>Get collection of 87 WordPress themes for $69 only, a lot of features and free support! <a href="http://j.mp/ET_WPTime_ref_pl" target="_blank">Get it now</a>.</p>
                <p><a title="Get collection of 87 WordPress themes for $69 only, a lot of features and free support!" href="http://j.mp/ET_WPTime_ref_pl" target="_blank"><img src="<?php echo plugins_url('/banner/banner.jpg', __FILE__); ?>"></a></p>
            </div>
        </div>
	<?php
}


function PopAd_general_id_validation( $input ){
    if ( isset($_POST['pop_ad_general_id']) ) {
        if( !empty($_POST['pop_ad_general_id']) and !preg_match('/^[0-9]+$/', $_POST['pop_ad_general_id']) ){
            return 'Please enter number only.';
        }else{
            return wp_filter_nohtml_kses($input);
        }
    }
}


function PopAd_general_id(){
	?>
		<input autocomplete="off" class="regular-text" name="pop_ad_general_id" type="text" id="pop_ad_general_id" value="<?php echo esc_attr( sanitize_text_field( get_option('pop_ad_general_id') ) ); ?>">
        <p class="description">Enter PopAd ID.</p>
	<?php
}