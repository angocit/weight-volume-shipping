<?php
final class WVS_ADMIN
{
    function __construct()
    {
        add_action('wp_ajax_updateship', array($this,'updateship'));
        add_action('wp_ajax_nopriv_updateship', array($this, 'updateship'));
    }
    function updateship()
    {
        global $wpdb;
        global $wp;        
        $data = $_POST['data'];
        $title = $_POST["wvs_title"];
        update_option('wvs_title',$title);
        $data = json_decode(stripslashes($data));
        $table_name = $wpdb->prefix . WVS_TABLE;
        // var_dump($data);
        // foreach ($data as $key => $value) {
        //     echo $value->weight;
        // }
        $this->delete_feetable();
        $msg = esc_html__("Update success!");
        foreach ($data as $key => $value) {
            $str +=1;
            $weight = $value->weight;
            $volume = $value->volume;
            $fee = $value->fee;
            $pos = $value->pos;
            if (($this->checkwv($weight))&& ($this->checkwv($volume))&&is_numeric($fee)){
                $wpdb->insert(
                    $table_name,
                    array(
                        'wfrom' => explode("-", $weight)[0],
                        'wto' => explode("-", $weight)[1],
                        'vfrom' => explode("-", $volume)[0],
                        'vto' => explode("-", $volume)[1],
                        'price' => $fee,
                        'pos' => $pos
                    )
                );                
            } 
            else {
                $msg .= esc_html__("Add weight = " . $weight . " and volume = " . $volume . " unsuccess!");
            }           
        }
        die($msg);
    }
    function checkwv($str){
        $check = false;
        $str = explode("-",$str);
        if (count($str)==2){
            if ((is_numeric($str[0]))&&(is_numeric($str[1]))){
                $check = true;
            }
        }
        return $check;
    }
    function delete_feetable()
    {
        global $wpdb;
        global $wp;
        $table_name = $wpdb->prefix . WVS_TABLE;
        $sql = "delete from {$table_name}";
        $wpdb->get_results($sql);
    }
    public static function load_feetable()
    {
        global $wpdb;
        global $wp;
        $table_name = $wpdb->prefix . WVS_TABLE;
        $sql = "select * from {$table_name} order by pos asc";
        $banggia = $wpdb->get_results($sql);
        $kq = '';
        foreach ($banggia as $key => $value) {
            $kq .= '<tr class="data"><td><input name="stl" type="text" placeholder="Weight range" value="' . $value->wfrom . '-' . $value->wto . '"></td><td><input name="skt" type="text" placeholder="Volume range" value="' . $value->vfrom . '-' . $value->vto . '"></td><td><input name="sphi" type="text" placeholder="Fee" value="' . $value->price . '"></td><td><a onclick="del(jQuery(this))"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/></svg></a></td></tr>';
        }
        // print_r($sql);
        return $kq;
    }
}
