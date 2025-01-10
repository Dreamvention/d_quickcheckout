<?php
namespace Opencart\Catalog\Model\Extension\AjaxQuickCheckout\AjaxQuickCheckout;

class Method extends \Opencart\System\Engine\Model {

	public function getShippingMethods($shipping_address = array()){
		
		if(!$shipping_address){
			$this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/address');
			$shipping_address = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_address->paymentOrShippingAddress();
		}
		$method_data = array();

     
        $this->load->model('setting/extension');
        $results = $this->model_setting_extension->getExtensionsByType('shipping');
        $prefix = "shipping_";

		foreach ($results as $result) {

			if ($this->config->get($prefix . $result['code'] . '_status')) {
   				$this->load->model('extension/' . $result['extension'] . '/shipping/' . $result['code']);

				$quote = $this->{'model_extension_' . $result['extension'] . '_shipping_' . $result['code']}->getQuote($shipping_address);
				if ($quote) {
					$method_data[$result['code']] = array(
						'title'      => ($quote['title'] ?? $quote['name']),
						'name'      => ($quote['title'] ?? $quote['name']),
						'quote'      => $quote['quote'],
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}
		}

		$sort_order = array();

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);
		
		return $method_data;
	}

	public function getFirstShippingMethod(){
		if(isset($this->session->data['shipping_methods']) && is_array($this->session->data['shipping_methods'])){
			foreach ($this->session->data['shipping_methods'] as $group){
                if(isset($group['quote']) && is_array($group['quote'])){
                    foreach($group['quote'] as $shipping_method){
						if (VERSION > '4.0.1.1') {
							return $shipping_method;
						} else {
							return $shipping_method['code'];
						}
                    }
                }
			}
		}
		return null;
	}

	public function getDefaultShippingMethod($default_option = false){
		if(!empty($default_option)){
			$shipping = explode('.', $default_option);
			if(isset($this->session->data['shipping_methods']) && isset($this->session->data['shipping_methods'][$shipping[0]])){
				if(isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])){
					if (VERSION > '4.0.1.1') {
						return $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
					} else {
						return $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]['code'];
					}
					
				}
			}
		}

		return $this->getFirstShippingMethod();
	}

	public function getPaymentMethods($payment_address, $total){
		$method_data = array();

		$this->load->model('setting/extension');
		$results = $this->model_setting_extension->getExtensionsByType('payment');
		$prefix = "payment_";

		//$recurring = $this->cart->hasRecurringProducts();

		foreach ($results as $result) {
			if ($this->config->get($prefix . $result['code'] . '_status')) {
		
				$this->load->model('extension/' . $result['extension'] . '/payment/' . $result['code']);

				if (VERSION > '4.0.1.1') {
					$method = $this->{'model_extension_' . $result['extension'] . '_payment_' . $result['code']}->getMethods($payment_address);
				} else {
					$method = $this->{'model_extension_' . $result['extension'] . '_payment_' . $result['code']}->getMethod($payment_address, $total);
				}

				

				if ($method) {
					if (isset($method['name']) && !isset($method['title'])) {
						$method['title'] = $method['name'];
					}
					
					$method_data[$result['code']] = $method;

					if(is_file(DIR_IMAGE.'catalog/ajax_quick_checkout/payment/'.$result['code'].'.png')){
						$method_data[$result['code']]['image'] = 'image/catalog/ajax_quick_checkout/payment/'.$result['code'].'.png';
					}

				}
			}
		}

		$sort_order = array();

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);
		return $method_data;
	}

	public function getFirstPaymentMethod(){

		if(isset($this->session->data['payment_methods']) && is_array($this->session->data['payment_methods'])){
			foreach ($this->session->data['payment_methods'] as $payment_method){
                if($payment_method){
                    if (VERSION > '4.0.1.1') {
						return reset($payment_method['option']);
					} else {
						return $payment_method['code'];
					}
					
                }
			}
		}
		return null;
	}

	public function getDefaultPaymentMethod($payment_method_code = ''){
		if($payment_method_code && isset($this->session->data['payment_methods']) && is_array($this->session->data['payment_methods'])){
			
			if(VERSION > '4.0.1.1' ){
				$payment = explode('.', $payment_method_code);
				if (!empty($this->session->data['payment_methods'][$payment[0]]['option'][$payment[1]])) {
					return $this->session->data['payment_methods'][$payment[0]]['option'][$payment[1]];
				}
				
			} else if(array_key_exists($payment_method_code, $this->session->data['payment_methods'])
				&& $this->session->data['payment_methods'][$payment_method_code]){
					
				return $payment_method_code;
			}
		}
		return $this->getFirstPaymentMethod();
	}

	public function getPayment(){
		$json = array();
		if(isset($this->session->data['payment_method']) && $this->session->data['payment_method']){
			$json['payment_popup'] = $this->getPaymentPopup(is_array($this->session->data['payment_method']) ? $this->session->data['payment_method']['code'] : $this->session->data['payment_method']);
			// $json['payment_popup'] = false;
			if (VERSION > '4.0.1.1') {
				$code = oc_substr($this->session->data['payment_method']['code'], 0, strpos($this->session->data['payment_method']['code'], '.'));
			} else {
				$code = $this->session->data['payment_method'];
			}
			$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $code);
			
			if($json['payment_popup']){
				$json['payment'] = $this->load->controller('extension/' . $extension_info['extension'] . '/payment/' . $code);
			}else{
				$json['payment'] = $this->load->controller('extension/' . $extension_info['extension'] . '/payment/' . $code);
			}

			$json['payment_popup_title'] = (is_array($this->session->data['payment_method']) ? $this->session->data['payment_method']['name'] : $this->session->data['payment_methods'][$this->session->data['payment_method']]['title']);

		}else{
            $json['payment_popup_title'] = '';
            $json['payment_popup'] = false;
			$json['payment'] = '';
		}

		return $json;
	}

	public function getPaymentPopup($payment_code){

		$payment_popup = false;
		$this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');

		$state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

		if(isset($state['config']['guest']['payment']['payment_popups'])
		&&!empty($state['config']['guest']['payment']['payment_popups'])){
			if(isset($state['config']['guest']['payment']['payment_popups'][$payment_code])){
				$payment_popup = (bool)$state['config']['guest']['payment']['payment_popups'][$payment_code];
			}
		}
		
		return $payment_popup;
	}
}
