<?php
include_once(WOO_PATH . '/woocommerce.php');
include_once(WVS_DIR . '/includes/WcTools.php');
include_once(WVS_DIR . '/includes/wvs-admin.php');
// include_once('../../woocommerce/classes/abstracts/abstract-wc-shipping-method.php');
if (!class_exists('WVS_WOOSHIPPING')) {
    final class WVS_WOOSHIPPING extends WC_Shipping_Method
    {
        public function __construct($instanceId = null)
        {
            $title = esc_html__("Shipping Fee");
            if (get_option('wvs_title')&&(get_option('wvs_title')!=='')){
                $title = get_option('wvs_title');
            }
            $this->plugin_id          = 'wvs_mt1';
            $this->id                 = 'wvs_mt';
            $this->instance_id        = absint($instanceId);    
            $this->method_title       = esc_html__('Weight Volume Shipping');
            $this->title              = $title;
            $this->supports = [
                'settings',
                'shipping-zones',
                'instance-settings',
                'global-instance',
            ];
            $this->init_settings();       
        }
        public function config($config = null)
        {
            $optionKey = $this->get_option_key();

            if (func_num_args()) {
                $updated = update_option($optionKey, $config);
                if ($updated) {
                    WcTools::purgeShippingCache();
                }
            } else {
                $config = get_option($optionKey, null);
                $config['enabled'] = WcTools::yesNo2Bool($config['enabled'] ?? true);
            }

            return $config;
        }
        public function get_option_key()
        {
            return join('_', array_filter([
                $this->plugin_id,
                $this->instance_id,
                'config',
            ]));
        }
        public function init_settings()
        {
            $this->settings = $this->config();
            $this->enabled = $this->settings['enabled'] = WcTools::bool2YesNo($this->settings['enabled']);
        }
        public function generate_settings_html($form_fields = [], $echo = true)
        {
            $result = parent::generate_settings_html(...func_get_args());
            echo $this->get_admin_options_html();
            return $result;
        }
        public function get_admin_options_html()
        {
            ob_start();
            include(WVS_DIR.'/template/tpl.php');
            return ob_get_clean();
        }
        function calculate_shipping($package = array())
        {
            global $woocommerce;
            $weight = $woocommerce->cart->cart_contents_weight;
            $volume = $this->get_cart_volume();
            $fee = $this->chagre($weight, $volume);
            $rate = array(
                'label' => $this->title,
                'cost' => $fee,
                'calc_tax' => 'per_item'
            );

            // Register the rate
            $this->add_rate($rate);
        }
        function  chagre($weight, $volume)
        {
            global $wpdb;
            global $wp;
            $table_name = $wpdb->prefix . WVS_TABLE;
            // $sqlw = "select * from {$table_name} where wfrom <= %d and wto >= %d";
            // $wprice = $wpdb->get_row($wpdb->prepare($sqlw,array($trongluong)),ARRAY_A);
            $sqlw = "select * from {$table_name} where wfrom <= " . $weight . " and wto > " . $weight;
            $wprice = $wpdb->get_row($sqlw, ARRAY_A);
            $sqlv = "select * from {$table_name} where vfrom <= " . $volume . " and vto > " . $volume;
            $vprice = $wpdb->get_row($sqlv, ARRAY_A);
            $price = $wprice["price"];
            if ($price < $vprice["price"]) {
                $price = $vprice["price"];
            }
            return $price;
        }
        protected function get_cart_volume()
        {
            $volume = $rate = 0;
            $dimension_unit = get_option('woocommerce_dimension_unit');
            // Loop through cart items
            foreach (WC()->cart->get_cart() as $cart_item) {
                // Get an instance of the WC_Product object and cart quantity
                $product = $cart_item['data'];
                $qty     = $cart_item['quantity'];

                // Get product dimensions  
                $length = $product->get_length();
                $width  = $product->get_width();
                $height = $product->get_height();

                // Calculations a item level
                $volume += $length * $width * $height * $qty;
            }
            return $volume;
        }
        public static function bool2YesNo($value)
        {
            return (bool)$value ? 'yes' : 'no';
        }               
    }
}
