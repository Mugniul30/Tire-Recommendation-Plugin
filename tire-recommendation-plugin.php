<?php
/*
 * Plugin Name:       Tire Recommendation Plugin
 * Plugin URI:        https://wetechpro.com/
 * Description:       Fetch tire recommendations from Google Sheets and filter WooCommerce products based on Registration and Postcode.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mugniul Afif
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if (! defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The Main Plugin Class
 */
final class Tire_Recommendation_Plugin
{
    /**
     * Plugin Version
     */
    const VERSION = '1.0.0';

    /**
     * Class Constructor
     */
    private function __construct()
    {
        $this->define_constants();

        // Register the activation hook and initialize the plugin.
        register_activation_hook(__FILE__, [$this, 'activate']);
        add_action('plugins_loaded', [$this, 'init_plugin']);
    }

    /**
     * Initializes a singleton instance
     *
     * @return \Tire_Recommendation_Plugin
     */
    public static function init()
    {
        static $instance = false;
        if (! $instance) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Define plugin constants
     */
    private function define_constants()
    {
        define('TIRE_RECOMMENDATION_PLUGIN_VERSION', self::VERSION);
        define('TIRE_RECOMMENDATION_PLUGIN_FILE', __FILE__);
        define('TIRE_RECOMMENDATION_PLUGIN_PATH', __DIR__);
        define('TIRE_RECOMMENDATION_PLUGIN_URL', plugins_url('', TIRE_RECOMMENDATION_PLUGIN_FILE));
        define('TIRE_RECOMMENDATION_PLUGIN_ASSETS', TIRE_RECOMMENDATION_PLUGIN_URL . '/assets');
    }

    /**
     * Run activation tasks
     */
    public function activate()
    {
        // Check if this plugin has been installed previously
        $installed = get_option('tire_recommendation_plugin_version');

        if (! $installed) {
            update_option('tire_recommendation_plugin_installed', time());
        }

        // Store the plugin version in the options table
        update_option('tire_recommendation_plugin_version', TIRE_RECOMMENDATION_PLUGIN_VERSION);
    }

    /**
     * Initialize the plugin functionality
     */
    public function init_plugin()
    {
        // Create an instance of the TireRecommendation class from the TRS namespace
        new TRS\TireRecommendation();
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Tire_Recommendation_Plugin
 */
function tire_recommendation_plugin()
{
    return Tire_Recommendation_Plugin::init();
}

// Initialize the plugin
tire_recommendation_plugin();
