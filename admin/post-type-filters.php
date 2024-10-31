<?php

defined( 'ABSPATH' ) or die( 'Silence of gold' );


function PopAd_post_type_edit_messages( $bulk_messages, $bulk_counts ) {
    $bulk_messages['pop_ad'] = array(
        'deleted'   => $bulk_counts['deleted']." PopAd permanently deleted.",
        'trashed'   => $bulk_counts['trashed']." PopAd moved to the Trash.",
        'untrashed' => $bulk_counts['untrashed']." PopAd restored from the Trash.",
    );
    return $bulk_messages;
}
add_filter( 'bulk_post_updated_messages', 'PopAd_post_type_edit_messages', 10, 2 );


function PopAd_post_type_updated_messages($messages){
    if ( get_post_type() == 'pop_ad' ) {
        if( isset($_GET['pop_ad_reset_cookie_time_a']) ){
            $updated_message = 'PopAd cookie time reset.';
        }
        elseif( isset($_GET['pop_ad_visual_media_a']) ){
            if( get_option('pop_ad_visual_media_c') == 'hide' ){
                $visual_media_text = "hidden";
            }else{
                $visual_media_text = "showing";
            }
            $updated_message = "PopAd $visual_media_text visual media.";
        }
        else{
            $updated_message = 'PopAd updated.';
        }
        $messages['pop_ad'] = array( 0 => '', 1 => $updated_message, 6 => 'PopAd created.');
    }

    return $messages;
}
add_filter( 'post_updated_messages', 'PopAd_post_type_updated_messages' );


function PopAd_post_type_add_table_columns($columns){
	$columns = array(
                    'cb' => true,
                    'pop_ad_title_column' => 'Title',
                    'pop_ad_date_column' => 'Date',
                    'pop_ad_id_column' => 'ID',
                    'pop_ad_type_column' => 'Type',
                    'pop_ad_general_column' => 'General',
                    'pop_ad_cookie_time_column' => 'Cookie Time',
                    'pop_ad_views_count_column' => 'Impressions',
                    'pop_ad_exclude_display_column' => 'Exclude User',
                    'pop_ad_exclude_screen_column' => 'Exclude Screen',
                );
	return $columns;
}
add_filter( 'manage_edit-pop_ad_columns' , 'PopAd_post_type_add_table_columns' );


function PopAd_post_type_columns_content( $column, $post_id ) {
    if ( $column == 'pop_ad_title_column' ){
        if( !get_the_title($post_id) ){
            $get_the_title =  'Untitled';
        }else{
            $get_the_title = get_the_title($post_id);
        }

        if( isset($_GET['post_status']) and $_GET['post_status'] == 'trash' ){
            $restore_wp_nonce = wp_create_nonce('untrash-post_'.$post_id);
            $restore_link = admin_url("post.php?post=$post_id&action=untrash&_wpnonce=$restore_wp_nonce");
            $row_restore = '<span class="untrash"><a href="'.esc_url($restore_link).'">Restore</a></span>';

            $delete_wp_nonce = wp_create_nonce('delete-post_'.$post_id);
            $delete_link = admin_url("post.php?post=$post_id&action=delete&_wpnonce=$delete_wp_nonce");
            $row_delete = '<span class="delete"><a class="submitdelete" href="'.esc_url($delete_link).'">Delete Permanently</a></span>';

            echo '<strong>'.$get_the_title.'</strong>';
            echo '<div class="row-actions">'.$row_restore.' | '.$row_delete.'</div>';
            echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
        }else{
            $trash_wp_nonce = wp_create_nonce('trash-post_'.$post_id);
            $trash_link = admin_url("post.php?post=$post_id&action=trash&_wpnonce=$trash_wp_nonce");

            $row_trash = '<span class="trash"><a class="submitdelete" href="'.esc_url($trash_link).'">Trash</a></span>';
            $row_edit = '<span class="edit"><a href="'.esc_url(get_edit_post_link($post_id)).'">Edit</a></span>';

            $double_post_link = admin_url("post.php?post=$post_id&pop_ad_double=1");
            $row_double_post = '<span class="pop-ad-double-post"><a href="'.esc_url($double_post_link).'">Duplicate</a></span>';

            $row_views = '<span style="display:none;" class="pop-ad-row-views"> - '.get_post_meta($post_id, 'pop_ad_views_count', true).' Impressions</span>';

            echo '<strong><a class="row-title" href="'.esc_url(get_edit_post_link($post_id)).'">'.$get_the_title.'</a>'.$row_views.'</strong>';
            echo '<div class="row-actions">'.$row_edit.' | '.$row_double_post.' | '.$row_trash.'</div>';
            echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
        }
    }

    if ( $column == 'pop_ad_date_column' ){
        echo '<span>'.get_the_date('Y/m/d', $post_id).'</span>';
        echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
    }

    if ( $column == 'pop_ad_id_column' ){
        echo '<span>'.$post_id.'</span>';
        echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
    }

    if ( $column == 'pop_ad_type_column' ){
        if( get_post_meta($post_id, 'pop_ad_banner_link', true) ){
            $type = "Banner";
        }

        elseif( get_post_meta($post_id, 'pop_ad_media_link', true) ){
            if( get_post_meta($post_id, 'pop_ad_media_autoplay', true) ){
                $autoplay = "<br>Autoplay";
            }else{
                $autoplay =  null;
            }

            $media_link = get_post_meta($post_id, 'pop_ad_media_link', true);
            if( preg_match('/(youtube.com)|(youtu.be)/', $media_link) ){
                $media_source = "<br>YouTube".$autoplay;
            }

            elseif( preg_match('/(vimeo.com)/', $media_link) ){
                $media_source = "<br>Vimeo".$autoplay;
            }

            elseif( preg_match('/(soundcloud.com)/', $media_link) ){
                $media_source = "<br>SoundCloud".$autoplay;
            }

            else{
                $media_source = "<br>Unknown";
            }
            $type = 'Media'."".$media_source;
        }

        elseif( get_post_meta($post_id, 'pop_ad_google_adsense', true) ){
            $type = 'AdSense';
        }

        else{
            $type = 'None';
        }
        echo '<span>'.$type.'</span>';
        echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
    }

    if( $column == 'pop_ad_general_column' ){
        if( get_option('pop_ad_general_id') and get_option('pop_ad_general_id') == $post_id ){
            echo '<strong><span><a href="'.esc_url( admin_url('edit.php?post_type=pop_ad&page=pop-ad-general') ).'">Yes</a></span></strong>';
            echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
        }else{
            echo "<span>No</span>";
            echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
        }
    }

    if( $column == 'pop_ad_cookie_time_column' ){
        if( get_post_meta($post_id, 'pop_ad_cookie_time', true) ){
            $number = get_post_meta($post_id, 'pop_ad_cookie_time', true);
            if( $number > 1 ){
                $number_text = "Hours";
            }else{
                $number_text = "Hour";
            }
        }else{
            $number = 12;
            $number_text = "Hours";
        }

        if( $number % 24 == 0 ){
            $number_to_day = $number / 24;
            if( $number_to_day > 1 ){
                $day_text = "Days";
            }else{
                $day_text = "Day";
            }
            $number_output = $number_to_day." ".$day_text;
        }else{
            $number_output = $number." ".$number_text;
        }

        echo "<span>$number_output</span>";
        echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
    }

    if( $column == 'pop_ad_views_count_column' ){
        echo "<span>".get_post_meta($post_id, 'pop_ad_views_count', true)."</span>";
        echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
    }

    if( $column == 'pop_ad_exclude_display_column' ){
        $roles = array();
        $get_display_exclude = '';

        if( get_post_meta($post_id, 'pop_ad_exclude_administrator', true) ){
            $roles[] = 'Administrators';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_editor', true) ){
            $roles[] = 'Editors';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_author', true) ){
            $roles[] = 'Authors';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_contributor', true) ){
            $roles[] = 'Contributors';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_subscriber', true) ){
            $roles[] = 'Subscribers';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_visitor', true) ){
            $roles[] = 'Visitors';
        }

        foreach ($roles as $role) {
            if( end($roles) !== $role ){
                $comma = ", ";
            }else{
                $comma = null;
            }

            $get_display_exclude .= $role.$comma;
        }

        if( !empty($get_display_exclude) ){
            echo "<span>".$get_display_exclude."</span>";
            echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
        }else{
            echo "<span>None</span>";
            echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
        }
    }

    if( $column == 'pop_ad_exclude_screen_column' ){
        $screens = array();
        $get_screen_exclude = '';

        if( get_post_meta($post_id, 'pop_ad_exclude_home', true) ){
            $screens[] = 'Homepage';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_frontpage', true) ){
            $screens[] = 'Frontpage';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_single', true) ){
            $screens[] = 'All Posts';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_page', true) ){
            $screens[] = 'All Pages';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_category', true) ){
            $screens[] = 'Categories';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_tag', true) ){
            $screens[] = 'Tags';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_attachment', true) ){
            $screens[] = 'Attachment';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_search', true) ){
            $screens[] = 'Search';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_404', true) ){
            $screens[] = '404 Error Page';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_quote', true) ){
            $screens[] = 'Format Quote';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_aside', true) ){
            $screens[] = 'Format Aside';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_gallery', true) ){
            $screens[] = 'Format Gallery';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_link', true) ){
            $screens[] = 'Format Link';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_image', true) ){
            $screens[] = 'Format Image';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_status', true) ){
            $screens[] = 'Format Status';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_video', true) ){
            $screens[] = 'Format Video';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_audio', true) ){
            $screens[] = 'Format Audio';
        }

        if( get_post_meta($post_id, 'pop_ad_exclude_chat', true) ){
            $screens[] = 'Format Chat';
        }

        foreach ($screens as $screen) {
            if( end($screens) !== $screen ){
                $comma_s = ", ";
            }else{
                $comma_s = null;
            }

            $get_screen_exclude .= $screen.$comma_s;
        }

        if( !empty($get_screen_exclude) ){
            echo "<span>".$get_screen_exclude."</span>";
            echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
        }else{
            echo "<span>None</span>";
            echo '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
        }
    }
}
add_action( 'manage_pop_ad_posts_custom_column' , 'PopAd_post_type_columns_content', 10, 2 );


function PopAd_post_type_remove_edit_bulk($actions){
    unset( $actions['edit'] );
    return $actions;
}
add_filter('bulk_actions-edit-pop_ad', 'PopAd_post_type_remove_edit_bulk');