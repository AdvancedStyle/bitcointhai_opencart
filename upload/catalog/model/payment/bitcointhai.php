<?php 
require_once(DIR_SYSTEM . 'payment/bitcointhai.php');

class ModelPaymentBitcoinThai extends Model {
  	public function getMethod($address) {
		$this->load->language('payment/bitcointhai');
		
		if ($this->config->get('bitcointhai_status')) {
        	$status = TRUE;
		} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'         	=> 'bitcointhai',
        		'title'      	=> $this->language->get('text_title'),
				'sort_order' 	=> $this->config->get('bitcointhai_sort_order'),
      		);
    	}
   
    	return $method_data;
  	}
}
?>