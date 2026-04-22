<?php
/**
 * Plugin Name:       Appliance Fit Check
 * Plugin URI:        https://appliancefitcheck.org
 * Description:       A premium, AI-driven appliance fit calculator built with Tailwind CSS. Use the shortcode [appliance_fit_check] on any page.
 * Version:           1.2.0
 * Author:            Appliance Fit Check
 * Author URI:        https://appliancefitcheck.org
 * License:           GPL-2.0+
 * Text Domain:       appliance-fit-check
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main plugin class
 */
class Appliance_Fit_Check {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Load dependencies
        $this->load_dependencies();

        // Initialize hooks
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'init', array( 'AFC_Shortcode', 'init' ) );

        // AJAX hooks
        AFC_Ajax::init();
    }

    /**
     * Load necessary files
     */
    private function load_dependencies() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-afc-shortcode.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-afc-ajax.php';
    }

    /**
     * Enqueue CSS & JS
     */
    public function enqueue_assets() {
        // Enqueue Tailwind-generated CSS
        wp_enqueue_style(
            'appliance-fit-check',
            plugin_dir_url( __FILE__ ) . 'src/output.css',
            array(),
            '1.2.0'
        );

        // Enqueue main JS
        wp_enqueue_script(
            'appliance-fit-check',
            plugin_dir_url( __FILE__ ) . 'assets/appliance-fit-check.js',
            array( 'jquery' ),
            '1.2.0',
            true
        );

        // Pass AJAX URL and nonce to JS
        wp_localize_script(
            'appliance-fit-check',
            'AFC_SETTINGS',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'afc_nonce' ),
            )
        );
    }
}

// Init plugin
Appliance_Fit_Check::get_instance();
