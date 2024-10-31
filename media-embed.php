<?php

defined( 'ABSPATH' ) or die( 'Silence of gold' );


function PopAd_get_soundcloud_track($url, $pop_ad_id){
	if( get_post_meta( $pop_ad_id, 'pop_ad_media_autoplay', true ) ){
		$autoplay = '1';
	}else{
		$autoplay = '0';
	}

	if( !preg_match("/(http:\/\/)|(https:\/\/)/", $url) ){
		$url = "https://".$url;
	}

	$transient_name = md5($url.$pop_ad_id.$autoplay."soundcloud-pop-ad-media");

	$get_transient = get_transient( $transient_name );

	if ( empty( $get_transient ) ){
		$get = wp_remote_get("http://soundcloud.com/oembed?url=$url&format=json");
		$retrieve = wp_remote_retrieve_body($get);
		$result = json_decode($retrieve, true);

		if( preg_match("/(errors)+/", $retrieve) ){
			return false;
		}

		$track = $result['html'];

		set_transient($transient_name, $track, 3600 * 24 * 30);
		return $track;
	}

	else{
		return $get_transient;
	}
}


function PopAd_media_embed($pop_ad_video_link, $pop_ad_id){
	if( get_post_meta( $pop_ad_id, 'pop_ad_media_autoplay', true ) ){
		$autoplay = '1';
	}else{
		$autoplay = '0';
	}

	if( preg_match("/(youtube.com|youtu.be)/", $pop_ad_video_link) ){
    	if( preg_match("/(youtu.be)/", $pop_ad_video_link) ){
    		$ex = "/";
    		$num = 3;
    		$pop_ad_video_link = str_replace("www.", '', $pop_ad_video_link);
    		if( !preg_match("/(http:\/\/)|(https:\/\/)/", $pop_ad_video_link) ){
    			$pop_ad_video_link = 'http://'.$pop_ad_video_link;
    		}
    	}else{
    		$ex = "v=";
    		$num = 1;
    	}
		$get_video_id = explode($ex, preg_replace("/(&)+(.*)/", null, $pop_ad_video_link) );
		$video_id = $get_video_id[$num];
		$media_link = esc_url("https://www.youtube.com/embed/$video_id/?rel=0&autoplay=".$autoplay);
		return $media_link;
		return false;
	}

	if( preg_match("/(vimeo.com)/", $pop_ad_video_link) ){
		$remove_param = preg_replace("/([?])+(.*)|(&)+(.*)/", '', $pop_ad_video_link);
		$video_id = preg_replace("/[^\/]+[^0-9]|(\/)/", "", rtrim($remove_param, "/"));
		$media_link = esc_url("http://player.vimeo.com/video/$video_id/?autoplay=".$autoplay);
		return $media_link;
		return false;
	}

	if( preg_match("/(soundcloud.com)/", $pop_ad_video_link) ){
		$track_html = PopAd_get_soundcloud_track($pop_ad_video_link, $pop_ad_id);
		preg_match_all('~src="(.*)"~', $track_html, $matches);
		$track_link = esc_url($matches[1][0]);
		if( $autoplay == '1' ){
			$soundcloud_autoplay = 'true';
		}else{
			$soundcloud_autoplay = 'false';
		}
		$media_link = $track_link."&auto_play=".$soundcloud_autoplay;
		return $media_link;
		return false;
	}
}