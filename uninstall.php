<?php

defined( 'ABSPATH' ) or die( 'Silence of gold' );

/* Uninstall Plugin */

// if not uninstalled plugin
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit(); // out!


/*esle:
	if uninstalled plugin, this options will be deleted
*/
delete_option('pop_ad_general_id');
delete_option('pop_ad_visual_media_c');