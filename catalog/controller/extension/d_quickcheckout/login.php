<?php

    class ControllerExtensionDQuickcheckoutLogin extends Controller {

        public function index($config) {

            $this->load->model('extension/module/d_quickcheckout');
            $this->load->model('extension/d_opencart_patch/load');
            $this->model_extension_module_d_quickcheckout->logWrite('Controller:: login/index');

            if (!$config['general']['compress']) {
                $this->document->addScript('catalog/view/javascript/d_quickcheckout/model/login.js');
                $this->document->addScript('catalog/view/javascript/d_quickcheckout/view/login.js');
            }

            $data['col'] = $config['account']['guest']['login']['column'];
            $data['row'] = $config['account']['guest']['login']['row'];

            $data['text_returning_customer'] = $this->language->get('text_returning_customer');
            $data['text_new_customer'] = $this->language->get('text_new_customer');
            $data['entry_email'] = $this->language->get('entry_email');
            $data['entry_password'] = $this->language->get('entry_password');
            $data['text_guest'] = $this->language->get('text_guest');
            $data['button_login'] = $this->language->get('button_login');
            $data['text_forgotten'] = $this->language->get('text_forgotten');
            $data['step_option_guest_desciption'] = $this->language->get('step_option_guest_desciption');
            
            $this->load->model('extension/module/d_quickcheckout');
            
            if ($this->model_extension_module_d_quickcheckout->isInstalled('d_social_login') && $this->config->get('d_social_login_status') && $config['general']['social_login']) {
                $data['d_social_login'] = $this->load->controller('extension/module/d_social_login');
            } else {
                $data['d_social_login'] = '';
            }

            $data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
            $json['account'] = $this->session->data['account'];
            $json['error'] = '';

            if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
                $data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
            } else {
                $data['attention'] = '';
            }

            $data['json'] = json_encode($json);
            
            return $this->model_extension_d_opencart_patch_load->view('d_quickcheckout/login', $data);
        }

        /*
         *   Update functions
         */

        public function loginAccount() {

            $this->load->language('checkout/checkout');
            $this->load->model('extension/module/d_quickcheckout');
            $this->load->model('extension/d_quickcheckout/address');
            $this->load->model('extension/d_quickcheckout/method');
            $this->load->model('extension/d_quickcheckout/order');

            $json = array();

            if ($this->customer->isLogged()) {
                $json['account'] = $this->session->data['account'] = 'logged';
            }

            if ($this->model_extension_d_quickcheckout_order->isCartEmpty()) {
                $json['redirect'] = $this->model_extension_module_d_quickcheckout->ajax($this->url->link('checkout/cart'));
            }

            if (!$json) {
                $this->load->model('account/customer');
                 if(VERSION >= '2.0.2.0'){
                    // Check how many login attempts have been made.
                    $login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

                    if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
                        $json['login_error'] = $this->language->get('error_attempts');
                    }
                }
                // Check if customer has been approved.
                $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

                // if ($customer_info && !$customer_info['approved']) {
                //     $json['login_error'] = $this->language->get('error_approved');
                // }

                 
					if (!isset($json['login_error'])) {
						if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
							$json['login_error'] = $this->language->get('error_login');
                            if(VERSION >= '2.0.2.0'){
                                $this->model_account_customer->addLoginAttempt($this->request->post['email']);
                            }
						} else {
                            if(VERSION >= '2.0.2.0'){
                                $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
                            }
						}
					}
				
            }

            if (!$json) {
                //unset($this->session->data['guest']);

                $this->load->model('account/address');

                $json['account'] = $this->session->data['account'] = 'logged';

                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $this->customer->getId(),
                    'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
                );

                $this->model_account_activity->addActivity('login', $activity_data);
            }
            $this->load->model('extension/module/d_quickcheckout');
            //statistic
            $statistic = array(
                'click' => array(
                    'login' => 1
                )
            );
            $this->model_extension_module_d_quickcheckout->updateStatistic($statistic);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }

        public function updateAll() {
             
            $this->load->language('checkout/checkout');
            $this->load->model('extension/module/d_quickcheckout');
            $this->load->model('extension/d_quickcheckout/address');
            $this->load->model('extension/d_quickcheckout/method');
            $this->load->model('extension/d_quickcheckout/order');

            $json = array();
            
            $this->load->model('account/address');

            $json['account'] = $this->session->data['account'] = 'logged';
                
            //payment address
            $json = $this->load->controller('extension/d_quickcheckout/payment_address/prepare', $json);

            //shipping address
            $json = $this->load->controller('extension/d_quickcheckout/shipping_address/prepare', $json);

            $json['addresses'] = $this->model_extension_d_quickcheckout_address->getAddresses();

            //shipping address
            $json['show_shipping_address'] = $this->model_extension_d_quickcheckout_address->showShippingAddress();

            $this->model_extension_d_quickcheckout_address->updateTaxAddress();

            //shipping method
            $json = $this->load->controller('extension/d_quickcheckout/shipping_method/prepare', $json);

            //payment method - for xshipping (optimization needed)
            $json = $this->load->controller('extension/d_quickcheckout/payment_method/prepare', $json);

            //totals
            $json = $this->load->controller('extension/d_quickcheckout/cart/prepare', $json);
            
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

            //order
            $json['order_id'] = $this->session->data['order_id'] = $this->load->controller('extension/d_quickcheckout/confirm/updateOrder');

            //payment
            $json = $this->load->controller('extension/d_quickcheckout/payment/prepare', $json);

          
            //statistic
            $statistic = array(
                'click' => array(
                    'update' => 1
                )
            );
           $this->model_extension_module_d_quickcheckout->updateStatistic($statistic);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }

        public function updateAccount() {
            $this->load->model('extension/module/d_quickcheckout');

            $this->session->data['account'] = $this->request->post['account'];
            $json['account'] = $this->session->data['account'];

            //statistic
            $statistic = array(
                'click' => array(
                    'login' => 1
                ),
                'account' => $json['account']
            );
            $this->model_extension_module_d_quickcheckout->updateStatistic($statistic);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }

    }
    