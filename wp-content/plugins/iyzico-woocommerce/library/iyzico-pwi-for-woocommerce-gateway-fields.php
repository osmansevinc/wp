<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Iyzico_Pwi_For_WooCommerce_Fields {

	public static function iyzicoPwiAdminFields() {

		return $form_fields = array(
		    'enabled' => array(
		        'title' => __('Enable/Disable', 'woocommerce-iyzico-pwi'),
		        'label' => __('Enable Pay with iyzico', 'woocommerce-iyzico-pwi'),
		        'type' => 'checkbox',
		        'default' => 'yes'
		    ),
		);
	}
}
