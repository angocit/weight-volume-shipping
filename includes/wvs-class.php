<?php
class WVS_INSTALL
{
   public static function wvs_install()
    {
        global $wpdb;
        global $jal_db_version;

        $table_name = $wpdb->prefix . 'wvs_setting';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            wfrom float(50) NOT NULL,
            wto float(50) NOT NULL,
            vfrom float(50) NOT NULL,
            vto float(50) NOT NULL,
            price varchar(50) NOT NULL, 
            pos mediumint(9) NOT NULL  
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    public function __construct()
    {
        register_activation_hook(WVS_PATH, array($this, 'wvs_install'));        
    }
   
}
