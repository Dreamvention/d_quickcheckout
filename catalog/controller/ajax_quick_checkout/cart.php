<?php

namespace Opencart\Catalog\Controller\Extension\AjaxQuickCheckout\AjaxQuickCheckout;

class Cart extends \Opencart\System\Engine\Controller {
    private $route = 'extension/ajax_quick_checkout/ajax_quick_checkout/cart';

    public $action = array(
        'cart/update',
        'account/update/after',
        'total/update',
        'shipping_method/update/after',
        'payment_method/update/after',
        'payment_address/update/after',
        'shipping_address/update/after'
    );

    private $pro = '';

    public function __construct($registry){
        parent::__construct($registry);
        
        if(is_file(DIR_EXTENSION.'ajax_quick_checkout_pro/install.json')) $this->pro .= '_pro';

        $this->config->addPath(DIR_EXTENSION . 'ajax_quick_checkout' . $this->pro . '/system/config/');

        $this->load->model('extension/ajax_quick_checkout/module/ajax_quick_checkout');
        $this->model_extension_ajax_quick_checkout_module_ajax_quick_checkout->loadDependencies();
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/order');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/store');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/method');
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/address');

    }

    /**
     * Initialization
     */
    public function index(){
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        $state['config'] = $this->getConfig();
        $state['language']['cart'] = $this->getLanguages();
        $state['action']['cart'] = $this->action;
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState($state);

        $cart = $this->getDefault();
        //$this->model_extension_ajax_quick_checkout_store->updateState(array( 'session' , 'cart'), $cart);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'cart', 'products'), $cart['products']);
        if(isset($cart['vouchers'])){
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session', 'vouchers'), $cart['vouchers']);
        }


        $totals = $this->getTotals();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'totals'), $totals);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_order->updateOrder();
    }

    //fix cart quantity lateness on 4.0.2.x
    public function get_cart_data() {
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->loadState();
        $cart = $this->getCart();
        if(!$cart['products']){
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session', 'status'), false);
        }else{
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'cart', 'products'), $cart['products']);

            $totals = $this->getTotals();
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'totals'), $totals);
        }

        $data = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getStateUpdated();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    /**
     * update via ajax
     */
    public function update(){
        $rawData = file_get_contents('php://input');
        $post = json_decode($rawData, true);
        if(!$post){
            $post = $this->request->post;
        }
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->loadState();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_order->updateOrder();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('cart/update/before', $post);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('cart/update', $post);
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('total/update', $post);

        $data = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getStateUpdated();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }


    /**
     * Receiver
     * Receiver listens to dispatch of events and accepts data array with action and state
     */
    public function receiver($data){
        $update = false;

        //updating payment_method value
        if($data['action'] == 'cart/update'){

            if(isset($data['data']['cart'])){
                $cart = $this->updateCart($data['data']['cart']);
                if(!$cart['products']){
                    $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session', 'status'), false);
                }else{
                    $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'cart', 'products'), $cart['products']);

                    $totals = $this->getTotals();
                    $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'totals'), $totals);
                }

                $this->validate();

                $update = true;
            }

            if(isset($data['data']['coupon'])){
                $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
                $coupon = $data['data']['coupon'];

                $this->load->model('marketing/coupon');
                $this->load->language('extension/opencart/total/coupon');
                $coupon_info = $this->model_marketing_coupon->getCoupon($coupon);


                if (empty($data['data']['coupon'])) {
                    $state['notifications']['cart']['error_coupon'] = $this->language->get('error_coupon');

                    $state['session']['coupon'] = "";
                } elseif ($coupon_info) {
                    $state['session']['coupon'] = $coupon;
                    $state['notifications']['cart']['success_coupon'] = $this->language->get('text_success');
                } else {
                    $state['notifications']['cart']['error_coupon'] = $this->language->get('error_coupon');
                }

                if(!isset($state['session']['coupon'])){
                    $state['session']['coupon'] = $coupon;
                }

                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'coupon'), $state['session']['coupon']);
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'notifications' , 'cart'), $state['notifications']['cart']);

                $totals = $this->getTotals();
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'totals'), $totals);

                $update = true;
            }

            if(isset($data['data']['voucher'])){
                $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

                $voucher = $data['data']['voucher'];

                $this->load->model('checkout/voucher');
                $this->load->language('extension/opencart/total/voucher');
                $voucher_info = $this->model_checkout_voucher->getVoucher($voucher);


                if (empty($data['data']['voucher'])) {
                    $state['notifications']['cart']['error_voucher'] = $this->language->get('error_voucher');
                    $state['session']['voucher'] = "";
                } elseif ($voucher_info) {
                    $state['session']['voucher'] = $voucher;
                    $state['notifications']['cart']['success_voucher'] = $this->language->get('text_success');
                } else {
                    $state['notifications']['cart']['error_voucher'] = $this->language->get('error_voucher');
                }

                if(!isset($state['session']['voucher'])){
                    $state['session']['voucher'] = $voucher;
                }

                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'voucher'), $state['session']['voucher']);
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'notifications' , 'cart'), $state['notifications']['cart']);

                $totals = $this->getTotals();
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'totals'), $totals);

                $update = true;
            }

            if(!empty($data['data']['reward'])){
                $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

                $this->load->language('extension/opencart/total/reward');

                $points = $this->customer->getRewardPoints();

                $points_total = $this->rewardsToUse();

                $reward = $data['data']['reward'];

                if (empty($reward)) {
                    $state['notifications']['cart']['error_reward'] = $this->language->get('error_reward');
                }

                if ($reward > $points) {
                    $state['notifications']['cart']['error_reward'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
                }

                if ($reward > $points_total) {
                    $state['notifications']['cart']['error_reward'] = sprintf($this->language->get('error_maximum'), $points_total);
                }

                if (!isset($state['notifications']['cart'])) {
                    $state['session']['reward'] = abs($reward);

                    $state['notifications']['cart']['success_reward'] = $this->language->get('text_success');
                }

                if(!isset($state['session']['reward'])){
                    $state['session']['reward'] = $reward;
                }

                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'reward'), $state['session']['reward']);
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'notifications' , 'cart'), $state['notifications']['cart']);

                $totals = $this->getTotals();
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'totals'), $totals);

                $update = true;

            }
        }

        if($data['action'] == 'account/update/after'){
            if($this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->isUpdated('account')){
                $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
                if($state['session']['account'] == 'logged'){
                    $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/order');
                    $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_order->initCart();
                }

                //REFACTOR
                //Need for load rewards, etc. after customer log in on checkout page.
                $this->load->config('ajax_quick_checkout/cart');
                $cart_language = $this->getLanguages();
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'language' , 'cart'),  $cart_language);

                $cart = $this->getCart();
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'cart', 'products'), $cart['products']);

                $totals = $this->getTotals();
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'totals'), $totals);

                $update = true;
            }
        }

        if($update){
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('cart_total_text'), $this->getCartTotalText());
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('cart/update/after', $data);
        }

        if($data['action'] == 'total/update'
            || $data['action'] == 'shipping_method/update/after'
            || $data['action'] == 'payment_method/update/after'){

            $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
            if($state['session']['account'] == 'logged'){
                $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/order');
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_order->initCart();
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('cart_total_text'), $this->getCartTotalText());
            }

            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('total/update/before', array());

            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_address->updateTaxAddress();

            $cart = $this->getCart();
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'cart', 'products'), $cart['products']);

            $totals = $this->getTotals();
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'totals'), $totals);

            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch('total/update/after', $data);
        }
    }

    public function validate(){
        $result = true;
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        $this->load->language('extension/ajax_quick_checkout/ajax_quick_checkout/cart');
        if($state['config']['guest']['cart']['min_total'] > $state['session']['total']){
            $state['errors']['cart']['error_min_total'] = sprintf($this->language->get('error_min_total'), $this->currency->format( $state['config']['guest']['cart']['min_total'], $this->session->data['currency']));
            $result = false;
        }else{
            $state['errors']['cart']['error_min_total'] = '';
        }

        if($state['config']['guest']['cart']['min_quantity'] > $state['session']['quantity']){
            $state['errors']['cart']['error_min_quantity'] = sprintf($this->language->get('error_min_quantity'), $state['config']['guest']['cart']['min_quantity']);
            $result = false;
        }else{
            $state['errors']['cart']['error_min_quantity'] = '';
        }

        if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
            $this->load->language('checkout/cart');
            $state['errors']['cart']['error_stock'] = $this->language->get('error_stock');
            $result = false;
        }else{
            $state['errors']['cart']['error_stock'] = '';
        }

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'errors' , 'cart'), $state['errors']['cart']);


        $cart = $this->getCart();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'cart', 'products'), $cart['products']);

        return $result;
    }

    private function getConfig(){
        $this->load->config('ajax_quick_checkout/cart');
        $config = $this->config->get('ajax_quick_checkout_cart');

        $settings = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getSetting();
        $result = array();
        foreach($config['account'] as $account => $value){
            if(!empty($settings['config'][$account]['cart'])){
                $result[$account]['cart'] = $settings['config'][$account]['cart'];
            }else{
                $result[$account]['cart'] = array_replace_recursive($config, $value);
            }
        }

        return $result;
    }

    private function getLanguages(){

        $this->load->language('extension/ajax_quick_checkout/ajax_quick_checkout/confirm');
        $this->load->language('extension/ajax_quick_checkout/ajax_quick_checkout/cart');
        $result = array();
        $languages = $this->config->get('ajax_quick_checkout_cart_language');

        $this->load->language('checkout/cart');
        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $result['heading_title'] = $this->language->get('heading_title');

        $this->load->language('extension/opencart/total/coupon');

        $result['entry_coupon'] = $this->language->get('heading_title');

        $this->load->language('extension/opencart/total/voucher');

        $result['entry_voucher'] = $this->language->get('heading_title');

        $this->load->language('extension/opencart/total/reward');

        $points = $this->customer->getRewardPoints();
        $points_total = $this->rewardsToUse();

        $result['reward_heading_title'] = sprintf($this->language->get('heading_title'), $points ? $points : 0);
        $result['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total ? $points_total : 0);


        $language = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getLanguage();

        if(isset($language['cart'])){
            $result = array_replace_recursive($result, $language['cart']);
        }
        $result['reward_heading_title'] = sprintf($this->language->get('heading_title'), $points ? $points : 0);
        $result['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total ? $points_total : 0);

        if (is_file(DIR_IMAGE . 'catalog/ajax_quick_checkout/step/cart.svg')) {
            $result['image'] = HTTP_SERVER.'image/catalog/ajax_quick_checkout/step/cart.svg';
        } else {
            $result['image'] = HTTP_SERVER.'extension/ajax_quick_checkout/image/catalog/ajax_quick_checkout/step/cart.svg';
        }

        return $result;
    }


    private function getDefault(){
        return $this->getCart();
    }

    private function updateCart($cart){
        if($cart){
            foreach($cart as $key => $value){
                $this->cart->update($key, $value);
            }
        }

        return $this->getCart();
    }

    private function getCart(){

        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_address->updateTaxAddress();

        $data['products'] = array();
        $products = $this->cart->getProducts();
        $this->load->model('tool/image');
        $quantity = 0;
        foreach ($products as $product) {

            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
            }

            if ($product['image']) {
                $image = $this->model_tool_image->resize(
                    $product['image'],
                    $state['config'][$state['session']['account']]['cart']['image_size']['width'],
                    $state['config'][$state['session']['account']]['cart']['image_size']['height']
                    );
            } else {
                $image = '';
            }

            if ($product['image']) {
                $thumb = $this->model_tool_image->resize(
                    $product['image'],
                    $state['config'][$state['session']['account']]['cart']['thumb_size']['width'],
                    $state['config'][$state['session']['account']]['cart']['thumb_size']['height']
                    );
            } else {
                $thumb = '';
            }

            $option_data = array();
            $this->load->model('tool/upload');
            $this->load->model('extension/dv_opencart_patch/helper/general');
            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {

                    $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }
                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => ($this->model_extension_dv_opencart_patch_helper_general->strlen($value) > 20 ? $this->model_extension_dv_opencart_patch_helper_general->substr($value, 0, 20) . '..' : $value)
                    );
            }

                    // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']);
            } else {
                $price = false;
            }

                    // Display prices
            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'],$this->session->data['currency']);
            } else {
                $total = false;
            }

            $subscription = '';

            if ($product['subscription']) {
				$trial_price = $this->currency->format($this->tax->calculate($product['subscription']['trial_price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$trial_cycle = $product['subscription']['trial_cycle'];
				$trial_frequency = $this->language->get('text_' . $product['subscription']['trial_frequency']);
				$trial_duration = $product['subscription']['trial_duration'];

				if ($product['subscription']['trial_status']) {
					$description .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
				}

				$price = $this->currency->format($this->tax->calculate($product['subscription']['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$cycle = $product['subscription']['cycle'];
				$frequency = $this->language->get('text_' . $product['subscription']['frequency']);
				$duration = $product['subscription']['duration'];

				if ($duration) {
					$subscription .= sprintf($this->language->get('text_subscription_duration'), $price, $cycle, $frequency, $duration);
				} else {
					$subscription .= sprintf($this->language->get('text_subscription_cancel'), $price, $cycle, $frequency);
				}
			}
            $data['products'][] = array(
                'key'       => (isset($product['cart_id'])) ? $product['cart_id'] : $product['key'],
                'product_id'=> $product['product_id'],
                'image'     => $image,
                'thumb'     => $thumb,
                'name'      => $product['name'],
                'model'     => $product['model'],
                'option'    => $option_data,
                'subscription' => $subscription,
                'quantity'  => $product['quantity'],
                'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                'price'     => $price,
                'total'     => $total,
                'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );
                
            $data[(isset($product['cart_id'])) ? $product['cart_id'] : $product['key']] = $product['quantity'];

            $quantity = $product['quantity'];
        }
        
        if (!empty($this->session->data['vouchers'])) {
        foreach ($this->session->data['vouchers'] as $voucher) {
              $data['vouchers'][] = array(
                  'description'      => $voucher['description'],
                  'code'             => substr(md5(mt_rand()), 0, 10),
                  'to_name'          => $voucher['to_name'],
                  'to_email'         => $voucher['to_email'],
                  'from_name'        => $voucher['from_name'],
                  'from_email'       => $voucher['from_email'],
                  'voucher_theme_id' => $voucher['voucher_theme_id'],
                  'message'          => $voucher['message'],
                  'amount'           => $voucher['amount']
              );
        }

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array('session', 'vouchers'), $data['vouchers']);

        }

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session' , 'quantity'), $quantity);

        if(!$quantity && !isset($data['vouchers'])){
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'session', 'status'), false);
        }

        if(isset($data['error_warning'])){
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'errors' , 'cart', 'error_minimum'), $data['error_warning']);
        }else{
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(array( 'errors' , 'cart', 'error_minimum'), '');
        }

        return $data;
    }

    private function getCartTotalText(){
        $this->load->language('checkout/cart');
        return sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format( $this->session->data['total'], $this->session->data['currency']));
    }


    private function getTotals(){

        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
            );
        $this->load->model('extension/ajax_quick_checkout/ajax_quick_checkout/order');
        return $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_order->getTotals($total_data);
    }
    private function rewardsToUse(){

        $points_total = 0;

        foreach ($this->cart->getProducts() as $product) {
            if ($product['points']) {
                $points_total += $product['points'];
            }
        }
        return $points_total;
    }
}
