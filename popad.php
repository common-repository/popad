<?php
/*
Plugin Name: PopAd
Plugin URI: http://wp-plugins.in/PopAd_Plugin
Description: The Ultimate PopUp Ads Plugin! With a Lot of Features.. Discover it by yourself.
Version: 1.0.4
Author: Alobaidi
Author URI: http://wp-plugins.in
License: GPLv2 or later
*/

/*  Copyright 2016 Alobaidi (email: wp-plugins@outlook.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


defined( 'ABSPATH' ) or die( 'Silence of gold' );


function PopAd_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, 'popad.php' ) !== false ) {
        $new_links = array(
                            '<a href="http://wp-plugins.in/PopAd_Plugin" target="_blank">Explanation of Use</a>',
                            '<a href="http://wp-plugins.in/PopAd_Extensions" target="_blank">PopAd Extensions</a>',
                            '<a href="https://profiles.wordpress.org/alobaidi#content-plugins" target="_blank">More Plugins</a>'
                        );
        $links = array_merge( $links, $new_links );
    }
    
    return $links;
}
add_filter( 'plugin_row_meta', 'PopAd_plugin_row_meta', 10, 2 );


require_once dirname( __FILE__ ). '/admin/admin.php';

require_once dirname( __FILE__ ). '/admin/post-type-filters.php';

require_once dirname( __FILE__ ). '/admin/post-meta-box.php';

require_once dirname( __FILE__ ). '/admin/visual-media.php';

require_once dirname( __FILE__ ). '/admin/settings.php';

require_once dirname( __FILE__ ). '/media-embed.php';

include_once dirname( __FILE__ ). '/display.php';