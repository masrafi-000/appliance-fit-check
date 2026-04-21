<?php
/**
 * Shortcode Handler for Appliance Fit Check
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AFC_Shortcode {

    /**
     * Initialize the shortcode
     */
    public static function init() {
        $instance = new self();
        add_shortcode( 'appliance_fit_check', array( $instance, 'render' ) );
    }

    /**
     * Render the shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public function render( $atts ) {
        $atts = shortcode_atts(
            array(
                'app_store_url' => 'https://apps.apple.com',
                'play_store_url' => 'https://play.google.com',
                'title'          => __( 'Appliance Fit Check', 'appliance-fit-check' ),
            ),
            $atts,
            'appliance_fit_check'
        );

        // Extract variables for template
        $app_store_url  = esc_url( $atts['app_store_url'] );
        $play_store_url = esc_url( $atts['play_store_url'] );
        $calculator_title = esc_html( $atts['title'] );

        ob_start();
        $template_path = plugin_dir_path( __FILE__ ) . '../templates/calculator-form.php';
        
        if ( file_exists( $template_path ) ) {
            include $template_path;
        } else {
            return '<p>' . __( 'Calculator template not found.', 'appliance-fit-check' ) . '</p>';
        }

        return ob_get_clean();
    }
}
