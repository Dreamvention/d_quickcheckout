<?php

    class ControllerExtensionDQuickcheckoutShippingMethod extends Controller {

        public function index($config) {

            $this->load->model('extension/d_quickcheckout/method');
            $this->load->model('extension/module/d_quickcheckout');
            $this->model_extension_module_d_quickcheckout->logWrite('controller:: shipping_method/index');

            if (!$config['general']['compress']) {
                $this->document->addScript('catalog/view/javascript/d_quickcheckout/model/shipping_method.js');
                $this->document->addScript('catalog/view/javascript/d_quickcheckout/view/shipping_method.js');
            }

            $data['col'] = $config['account']['guest']['shipping_method']['column'];
            $data['row'] = $config['account']['guest']['shipping_method']['row'];

            $json['account'] = $this->session->data['account'];
            $json['shipping_methods'] = $this->session->data['shipping_methods'];
            $json['shipping_method'] = $this->session->data['shipping_method'];
            $json['show_shipping_method'] = $this->model_extension_d_quickcheckout_method->shippingRequired();
            if (empty($this->session->data['shipping_methods'])) {
                $json['shipping_error'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
            } else {
                $json['shipping_error'] = '';
            }
            $data['json'] = json_encode($json);

            if(VERSION >= '2.2.0.0'){
                $template = 'd_quickcheckout/shipping_method';
			}elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_quickcheckout/shipping_method.tpl')) {
                $template = $this->config->get('config_template') . '/template/d_quickcheckout/shipping_method.tpl';
            } else {
                $template = 'default/template/d_quickcheckout/shipping_method.tpl';
            }

            $this->load->model('extension/d_opencart_patch/load');
            return $this->model_extension_d_opencart_patch_load->view($template, $data);
        }

        public function update() {
            $this->load->model('extension/d_quickcheckout/order');
            $this->load->model('extension/module/d_quickcheckout');

            $json = array();

            $json = $this->prepare($json);

            //payment method - for xshipping (optimization needed)
            $json = $this->load->controller('extension/d_quickcheckout/payment_method/prepare', $json);

            $totals = array();
            $taxes = $this->cart->getTaxes();
            $total = 0;

            $total_data = array(
                'totals' => &$totals,
                'taxes'  => &$taxes,
                'total'  => &$total
            );

            $json['totals'] = $this->session->data['totals'] = $this->model_extension_d_quickcheckout_order->getTotals($total_data);
            $json['total'] = $this->model_extension_d_quickcheckout_order->getCartTotal($total);
            $json['order_id'] = $this->session->data['order_id'] = $this->load->controller('extension/d_quickcheckout/confirm/updateOrder');

            //payment
            $json = $this->load->controller('extension/d_quickcheckout/payment/prepare', $json);

            //statistic
            $statistic = array(
                'update' => array(
                    'shipping_method' => 1
                )
            );



            $this->model_extension_module_d_quickcheckout->updateStatistic($statistic);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }

        public function prepare($json) {
            $this->load->model('extension/module/d_quickcheckout');
            $this->load->model('extension/d_quickcheckout/method');
            $this->load->model('extension/d_quickcheckout/address');
             $this->load->model('extension/d_quickcheckout/order');

            $this->session->data['shipping_methods'] = $this->model_extension_d_quickcheckout_method->getShippingMethods($this->model_extension_d_quickcheckout_address->paymentOrShippingAddress());

            if (isset($this->request->post['shipping_method'])) {
                $shipping = explode('.', $this->request->post['shipping_method']);
                $this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
            }

            if (isset($this->session->data['shipping_method']['code'])) {
          
                if (!$this->model_extension_module_d_quickcheckout->in_array_multi($this->session->data['shipping_method']['code'], $this->session->data['shipping_methods'])) {
                    $this->session->data['shipping_method'] = $this->model_extension_d_quickcheckout_method->getFirstShippingMethod();
             
                } else {
                    $shipping = explode('.', $this->session->data['shipping_method']['code']);
                    $this->session->data['shipping_method'] = array_merge($this->session->data['shipping_method'], $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]);
                  
                }
            }

            if (empty($this->session->data['shipping_method'])) {
				
                $this->session->data['shipping_method'] = $this->model_extension_d_quickcheckout_method->getDefaultShippingMethod($this->session->data['d_quickcheckout']['account']['register']['shipping_method']['default_option']);
            }


            $json['show_shipping_method'] = $this->model_extension_d_quickcheckout_method->shippingRequired();
            $json['shipping_methods'] = $this->session->data['shipping_methods'];
            if (empty($this->session->data['shipping_methods'])) {
                 $this->load->language('checkout/checkout');
                $json['shipping_error'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
            } else {
                $json['shipping_error'] = '';
            }
            $json['show_confirm'] = $this->model_extension_d_quickcheckout_order->showConfirm();

            $json['shipping_method'] = $this->session->data['shipping_method'];
            $this->model_extension_module_d_quickcheckout->logWrite('Controller:: shipping_method/prepare. shipping_methods = ' . json_encode($json['shipping_methods']) . ' shipping_method = ' . json_encode($json['shipping_method']));

            return $json;
        }

    }
    