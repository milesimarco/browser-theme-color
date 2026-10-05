<?php
/*
Plugin Name:  Browser Theme Color
Plugin URI:   https://wordpress.org/plugins/browser-theme-color/
Description:  Simple and effective plugin to add the "theme-color" meta tag to your website
Version:      1.5
Author:       Marco Milesi
Author URI:   https://marcomilesi.com
Contributors: Milmor
Text Domain:  browser-theme-color
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Browser_Theme_Color' ) ) :

class Browser_Theme_Color {

    const OPTION_NAME = 'btc_color';
    const DEFAULT_COLOR = '#23282D';

    public function __construct() {
        add_action( 'wp_head', [ $this, 'output_theme_color_meta' ] );
        add_action( 'admin_menu', [ $this, 'register_settings_page' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
    }

    public function output_theme_color_meta() {
        $color = esc_attr( $this->get_color() );
        echo "<!-- browser-theme-color for WordPress -->\n";
        echo '<meta name="theme-color" content="' . $color . '">' . "\n";
        echo '<meta name="msapplication-navbutton-color" content="' . $color . '">' . "\n";
        echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
        echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">' . "\n";
    }

    public function register_settings_page() {
        add_options_page(
            __( 'Browser Theme Color', 'browser-theme-color' ),
            __( 'Browser Theme Color', 'browser-theme-color' ),
            'manage_options',
            'btc_settings',
            [ $this, 'settings_page' ]
        );
    }

    public function enqueue_admin_scripts( $hook ) {
        if ( $hook !== 'settings_page_btc_settings' ) {
            return;
        }
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'wp-color-picker' );
        // Inline JS will be printed in settings_page()
    }

    public function settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'browser-theme-color' ) );
        }

        // Handle form submission
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['submit'] ) && isset( $_POST['btc-input-class-n'] ) ) {
            if ( ! check_admin_referer( 'btc_nonce_action', 'btc_nonce_name' ) ) {
                wp_die( esc_html__( 'Nonce verification failed', 'browser-theme-color' ) );
            }
            $color = sanitize_hex_color( $_POST['btc-input-class-n'] );
            if ( $color ) {
                update_option( self::OPTION_NAME, $color );
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Theme color updated.', 'browser-theme-color' ) . '</p></div>';
            } else {
                echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Invalid color value.', 'browser-theme-color' ) . '</p></div>';
            }
        }

        $current_color = $this->get_color();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Browser Theme Color', 'browser-theme-color' ); ?></h1>
            <form method="post" action="">
                <?php wp_nonce_field( 'btc_nonce_action', 'btc_nonce_name' ); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label for="btc-input-class-n"><?php esc_html_e( 'Theme Color', 'browser-theme-color' ); ?></label>
                        </th>
                        <td>
                            <input 
                                type="text" 
                                id="btc-input-class-n" 
                                class="btc-input-class" 
                                name="btc-input-class-n" 
                                value="<?php echo esc_attr( $current_color ); ?>" 
                                data-default-color="<?php echo esc_attr( self::DEFAULT_COLOR ); ?>" 
                                maxlength="7"
                                pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                style="width:100px;"
                            />
                            <p class="description"><?php esc_html_e( 'Choose the color for the browser theme bar. Use a valid hex color (e.g., #23282D).', 'browser-theme-color' ); ?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button( __( 'Save Changes', 'browser-theme-color' ) ); ?>
            </form>
            <hr>
            <h2><?php esc_html_e( 'Preview', 'browser-theme-color' ); ?></h2>
            <div style="padding:1em; background:<?php echo esc_attr( $current_color ); ?>; color:#fff; border-radius:4px; width:200px; text-align:center;">
                <?php esc_html_e( 'This is how your theme color looks!', 'browser-theme-color' ); ?>
            </div>
        </div>
        <?php
        // Print inline JS after all scripts are loaded
        ?>
        <script>
        jQuery(function($){
            $('.btc-input-class').wpColorPicker();
        });
        </script>
        <?php
    }

    public function get_color() {
        $color = get_option( self::OPTION_NAME );
        return $color ? sanitize_hex_color( $color ) : self::DEFAULT_COLOR;
    }
}

new Browser_Theme_Color();

endif;
?>