</form>
<label for="wvs_title">
    <h3><?php esc_html_e("Method title"); ?></h3>
</label>
<p><input type="text" name="wvs_title" id="wvs_title" value="<?php echo get_option('wvs_title'); ?>" placeholder="<?php esc_html_e("Method title") ?>" /></p>
<h3><?php echo esc_html__("Shipping Rules") ?></h3>
<table>
    <tr>
        <td>Weight range (From-To)</td>
        <td><input type="text" name="weight" placeholder="0-1(<?php echo get_option('woocommerce_weight_unit'); ?>)">(<?php echo get_option('woocommerce_weight_unit'); ?>)</td>
    </tr>
    <tr>
        <td>Volume range (From-To)</td>
        <td><input type="text" name="volume" placeholder="0-200 (<?php echo get_option('woocommerce_dimension_unit'); ?>3)">(<?php echo get_option('woocommerce_dimension_unit'); ?>3)</td>
    </tr>
    <tr>
        <td>Shipping Fee</td>
        <td><input type="text" name="fee" placeholder="<?php echo get_option('woocommerce_currency'); ?>">(<?php echo get_option('woocommerce_currency'); ?>)</td>
    </tr>
    <tr>
        <td></td>
        <td><button id="add" class="button button-primary button-large"><?php esc_html_e("Add rules") ?></button></td>
    </tr>
</table>
<div class="box">
    <h3><?php echo esc_html__("List Rules") ?></h3>
    <table class="list wp-list-table widefat fixed striped table-view-list">
        <thead class="title">
            <tr>
                <th>Weight range(kg)</th>
                <th>Volume range (cm3)</th>
                <th>Shipping Fee</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="title" id="display">
            <?php echo WVS_ADMIN::load_feetable(); ?>
        </tbody>
    </table>
    <button id="save" onclick="save('<?php echo admin_url('admin-ajax.php'); ?>')" class="button button-primary button-large right"><?php esc_html_e("Update") ?></button>
</div>
<div class="donate"><a href="https://www.paypal.com/paypalme/angocit" target="_blank">Buy me a coffee</a></div>
<script type="text/javascript" src="<?php echo WVS_URL . '/assets/js/main.js'; ?>"></script>
<style>
    .donate {
        text-align: center;
        display: block;
        margin-top: 15px;
    }
    a{cursor: pointer;}
    #save {
        margin-top: 10px;
    }
</style>
<form style="display: none">