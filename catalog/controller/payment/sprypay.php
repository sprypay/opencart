<?php
/*
	Plugin Name: SpryPay Payment Gateway for OpenCart
	Plugin URI: http://sprypay.ru/moduli-oplaty/opencart/
	Author: Sprypay.ru
	Author URI: http://sprypay.ru
*/
class ControllerPaymentSprypay extends Controller {
	protected function index() {
        $this->load->library('sprypaylib/SprypayRequestPaymentForm');
		$this->load->model('checkout/order');
        $language = $this->language->get('code');
        $order = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$url='https://sprypay.ru/sppi/?spShopId='.$this->config->get('sprypay_shop').'&spShopPaymentId='.$order['order_id'].'&spAmount='.$this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false).'&spCurrency='.strtolower($order['currency_code']);//.'&spPurpose=Order №'.$order['order_id'];

		//$this->model_checkout_order->confirm($order['order_id'], $this->config->get('sprypay_order_status_id'));
        $requestPaymentForm = new SprypayRequestPaymentForm();
        $requestPaymentForm->setShopId($this->config->get('sprypay_shop'));

        $requestPaymentForm->setShopPaymentId($order['order_id']);
		$requestPaymentForm->setAmount($this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false));
		$requestPaymentForm->setCurrency(strtolower($order['currency_code']));

		if ($language == 'ru') 
		{
			$requestPaymentForm->setPurpose('Заказ%20№'. $order['order_id']);
			$url.='&spPurpose=Заказ%20№'. $order['order_id'];
		}
		else 
		{
			$url.='&spPurpose=Order%20№'. $order['order_id'];
			$requestPaymentForm->setPurpose('Order%20№' . $order['order_id']);
		}

		$requestPaymentForm->setUserEmail($order['email']);
        $requestPaymentForm->setSubmitLabel($this->language->get('button_confirm'));
        $requestPaymentForm->disableAutoSubmit();

        // Sprypay support only Russian and English
		$language = $this->language->get('code');
        if ($language != 'ru')
            $language = 'en';
        $requestPaymentForm->setLanguage($language);
        $url.='&lang='.$language.'&src=1';

		if( $this->config->get('sprypay_confirm_status')=='before' )
		{
             $comment = '';
   			 if ($language == 'ru')
             {
				$comment = 'Ссылка для оплаты: <a href="'.$url.'">Оплатить с помощью Sprypay</a>';//$this->config->get('sprypay_confirm_comment')	;
			 }
   			 else{
       			$comment = 'Link for payment: <a href="'.$url.'">Pay with Sprypay</a>';//$this->config->get('sprypay_confirm_comment')	;
   			 }


			$comment = html_entity_decode($comment, ENT_QUOTES, 'UTF-8');

			$this->model_checkout_order->confirm( $this->session->data['order_id'], $this->config->get('sprypay_preorder_status_id'),
											    $comment, true); //$this->config->get('sprypay_confirm_notify') );
		}
		elseif($this->config->get('sprypay_confirm_status')=='after'  )
		{
			//$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('sprypay_order_status_id'));
		}

        $this->data['request_payment_form'] = $requestPaymentForm->renderForm();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/sprypay.tpl'))
            $this->template = $this->config->get('config_template') . '/template/payment/sprypay.tpl';
        else
            $this->template = 'default/template/payment/sprypay.tpl';

		$this->render();
	}

	public function callback() {
        if ($this->request->server['REQUEST_METHOD'] == 'GET')
            $notificationData = $this->request->get;
        elseif ($this->request->server['REQUEST_METHOD'] == 'POST')
            $notificationData = $this->request->post;
        else {
            $output = 'Unknown request method '.$this->request->server['REQUEST_METHOD'];
            $this->response->setOutput($output);
            return true;
        }


        $this->load->library('sprypaylib/SprypayNotificationValidator');
        $validator = new SprypayNotificationValidator();
        try {
            $validator->spParams = $notificationData;
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $notificationData = $validator->getNotificationData();
        $orderId = (int) $notificationData['shopPaymentId'];
		$this->load->model('checkout/order');
		$order = $orderId ? $this->model_checkout_order->getOrder($orderId) : null;

        if (!$order)
            die('Order with id: '.$orderId.' not found');

        if (!$validator->validateShopId(intval($this->config->get('sprypay_shop'))))
            die('Invalid shop id: '.$notificationData['shopId']);

        if (!$validator->validateCurrency(strtolower($order['currency_code'])))
            die('Invalid currency: '.$notificationData['currency']);

        if (!$validator->validateControlSum($this->config->get('sprypay_secret')))
            die('Invalid control sum: '.$notificationData['hashString']);

        $message = 'Sprypay payment '.$notificationData['paymentId'].' ('.$notificationData['balanceAmount'].' '.$notificationData['balanceCurrency'].') was enrolled to sprypay balance in '.$notificationData['enrollDateTime'];

  		if( $this->config->get('sprypay_confirm_status')=='before' )
		{
			 $this->model_checkout_order->update($orderId,$this->config->get('sprypay_order_status_id'), $message, false);
		}
		elseif($this->config->get('sprypay_confirm_status')=='after'  )
		{
			$this->model_checkout_order->confirm($orderId, $this->config->get('sprypay_order_status_id'),$message,true);
		}

        die($validator->confirmNotification());
    }
}
?>
