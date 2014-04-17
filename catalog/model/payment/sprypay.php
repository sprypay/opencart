<?php
/*
	Plugin Name: SpryPay Payment Gateway for OpenCart
	Plugin URI: http://sprypay.ru/moduli-oplaty/opencart/
	Author: Sprypay.ru
	Author URI: http://sprypay.ru
*/
class ModelPaymentSprypay extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('payment/sprypay');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('sprypay_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('sprypay_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('sprypay_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
      		$method_data = array(
        		'code'       => 'sprypay',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('sprypay_sort_order')
      		);
    	}

    	return $method_data;
  	}
}
?>
