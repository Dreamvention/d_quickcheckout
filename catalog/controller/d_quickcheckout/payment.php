<?php 

class ControllerDQuickcheckoutPayment extends Controller {
	
	public function index($config){

		$this->load->model('module/d_quickcheckout');
		$this->load->model('d_quickcheckout/method');
        $this->model_module_d_quickcheckout->logWrite('controller:: payment/index');
		
		if(!$config['general']['compress']){
			$this->document->addScript('catalog/view/javascript/d_quickcheckout/model/payment.js');
			$this->document->addScript('catalog/view/javascript/d_quickcheckout/view/payment.js');
		}

		$data['col'] = $config['account']['guest']['payment']['column'];
        $data['row'] = $config['account']['guest']['payment']['row'];

        $json = array();
        $json = $this->prepare($json);
        $json['account'] = $this->session->data['account'];
        $json['trigger'] = $config['trigger'];
		
		$data['json'] = json_encode($json);

		if(VERSION >= '2.2.0.0'){
            $template = 'd_quickcheckout/payment';
        }elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_quickcheckout/payment.tpl')) {
			$template = $this->config->get('config_template') . '/template/d_quickcheckout/payment.tpl';
		} else {
			$template = 'default/template/d_quickcheckout/payment.tpl';
		}

		return $this->load->view($template, $data);
	}

	public function prepare($json){
		$this->load->model('d_quickcheckout/method');
		if(isset($this->session->data['payment_method']) && isset($this->session->data['payment_method']['code'])){
			$json['payment_popup'] = $this->model_d_quickcheckout_method->getPaymentPopup($this->session->data['payment_method']['code']);
			
			if($json['payment_popup']){
				if(!empty($json['cofirm_order'])){
					$json['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);
				}else{
					$json['payment'] = '';
				}
			}else{
				$json['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);	
			}
			
			$json['payment_popup_title'] = $this->session->data['payment_method']['title'];

		}else{
			$json['payment'] = '';
		}


		return $json;
	}
}