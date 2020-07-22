<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Iyzico_Pwi_For_WooCommerce_Gateway extends WC_Payment_Gateway {

    public function __construct() {

        $this->id    = 'iyzico_pwi';
        $this->iyziV = '1.0.0';
        $this->method_title = __('Pay with iyzico', 'woocommerce-iyzico');
        $this->method_description = __('Best Payment Solution', 'woocommerce-iyzico');
        $this->has_fields = true;
        $this->order_button_text = __('Pay with iyzico', 'woocommerce-iyzico');
        $this->supports = array('products');

        $this->init_form_fields();
        $this->init_settings();
        
        $this->enabled     = $this->get_option('enabled');
        $this->title = false;

        
        if(get_locale() == 'tr_TR') {
            $this->icon         = plugins_url().IYZICO_PLUGIN_NAME.'/image/pwi_tr.png?v=4';
            $this->description  = __('iyzico ile paran güvende!
                                    -iyzico ile kartını kaydet ve tek adımda ödeme kolaylığı yaşa,
                                    -Tüm ödemelerini iyzico Korumalı Alışveriş güvencesiyle yap,
                                    -Ödemelerinle ilgili 7/24 canlı destek al.',"woocommerce-iyzico");
        } else {
            $this->icon         = plugins_url().IYZICO_PLUGIN_NAME.'/image/pwi.png?v=1';
        $this->description  = __('Your money safe with iyzico!
                                 -Store your iyzico card and enjoy one-click payment.
                                 -All your transactions under the iyzico Buyer Protection guarantee.
                                 -Get live support 24/7.',"woocommerce-iyzico");
        }

        $this->valid_css();
        

        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
            $this,
            'process_admin_options',
        ) );

        add_action('woocommerce_receipt_iyzico_pwi', array($this, 'iyzico_pwi_payment_redirect'));


    }


    public function init_form_fields() {
        
        $this->form_fields = Iyzico_Pwi_For_WooCommerce_Fields::IyzicoPwiAdminFields();

    }

    public function valid_css() {

        wp_enqueue_style('style', plugins_url().IYZICO_PLUGIN_NAME.'/media/css/pwi.css',true,'1.1','all');
    }


    public function process_payment($order_id) {
        
        $order = wc_get_order($order_id);

        return array(
          'result'   => 'success',
          'redirect' => $order->get_checkout_payment_url(true)
        );

    }


    public function iyzico_pwi_payment_redirect($order_id) {

        $iyzicoBuilder = new Iyzico_Checkout_For_WooCommerce_Gateway();
        $getPwiGenerate = $iyzicoBuilder->iyzico_payment_form($order_id,"pwi");


        header("Location: ".$getPwiGenerate.""); 

    }


}

