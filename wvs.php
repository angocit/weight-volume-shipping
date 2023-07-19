<?php

/**
 * Plugin Name: Weight Volume Shipping
 * Plugin URI: https://fb.com/angocit
 * Description: Charge shipping with weight and volume.
 * Version: 1.0.0
 * Author: Angocit
 * Author URI: https://angocit.com
 *
 * @package Weight Volume Shipping
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
global $wpdb;
define('WVS_PATH', __FILE__);
define('WOO_PATH', WP_PLUGIN_DIR. '/woocommerce');
define('WVS_DIR', plugin_dir_path(__FILE__));
define('WVS_TABLE', 'wvs_setting');
define('WVS_URL', plugin_dir_url(__FILE__));
require_once WVS_DIR.'/includes/wvs-class.php';
require_once WVS_DIR . '/includes/wvs-admin.php';
require_once WVS_DIR . '/includes/wvs-function-class.php';
$at = new WVS_INSTALL();
$adm = new WVS_ADMIN();
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
  function wvs_shipping_method_init() {
        $wvs = new WVS_WOOSHIPPING();
  }
    add_action('woocommerce_shipping_init', 'wvs_shipping_method_init');
}
function add_wvs_shipping_method($methods)
{
    $methods['wvs_mt'] = 'WVS_WOOSHIPPING';
    return $methods;
}

add_filter('woocommerce_shipping_methods', 'add_wvs_shipping_method');