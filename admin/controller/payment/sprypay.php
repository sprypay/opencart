<?php
class ControllerPaymentSprypay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/sprypay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('sprypay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_successful'] = $this->language->get('text_successful');
		$this->data['text_declined'] = $this->language->get('text_declined');
		$this->data['text_off'] = $this->language->get('text_off');

		$this->data['entry_shop'] = $this->language->get('entry_shop');
		$this->data['entry_secret'] = $this->language->get('entry_secret');
		$this->data['entry_callback'] = $this->language->get('entry_callback');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_total'] = $this->language->get('entry_total');


		$this->data['entry_script'] = $this->language->get('entry_script');
		$this->data['entry_script_before'] = $this->language->get('entry_script_before');
		$this->data['entry_script_after'] = $this->language->get('entry_script_after');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_preorder_status'] = $this->language->get('entry_preorder_status');

		if (isset($this->request->post['sprypay_confirm_status'])) {
			$this->data['sprypay_confirm_status'] = $this->request->post['sprypay_confirm_status'];
		} elseif( $this->config->has('sprypay_confirm_status') ) {
			$this->data['sprypay_confirm_status'] = $this->config->get('sprypay_confirm_status');
		} else {
			$this->data['sprypay_confirm_status'] = 'before';
		}

		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		if (isset($this->request->post['sprypay_order_comment'])) {
			$this->data['sprypay_order_comment'] = $this->request->post['sprypay_order_comment'];
		} elseif( $this->config->get('sprypay_order_comment') )
		{
			if( is_array($this->config->get('sprypay_order_comment')) )
			{
				$this->data['sprypay_order_comment'] = $this->config->get('sprypay_order_comment');
			}
			elseif( $this->config->get('sprypay_order_comment') )
			{
				$this->data['sprypay_order_comment'] = unserialize($this->config->get('sprypay_order_comment'));
			}
			else
			{
				$this->data['sprypay_order_comment'] = array();
			}

		} elseif( !$this->config->has('sprypay_order_comment') ) {

		//	foreach($this->data['languages'] as $language)
			//{
				//$Lang = new Language( $language['directory'] );
				//$Lang->load('payment/sprypay');

				$this->data['sprypay_order_comment'] =$this->language->get('text_order_comment_default');
			//}
		} else {
			$this->data['sprypay_order_comment'] = array();
		}


		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['shop'])) {
			$this->data['error_shop'] = $this->error['shop'];
		} else {
			$this->data['error_shop'] = '';
		}

 		if (isset($this->error['secret'])) {
			$this->data['error_secret'] = $this->error['secret'];
		} else {
			$this->data['error_secret'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/sprypay', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('payment/sprypay', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['sprypay_shop'])) {
			$this->data['sprypay_shop'] = $this->request->post['sprypay_shop'];
		} else {
			$this->data['sprypay_shop'] = $this->config->get('sprypay_shop');
		}

		if (isset($this->request->post['sprypay_secret'])) {
			$this->data['sprypay_secret'] = $this->request->post['sprypay_secret'];
		} else {
			$this->data['sprypay_secret'] = $this->config->get('sprypay_secret');
		}

		$this->data['callback'] = HTTP_CATALOG . 'index.php?route=payment/sprypay/callback';

		if (isset($this->request->post['sprypay_total'])) {
			$this->data['sprypay_total'] = $this->request->post['sprypay_total'];
		} else {
			$this->data['sprypay_total'] = $this->config->get('sprypay_total');
		}

		if (isset($this->request->post['sprypay_order_status_id'])) {
			$this->data['sprypay_order_status_id'] = $this->request->post['sprypay_order_status_id'];
		} else {
			$this->data['sprypay_order_status_id'] = $this->config->get('sprypay_order_status_id');
		}

		if (isset($this->request->post['sprypay_preorder_status_id'])) {
			$this->data['sprypay_preorder_status_id'] = $this->request->post['sprypay_preorder_status_id'];
		} else {
			$this->data['sprypay_preorder_status_id'] = $this->config->get('sprypay_preorder_status_id');
		}

		if (isset($this->request->post['sprypay_confirm_status'])) {
					$this->data['sprypay_confirm_status'] = $this->request->post['sprypay_confirm_status'];
				} elseif( $this->config->has('sprypay_confirm_status') ) {

					$this->data['sprypay_confirm_status'] = $this->config->get('sprypay_confirm_status');
				} else {
					$this->data['sprypay_confirm_status'] = 'before';
				}


		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['sprypay_geo_zone_id'])) {
			$this->data['sprypay_geo_zone_id'] = $this->request->post['sprypay_geo_zone_id'];
		} else {
			$this->data['sprypay_geo_zone_id'] = $this->config->get('sprypay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['sprypay_status'])) {
			$this->data['sprypay_status'] = $this->request->post['sprypay_status'];
		} else {
			$this->data['sprypay_status'] = $this->config->get('sprypay_status');
		}

		if (isset($this->request->post['sprypay_sort_order'])) {
			$this->data['sprypay_sort_order'] = $this->request->post['sprypay_sort_order'];
		} else {
			$this->data['sprypay_sort_order'] = $this->config->get('sprypay_sort_order');
		}

		$this->template = 'payment/sprypay.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/sprypay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['sprypay_shop']) {
			$this->error['shop'] = $this->language->get('error_shop');
		}

		if (!$this->request->post['sprypay_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>
