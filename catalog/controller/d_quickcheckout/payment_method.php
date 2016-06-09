<?php

    class ControllerDQuickcheckoutPaymentMethod extends Controller {

        public function index($config) {
            $this->load->model('module/d_quickcheckout');
            $this->model_module_d_quickcheckout->logWrite('controller:: payment_method/index');

            if (!$config['general']['compress']) {
                $this->document->addScript('catalog/view/javascript/d_quickcheckout/model/payment_method.js');
                $this->document->addScript('catalog/view/javascript/d_quickcheckout/view/payment_method.js');
            }

            $data['col'] = $config['account']['guest']['payment_method']['column'];
            $data['row'] = $config['account']['guest']['payment_method']['row'];

            $json['account'] = $this->session->data['account'];
            $json['payment_methods'] = $this->session->data['payment_methods'];
            if (empty($this->session->data['payment_methods'])) {
                $json['payment_error'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
            } else {
                $json['payment_error'] = '';
            }
            $json['payment_method'] = $this->session->data['payment_method'];
            $json['error_warning'] = '';

            $data['json'] = json_encode($json);

            if(VERSION >= '2.2.0.0'){
                $template = 'd_quickcheckout/payment_method';
            }elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/d_quickcheckout/payment_method.tpl')) {
                $template = $this->config->get('config_template') . '/template/d_quickcheckout/payment_method.tpl';
            } else {
                $template = 'default/template/d_quickcheckout/payment_method.tpl';
            }

            return $this->load->view($template, $data);
        }

        public function update() {
            $this->load->model('d_quickcheckout/order');
            $this->load->model('module/d_quickcheckout');

            $json = array();

            $json = $this->prepare($json);
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
                    'payment_method' => 1
                )
            );
            $this->model_module_d_quickcheckout->updateStatistic($statistic);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }

        public function prepare($json) {
            $this->load->model('module/d_quickcheckout');
            $this->load->model('d_quickcheckout/method');
            $this->load->model('d_quickcheckout/address');
             $this->load->model('d_quickcheckout/order');

            $this->session->data['payment_methods'] = $this->model_d_quickcheckout_method->getPaymentMethods($this->session->data['payment_address'], $this->session->data['total']);

            if (isset($this->request->post['payment_method'])) {
                $this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
            }

            if (isset($this->session->data['payment_method']['code'])) {
                if (!$this->model_module_d_quickcheckout->in_array_multi($this->session->data['payment_method']['code'], $this->session->data['payment_methods'])) {
                    $this->session->data['payment_method'] = $this->model_d_quickcheckout_method->getFirstPaymentMethod();
                }
            }

            if (empty($this->session->data['payment_method'])) {
                $this->session->data['payment_method'] = $this->model_d_quickcheckout_method->getDefaultPaymentMethod($this->session->data['d_quickcheckout']['account']['register']['payment_method']['default_option']);
            }

            $json['payment_methods'] = $this->session->data['payment_methods'];

            if (empty($this->session->data['payment_methods'])) {
                $this->load->language('checkout/checkout');
                $json['payment_error'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
            } else {
                $json['payment_error'] = '';
            }
            $json['payment_method'] = $this->session->data['payment_method'];
            $json['show_confirm'] = $this->model_d_quickcheckout_order->showConfirm();

            $this->model_module_d_quickcheckout->logWrite('Controller:: payment_method/prepare. paymet_methods = ' . json_encode($json['payment_methods']) . ' payment_method = ' . json_encode($json['payment_method']));

            return $json;
        }

    }
    