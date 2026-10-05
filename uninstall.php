<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'btc_color' );
delete_option( 'btc_color_dark' );
delete_option( 'btc_legacy_tags' );
