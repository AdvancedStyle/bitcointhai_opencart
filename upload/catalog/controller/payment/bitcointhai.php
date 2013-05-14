<?php
require_once(DIR_SYSTEM . 'payment/bitcointhai.php');
class ControllerPaymentBitcoinThai extends Controller {
	private $payment_module_name  = 'bitcointhai';
	private $api, $order;
	
	function loadAPI($load_order = true){
		$this->load->model('checkout/order');
		if($load_order){
			$this->order = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		}
		
		$this->api = new bitcointhaiAPI;
		$this->data['enabled'] = true;
		if(!$this->api->init($this->config->get($this->payment_module_name.'_api_id'), $this->config->get($this->payment_module_name.'_api_key'))){
			$this->data['enabled'] = false;
		}elseif(!$this->api->validate($this->order['total'],$this->order['currency_code'])){
			$this->data['enabled'] = false;
		}
	}
	
	protected function index() {
		
		$this->loadAPI();
		
		$this->data['api'] = $this->api;
		
		$this->language->load('payment/'.$this->payment_module_name);
		
		if($this->data['enabled']){
			$this->api->order_id = (isset($this->session->data['bitcoin_order_id']) ? $this->session->data['bitcoin_order_id'] : 0);
			$data = array('amount' => $this->order['total'],
						  'currency' => $this->order['currency_code'],
						  'ipn' => HTTPS_SERVER . 'index.php?route=payment/bitcointhai/callback');
			if(!$paybox = $this->api->paybox($data)){
				$error = $this->language->get('text_bitcoin_unavailable');
			}
			$this->session->data['bitcoin_order_id'] = $this->api->order_id;
			$this->data['paybox'] = $paybox;
			$this->data['btc_url'] = 'bitcoin:'.$paybox->address.'?amount='.$paybox->btc_amount.'&label='.urlencode($GLOBALS['config']->get('config', 'store_name').' Order '.$this->_basket['cart_order_id']);
		}
		
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['text_bitcoin_unavailable'] = $this->language->get('text_bitcoin_unavailable');
		$this->data['text_bitcoin_title'] = $this->language->get('text_bitcoin_title');
		$this->data['text_pay_msg'] = $this->language->get('text_pay_msg');
		$this->data['text_afterpay'] = $this->language->get('text_afterpay');
		$this->data['text_countdown'] = $this->language->get('text_countdown');
		$this->data['text_countdown_exp'] = $this->language->get('text_countdown_exp');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['button_confirm'] 			= $this->language->get('button_bitcoin_confirm');
		
		$this->data['continue'] = HTTPS_SERVER . 'index.php?route=checkout/success';
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bitcointhai.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/bitcointhai.tpl';
		} else {
			$this->template = 'default/template/payment/bitcointhai.tpl';
		}	
		
		$this->data['url_success'] 	= HTTPS_SERVER . 'index.php?route=checkout/success';
		$this->data['url_cancel'] 	= HTTPS_SERVER . 'index.php?route=checkout/payment';
		
		$this->data['store_name']		=$this->order['store_name'];
		$this->data['order_id']			=$this->order['order_id'];
		$this->data['order_total']		=$this->order['total'];
		$this->data['order_currency']	=$this->order['currency_code'];
		
		$this->render();
	}
	
	
	public function send() {
		$this->load->model('checkout/order');
		
		$this->loadAPI();
		
		$this->language->load('payment/'.$this->payment_module_name);
		
		$result = $this->api->checkorder($_POST['order_id'], $this->session->data['order_id']);
		if(!$result || $result->error != ''){
			if(!$result){
			  $json['error'] = $this->language->get('text_bitcoin_unavailable');
			}else{
			  $json['error'] = $result->error;
			  if(isset($result->order_id)){
				  $this->session->data['bitcoin_order_id'] = $result->order_id;
			  }
			}
		}else{
			unset($this->session->data['bitcoin_order_id']);
			
			$order_status_id = $this->config->get($this->payment_module_name.'_order_status_id');
			$this->model_checkout_order->confirm($this->session->data['order_id'], $order_status_id, $this->language->get('text_comment_pending'),true);
			
			$json['success'] = $this->url->link('checkout/success');
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	
	public function callback() {
		$this->load->model('checkout/order');
		
		$this->loadAPI(false);
		
		$data = $_POST;
		
		if($ipn = $this->api->verifyIPN($data)){
			$order_id	= $data['reference_id'];
			$order_info = $this->model_checkout_order->getOrder($order_id);
			if (!empty($order_id) && !empty($data)) {
				$order_status_id = $this->config->get($this->payment_module_name.'_order_status_id_after');
				
				if (!$order_info['order_status_id']) {
					$this->model_checkout_order->confirm($order_id, $order_status_id,'Bitcoin IPN: '.$data['message'],true);
				} else {
					$this->model_checkout_order->update($order_id, $order_status_id,'Bitcoin IPN: '.$data['message'],true);
				}
				
				echo 'IPN Success';
				exit();
			}
		}
		header("HTTP/1.0 403 Forbidden");
		echo 'IPN Failed';
		exit();
	}
}
?>