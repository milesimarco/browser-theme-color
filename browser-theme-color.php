<?php
/*
Plugin Name:       Browser Theme Color
Plugin URI:        https://wordpress.org/plugins/browser-theme-color/
Description:       Simple and effective plugin to add the "theme-color" meta tag to your website, with optional dark mode color.
Version:           1.6
Requires at least: 5.0
Requires PHP:      7.0
Author:            Marco Milesi
Author URI:        https://marcomilesi.com
License:           GPLv2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:       browser-theme-color
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Browser_Theme_Color' ) ) :

class Browser_Theme_Color {

    const OPTION_NAME        = 'btc_color';
    const OPTION_DARK        = 'btc_color_dark';
    const OPTION_LEGACY_TAGS = 'btc_legacy_tags';
    const DEFAULT_COLOR      = '#23282D';
    const SETTINGS_GROUP     = 'btc_settings';
    const PAGE_SLUG          = 'btc_settings';

    public function __construct() {
        add_action( 'wp_head', [ $this, 'output_theme_color_meta' ], 1 );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'admin_menu', [ $this, 'register_settings_page' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'add_settings_link' ] );
    }

    public function output_theme_color_meta() {
        /**
         * Filters the theme color printed in the "theme-color" meta tag.
         *
         * @param string $color Hex color.
         */
        $color = sanitize_hex_color( apply_filters( 'browser_theme_color', $this->get_color() ) );

        /**
         * Filters the dark mode theme color. Return an empty string to disable it.
         *
         * @param string $color Hex color or empty string.
         */
        $dark = sanitize_hex_color( apply_filters( 'browser_theme_color_dark', $this->get_dark_color() ) );

        if ( ! $color ) {
            return;
        }

        echo "<!-- browser-theme-color for WordPress -->\n";
        if ( $dark ) {
            echo '<meta name="theme-color" media="(prefers-color-scheme: light)" content="' . esc_attr( $color ) . '">' . "\n";
            echo '<meta name="theme-color" media="(prefers-color-scheme: dark)" content="' . esc_attr( $dark ) . '">' . "\n";
        } else {
            echo '<meta name="theme-color" content="' . esc_attr( $color ) . '">' . "\n";
        }

        if ( $this->legacy_tags_enabled() ) {
            echo '<meta name="msapplication-navbutton-color" content="' . esc_attr( $color ) . '">' . "\n";
            echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
            echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">' . "\n";
        }
    }

    public function register_settings() {
        register_setting( self::SETTINGS_GROUP, self::OPTION_NAME, [
            'type'              => 'string',
            'sanitize_callback' => [ $this, 'sanitize_color' ],
            'default'           => self::DEFAULT_COLOR,
        ] );
        register_setting( self::SETTINGS_GROUP, self::OPTION_DARK, [
            'type'              => 'string',
            'sanitize_callback' => [ $this, 'sanitize_dark_color' ],
            'default'           => '',
        ] );
        register_setting( self::SETTINGS_GROUP, self::OPTION_LEGACY_TAGS, [
            'type'              => 'string',
            'sanitize_callback' => [ $this, 'sanitize_checkbox' ],
            'default'           => '1',
        ] );
    }

    public function sanitize_color( $value ) {
        $color = sanitize_hex_color( $value );
        if ( ! $color ) {
            add_settings_error( self::OPTION_NAME, 'btc_invalid_color', __( 'Invalid theme color: the previous value has been kept.', 'browser-theme-color' ) );
            return $this->get_color();
        }
        return $color;
    }

    public function sanitize_dark_color( $value ) {
        if ( '' === trim( (string) $value ) ) {
            return '';
        }
        $color = sanitize_hex_color( $value );
        if ( ! $color ) {
            add_settings_error( self::OPTION_DARK, 'btc_invalid_dark_color', __( 'Invalid dark mode color: the previous value has been kept.', 'browser-theme-color' ) );
            return $this->get_dark_color();
        }
        return $color;
    }

    public function sanitize_checkbox( $value ) {
        return $value ? '1' : '0';
    }

    public function register_settings_page() {
        add_options_page(
            __( 'Browser Theme Color', 'browser-theme-color' ),
            __( 'Browser Theme Color', 'browser-theme-color' ),
            'manage_options',
            self::PAGE_SLUG,
            [ $this, 'settings_page' ]
        );
    }

    public function add_settings_link( $links ) {
        $url = admin_url( 'options-general.php?page=' . self::PAGE_SLUG );
        array_unshift( $links, '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'browser-theme-color' ) . '</a>' );
        return $links;
    }

    public function enqueue_admin_scripts( $hook ) {
        if ( $hook !== 'settings_page_' . self::PAGE_SLUG ) {
            return;
        }
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_add_inline_script( 'wp-color-picker', $this->get_admin_js() );
    }

    private function get_admin_js() {
        return <<<'JS'
jQuery(function($){
    function textColor(hex){
        hex = (hex || '').replace('#', '');
        if (hex.length === 3) { hex = hex.replace(/(.)/g, '$1$1'); }
        if (hex.length !== 6) { return '#fff'; }
        var r = parseInt(hex.substr(0, 2), 16), g = parseInt(hex.substr(2, 2), 16), b = parseInt(hex.substr(4, 2), 16);
        return (r * 299 + g * 587 + b * 114) / 1000 > 150 ? '#000' : '#fff';
    }
    function updatePreview(input, color){
        var target = $('#' + $(input).data('preview'));
        if (!color) { target.hide(); return; }
        target.show().css({ background: color, color: textColor(color) });
    }
    $('.btc-color-field').each(function(){
        var input = this;
        $(input).wpColorPicker({
            change: function(event, ui){ updatePreview(input, ui.color.toString()); },
            clear: function(){ updatePreview(input, ''); }
        });
    });
});
JS;
    }

    private function contrast_text_color( $hex ) {
        $hex = ltrim( (string) $hex, '#' );
        if ( strlen( $hex ) === 3 ) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        if ( strlen( $hex ) !== 6 ) {
            return '#fff';
        }
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
        return ( $r * 299 + $g * 587 + $b * 114 ) / 1000 > 150 ? '#000' : '#fff';
    }

    private function preview_box( $id, $color, $label ) {
        $style = $color
            ? 'background:' . $color . ';color:' . $this->contrast_text_color( $color ) . ';'
            : 'display:none;';
        printf(
            '<div id="%1$s" style="%2$s padding:1em; border-radius:4px; width:220px; text-align:center; margin-top:8px;">%3$s</div>',
            esc_attr( $id ),
            esc_attr( $style ),
            esc_html( $label )
        );
    }

    public function settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'browser-theme-color' ) );
        }

        $color  = $this->get_color();
        $dark   = $this->get_dark_color();
        $legacy = $this->legacy_tags_enabled();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Browser Theme Color', 'browser-theme-color' ); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields( self::SETTINGS_GROUP ); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label for="btc-color"><?php esc_html_e( 'Theme Color', 'browser-theme-color' ); ?></label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="btc-color"
                                class="btc-color-field"
                                name="<?php echo esc_attr( self::OPTION_NAME ); ?>"
                                value="<?php echo esc_attr( $color ); ?>"
                                data-default-color="<?php echo esc_attr( self::DEFAULT_COLOR ); ?>"
                                data-preview="btc-preview-light"
                            />
                            <p class="description"><?php esc_html_e( 'Color of the browser toolbar / status bar.', 'browser-theme-color' ); ?></p>
                            <?php $this->preview_box( 'btc-preview-light', $color, __( 'Light mode preview', 'browser-theme-color' ) ); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="btc-color-dark"><?php esc_html_e( 'Dark Mode Color', 'browser-theme-color' ); ?></label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="btc-color-dark"
                                class="btc-color-field"
                                name="<?php echo esc_attr( self::OPTION_DARK ); ?>"
                                value="<?php echo esc_attr( $dark ); ?>"
                                data-preview="btc-preview-dark"
                            />
                            <p class="description"><?php esc_html_e( 'Optional. Used when the visitor\'s device is in dark mode. Leave empty to use the theme color everywhere.', 'browser-theme-color' ); ?></p>
                            <?php $this->preview_box( 'btc-preview-dark', $dark, __( 'Dark mode preview', 'browser-theme-color' ) ); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Legacy Tags', 'browser-theme-color' ); ?></th>
                        <td>
                            <label for="btc-legacy-tags">
                                <input type="hidden" name="<?php echo esc_attr( self::OPTION_LEGACY_TAGS ); ?>" value="0" />
                                <input type="checkbox" id="btc-legacy-tags" name="<?php echo esc_attr( self::OPTION_LEGACY_TAGS ); ?>" value="1" <?php checked( $legacy ); ?> />
                                <?php esc_html_e( 'Also output Windows Phone and iOS web app meta tags', 'browser-theme-color' ); ?>
                            </label>
                            <p class="description"><?php esc_html_e( 'Adds msapplication-navbutton-color, apple-mobile-web-app-capable and apple-mobile-web-app-status-bar-style. Disable it if your site should not open as a full-screen web app when added to the iOS home screen.', 'browser-theme-color' ); ?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function get_color() {
        $color = sanitize_hex_color( get_option( self::OPTION_NAME ) );
        return $color ? $color : self::DEFAULT_COLOR;
    }

    public function get_dark_color() {
        $color = sanitize_hex_color( get_option( self::OPTION_DARK, '' ) );
        return $color ? $color : '';
    }

    public function legacy_tags_enabled() {
        return '0' !== (string) get_option( self::OPTION_LEGACY_TAGS, '1' );
    }
}

new Browser_Theme_Color();

endif;
