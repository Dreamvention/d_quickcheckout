<?php
class ModelExtensionDQuickcheckoutMethod extends Model {

	public function getShippingMethods($shipping_address = array()){
		if(!$shipping_address){
			$this->load->model('extension/d_quickcheckout/address');
			$shipping_address = $this->model_extension_d_quickcheckout_address->paymentOrShippingAddress();
		}
		$method_data = array();

        if(VERSION < '3.0.0.0'){
            $this->load->model('extension/extension');
            $results = $this->model_extension_extension->getExtensions('shipping');
            $prefix = "";
        }else{
            $this->load->model('setting/extension');
            $results = $this->model_setting_extension->getExtensions('shipping');
            $prefix = "shipping_";
        }

		foreach ($results as $result) {

			if ($this->config->get($prefix . $result['code'] . '_status')) {

   				if(VERSION < '2.3.0.0'){
   					$this->load->model('shipping/' . $result['code']);

					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address);
   				}else{
   					$this->load->model('extension/shipping/' . $result['code']);

					$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($shipping_address);
   				}

				if ($quote) {
					$method_data[$result['code']] = array(
						'title'      => $quote['title'],
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
                        return $shipping_method;
                    }
                }
			}
		}
		return false;
	}

	public function getDefaultShippingMethod($default_option = false){

		if(!empty($default_option)){
			$shipping = explode('.', $default_option);
			if(isset($this->session->data['shipping_methods']) && isset($this->session->data['shipping_methods'][$shipping[0]])){
				if(isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])){
					return $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
				}
			}
		}

		return $this->getFirstShippingMethod();
	}

	public function getPaymentMethods($payment_address, $total){
		$method_data = array();

        if(VERSION < '3.0.0.0'){
            $this->load->model('extension/extension');
            $results = $this->model_extension_extension->getExtensions('payment');
            $prefix = "";
        }else{
            $this->load->model('setting/extension');
            $results = $this->model_setting_extension->getExtensions('payment');
            $prefix = "payment_";
        }

		$recurring = $this->cart->hasRecurringProducts();

		foreach ($results as $result) {
			if ($this->config->get($prefix . $result['code'] . '_status')) {

				if(VERSION < '2.3.0.0'){
   					$this->load->model('payment/' . $result['code']);

					$method = $this->{'model_payment_' . $result['code']}->getMethod($payment_address, $total);
   				}else{
   					$this->load->model('extension/payment/' . $result['code']);

					$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($payment_address, $total);
   				}

				if ($method) {
					if ($recurring) {
						if(VERSION < '2.3.0.0'){
							if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
								$method_data[$result['code']] = $method;
							}
						}else{
							if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
								$method_data[$result['code']] = $method;
							}
						}
					} else {
						$method_data[$result['code']] = $method;
					}

					if(file_exists(DIR_IMAGE.'catalog/d_quickcheckout/payment/'.$result['code'].'.png')){
						$method_data[$result['code']]['image'] = 'image/catalog/d_quickcheckout/payment/'.$result['code'].'.png';
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
                    return $payment_method;
                }
			}
		}
		return false;
	}

	public function getDefaultPaymentMethod($payment_method_code = ''){
		if(isset($this->session->data['payment_methods']) && is_array($this->session->data['payment_methods'])){
			
			if(array_key_exists($payment_method_code, $this->session->data['payment_methods'])
                && $this->session->data['payment_methods'][$payment_method_code]){
				return $this->session->data['payment_methods'][$payment_method_code];
			}
		}
		return $this->getFirstPaymentMethod();
	}

	public function getPayment(){
		$json = array();
		if(isset($this->session->data['payment_method']) && isset($this->session->data['payment_method']['code'])){
			//$json['payment_popup'] = $this->getPaymentPopup($this->session->data['payment_method']['code']);
			$json['payment_popup'] = false;
			if($json['payment_popup']){
				if(!empty($payment['cofirm_order'])){
					if(VERSION < '2.3.0.0'){
						$json['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);
					}else{
						$json['payment'] = $this->load->controller('extension/payment/' . $this->session->data['payment_method']['code']);
					}
				}else{
					$json['payment'] = '';
				}
			}else{
				if(VERSION < '2.3.0.0'){
					$json['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);
				}else{
					$json['payment'] = $this->load->controller('extension/payment/' . $this->session->data['payment_method']['code']);
				}
			}

			$json['payment_popup_title'] = $this->session->data['payment_method']['title'];

		}else{
            $json['payment_popup_title'] = '';
            $json['payment_popup'] = false;
			$json['payment'] = '';
		}

		return $json;
	}
}
