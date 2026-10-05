<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'btc_color' );
delete_option( 'btc_color_dark' );
delete_option( 'btc_ios_tint_top' );
delete_option( 'btc_ios_tint_bottom' );
