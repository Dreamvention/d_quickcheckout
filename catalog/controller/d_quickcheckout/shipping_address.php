<?php 

class ControllerDQuickcheckoutShippingAddress extends Controller {
   
	public function index($config){
        $this->load->model('d_quickcheckout/address');
        $this->load->model('module/d_quickcheckout');
        $this->model_module_d_quickcheckout->logWrite('controller:: shipping_address/index');

        if(!$config['general']['compress']){
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/model/shipping_address.js');
            $this->document->addScript('catalog/view/javascript/d_quickcheckout/view/shipping_address.js');
        }
        
        $data['col'] = $config['account']['guest']['shipping_address']['column'];
        $data['row'] = $config['account']['guest']['shipping_address']['row'];

        $data['text_address_existing'] = $this->language->get('text_address_existing');
        $data['text_address_new'] = $this->language->get('text_address_new');

        $json['account'] = $this->session->data['account'];
        $json['shipping_address'] = $this->session->data['shipping_address'];
        $json['show_shipping_address'] = $this->model_d_quickcheckout_address->showShippingAddress(); 

        if($this->customer->isLogged()){

            $json['addresses'] = $this->model_d_quickcheckout_address->getAddresses();

            if (!empty($this->session->data['shipping_address']['address_id'])) {
                $json['shipping_address']['address_id'] = $this->session->data['shipping_address']['address_id'];
            } else {
                $json['shipping_address']['address_id'] = $this->customer->getAddressId();
            }
        }

        $data['json'] = json_encode($json);

        if(VERSION >= '2.2.0.0'){
            $template = 'd_quickcheckout/shipping_address';
        }elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_quickcheckout/shipping_address.tpl')) {
			$template = $this->config->get('config_template') . '/template/d_quickcheckout/shipping_address.tpl';
		} else {
			$template = 'default/template/d_quickcheckout/shipping_address.tpl';
		}

        return $this->load->view($template, $data);
	}

	public function update(){
        $this->load->model('module/d_quickcheckout');
        $this->load->model('d_quickcheckout/address');
        $this->load->model('d_quickcheckout/method');
        $this->load->model('d_quickcheckout/order');

        $json = array();

        //shipping address
        $json = $this->prepare($json);

        //tax
        $this->model_d_quickcheckout_address->updateTaxAddress();

        //shipping methods
        $json = $this->load->controller('d_quickcheckout/shipping_method/prepare', $json);

        //payment methods
        $json = $this->load->controller('d_quickcheckout/payment_method/prepare', $json);

        $json = $this->load->controller('d_quickcheckout/cart/prepare', $json);
        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );
        $json['totals'] = $this->session->data['totals'] = $this->model_d_quickcheckout_order->getTotals($total_data);
        $json['total'] = $this->model_d_quickcheckout_order->getCartTotal($total);
        
        $json['order_id'] = $this->session->data['order_id'] = $this->load->controller('d_quickcheckout/confirm/updateOrder');

        //payment
        $json = $this->load->controller('d_quickcheckout/payment/prepare', $json);

        //statistic
        $statistic = array(
            'update' => array(
                'shipping_address' => 1
            )
        );
        $this->model_module_d_quickcheckout->updateStatistic($statistic);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function prepare($json){
        $this->load->model('d_quickcheckout/address');
        $this->load->model('account/address');
        $this->load->model('d_quickcheckout/custom_field');

        //post
        if(isset($this->request->post['shipping_address'])){

            $this->request->post['shipping_address'] = $this->model_d_quickcheckout_address->compareAddress($this->request->post['shipping_address'], $this->session->data['shipping_address']);

            if($this->customer->isLogged()){
                if( !empty($this->request->post['shipping_address']['address_id'])
                    && $this->request->post['shipping_address']['address_id'] !== 'new' 
                    && $this->request->post['shipping_address']['address_id'] !== $this->session->data['shipping_address']['address_id'] 
                    ){

                    $this->request->post['shipping_address'] = $this->model_d_quickcheckout_address->getAddress($this->request->post['shipping_address']['address_id']);
                }
            }
            if(isset($this->request->post['shipping_address']['customer_group_id']) && isset($this->request->post['payment_address']['customer_group_id'])){
                $this->request->post['shipping_address']['custom_field'] =  ((!empty($this->request->post['shipping_address']['custom_field']['address'])) ? array('address' => $this->request->post['shipping_address']['custom_field']['address']) : $this->model_d_quickcheckout_custom_field->setCustomFieldsDefaultSessionData('address', $this->request->post['payment_address']['customer_group_id']));
            }
            
            if(isset($this->request->post['shipping_address']['custom_field']) && is_array($this->request->post['shipping_address']['custom_field'])){
                $this->request->post['shipping_address'] = array_merge($this->request->post['shipping_address'], $this->model_d_quickcheckout_custom_field->setCustomFieldValue($this->request->post['shipping_address']['custom_field']));
            }
            //merge post into session
            $this->session->data['shipping_address'] = array_merge($this->session->data['shipping_address'], $this->request->post['shipping_address']);

        }
        $this->model_d_quickcheckout_custom_field->updateCustomFieldsConfigData('shipping_address');


        //session
        $json['show_shipping_address'] = $this->model_d_quickcheckout_address->showShippingAddress();

        if($json['show_shipping_address']){

            if($this->customer->isLogged()){
                if($this->model_account_address->getAddresses()){
                    if(empty($this->session->data['shipping_address']['address_id'])){
                        $this->session->data['shipping_address'] = current($this->model_d_quickcheckout_address->getAddresses());
                    }
                }else{
                    $this->session->data['shipping_address']['country_id'] =  $this->config->get('config_country_id');
                    $this->session->data['payment_address']['zone_id'] = $this->config->get('config_zone_id');
                }


                if(empty($this->session->data['shipping_address']['address_id'])){
                    $this->session->data['shipping_address']['address_id'] = 'new';
                }
            }

        }else{
            $this->session->data['shipping_address'] = $this->session->data['payment_address'];
        }

        $json['shipping_address'] = $this->session->data['shipping_address'];

        return $json;
    }


}