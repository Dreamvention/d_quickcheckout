<?php

class ControllerExtensionDQuickcheckoutCart extends Controller {
    private $route = 'extension/d_quickcheckout/cart';

    public $action = array(
        'cart/update',
        'account/update/after',
        'total/update',
        'shipping_method/update/after',
        'payment_method/update/after',
        'payment_address/update/after',
        'shipping_address/update/after'
    );

    public function __construct($registry){
        parent::__construct($registry);

        $this->load->model('extension/d_quickcheckout/store');
        $this->load->model('extension/d_quickcheckout/method');
        $this->load->model('extension/d_quickcheckout/address');

    }

    /**
     * Initialization
     */
    public function index($config){
        $this->document->addScript('catalog/view/theme/default/javascript/d_quickcheckout/step/cart.js');

        
        $state = $this->model_extension_d_quickcheckout_store->getState();
        $state['config'] = $this->getConfig();
        $state['language']['cart'] = $this->getLanguages();
        $state['action']['cart'] = $this->action;
        $this->model_extension_d_quickcheckout_store->setState($state);

        $state = $this->model_extension_d_quickcheckout_store->getState();
        $cart = $this->getDefault();
        //$this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'cart'), $cart);
        $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'cart', 'products'), $cart['products']);

        $totals = $this->getTotals();
        $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'totals'), $totals);
    }

    /**
     * update via ajax
     */
    public function update(){
        $this->model_extension_d_quickcheckout_store->loadState();
        $this->model_extension_d_quickcheckout_store->dispatch('cart/update/before', $this->request->post);
        $this->model_extension_d_quickcheckout_store->dispatch('cart/update', $this->request->post);

        $data = $this->model_extension_d_quickcheckout_store->getStateUpdated();
        
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
                    $this->model_extension_d_quickcheckout_store->updateState(array( 'session', 'status'), false);
                }else{
                    $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'cart', 'products'), $cart['products']);
                    
                    $totals = $this->getTotals();
                    $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'totals'), $totals);
                }

                $this->validate();

                $update = true;
            }

            if(isset($data['data']['coupon'])){
                $state = $this->model_extension_d_quickcheckout_store->getState();
                $coupon = $data['data']['coupon'];
                if(VERSION < '2.1.0.0'){
                    $this->load->model('checkout/coupon');
                    $this->load->language('checkout/coupon');
                    $coupon_info = $this->model_checkout_coupon->getCoupon($coupon);
                }elseif(VERSION < '2.3.0.0'){
                    $this->load->model('total/coupon');
                    $this->load->language('total/coupon');
                    $coupon_info = $this->model_total_coupon->getCoupon($coupon);
                }else{
                    $this->load->model('extension/total/coupon');
                    $this->load->language('extension/total/coupon');
                    $coupon_info = $this->model_extension_total_coupon->getCoupon($coupon);
                }

                if (empty($data['data']['coupon'])) {
                    $state['notifications']['cart']['error_coupon'] = $this->language->get('error_empty');

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

                $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'coupon'), $state['session']['coupon']);
                $this->model_extension_d_quickcheckout_store->updateState(array( 'notifications' , 'cart'), $state['notifications']['cart']);

                $totals = $this->getTotals();
                $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'totals'), $totals);

                $update = true;
            }

            if(isset($data['data']['voucher'])){
                $state = $this->model_extension_d_quickcheckout_store->getState();

                $voucher = $data['data']['voucher'];
                if(VERSION < '2.1.0.0'){
                    $this->load->model('checkout/voucher');
                    $this->load->language('checkout/voucher');
                    $voucher_info = $this->model_checkout_voucher->getVoucher($voucher);
                }elseif(VERSION < '2.3.0.0'){
                    $this->load->model('total/voucher');
                    $this->load->language('total/voucher');
                    $voucher_info = $this->model_total_voucher->getVoucher($voucher);
                }else{
                    $this->load->model('extension/total/voucher');
                    $this->load->language('extension/total/voucher');
                    $voucher_info = $this->model_extension_total_voucher->getVoucher($voucher);
                }

                if (empty($data['data']['voucher'])) {
                    $state['notifications']['cart']['error_voucher'] = $this->language->get('error_empty');
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

                $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'voucher'), $state['session']['voucher']);
                $this->model_extension_d_quickcheckout_store->updateState(array( 'notifications' , 'cart'), $state['notifications']['cart']);

                $totals = $this->getTotals();
                $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'totals'), $totals);

                $update = true;
            }

            if(!empty($data['data']['reward'])){
                $state = $this->model_extension_d_quickcheckout_store->getState();
                if(VERSION < '2.3.0.0'){
                    $this->load->language('total/reward');
                }else{
                    $this->load->language('extension/total/reward');
                }
                
                $points = $this->customer->getRewardPoints();

                $points_total = 0;

                foreach ($this->cart->getProducts() as $product) {
                    if ($product['points']) {
                        $points_total += $product['points'];
                    }
                }

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

                if (!$state['notifications']['cart']) {
                    $state['session']['reward'] = abs($reward);

                    $state['notifications']['cart']['success_reward'] = $this->language->get('text_success');
                }

                if(!isset($state['session']['reward'])){
                    $state['session']['reward'] = $reward;
                }

                $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'reward'), $state['session']['reward']);
                $this->model_extension_d_quickcheckout_store->updateState(array( 'notifications' , 'cart'), $state['notifications']['cart']);

                $totals = $this->getTotals();
                $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'totals'), $totals);

                $update = true;

            }
        }

        if($data['action'] == 'account/update/after'){
            if($this->model_extension_d_quickcheckout_store->isUpdated('account')){
                $state = $this->model_extension_d_quickcheckout_store->getState();
                if($state['session']['account'] == 'logged'){
                    $this->load->model('extension/d_quickcheckout/order');
                    $this->model_extension_d_quickcheckout_order->initCart();
                }
                
                $cart = $this->getCart();
                $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'cart', 'products'), $cart['products']);

                $totals = $this->getTotals();
                $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'totals'), $totals);

                $update = true;
            }
        }

        if($update){
            $this->model_extension_d_quickcheckout_store->updateState(array('cart_total_text'), $this->getCartTotalText());
            $this->model_extension_d_quickcheckout_store->dispatch('cart/update/after', $data);
        }

        if($data['action'] == 'total/update'
            || $data['action'] == 'shipping_method/update/after'
            || $data['action'] == 'payment_method/update/after'){

            $state = $this->model_extension_d_quickcheckout_store->getState();
            if($state['session']['account'] == 'logged'){
                $this->load->model('extension/d_quickcheckout/order');
                $this->model_extension_d_quickcheckout_order->initCart();
                $this->model_extension_d_quickcheckout_store->updateState(array('cart_total_text'), $this->getCartTotalText());
            }

            $this->model_extension_d_quickcheckout_store->dispatch('total/update/before', array());

            $this->model_extension_d_quickcheckout_address->updateTaxAddress();

            $cart = $this->getCart();
            $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'cart', 'products'), $cart['products']);

            $totals = $this->getTotals();
            $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'totals'), $totals);

            $this->model_extension_d_quickcheckout_store->dispatch('total/update/after', $data);
        }
    }

    public function validate(){
        $result = true;
        $state = $this->model_extension_d_quickcheckout_store->getState();
        $this->load->language('extension/d_quickcheckout/cart');
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

        $this->model_extension_d_quickcheckout_store->updateState(array( 'errors' , 'cart'), $state['errors']['cart']);
        

        $cart = $this->getCart();
        $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'cart', 'products'), $cart['products']);



        return $result;
    }

    private function getConfig(){
        
        $this->load->config('d_quickcheckout/cart');
        $config = $this->config->get('d_quickcheckout_cart');

        $settings = $this->model_extension_d_quickcheckout_store->getSetting();
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
        $this->load->language('checkout/cart');
        $this->load->language('checkout/checkout');
        $this->load->language('extension/d_quickcheckout/confirm');
        $result = array();
        $languages = $this->config->get('d_quickcheckout_cart_language');

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        if(VERSION < '2.1.0.0'){
            $this->load->language('checkout/coupon');
        }elseif(VERSION < '2.3.0.0'){
            $this->load->language('total/coupon');
        }else{
            $this->load->language('extension/total/coupon');
        }
        $result['entry_coupon'] = $this->language->get('heading_title');
        if(VERSION < '2.1.0.0'){
            $this->load->language('checkout/voucher');
        }elseif(VERSION < '2.3.0.0'){
            $this->load->language('total/voucher');
        }else{
            $this->load->language('extension/total/voucher');
        }
        $result['entry_voucher'] = $this->language->get('heading_title');
        if(VERSION < '2.1.0.0'){
            $this->load->language('checkout/reward');
        }elseif(VERSION < '2.3.0.0'){
            $this->load->language('total/reward');
        }else{
            $this->load->language('extension/total/reward');
        }
        $points = $this->customer->getRewardPoints();
        $result['entry_reward'] = sprintf($this->language->get('heading_title'), $points ? $points : 0);

        $language = $this->model_extension_d_quickcheckout_store->getLanguage();
        if(isset($language['cart'])){
            $result = array_replace_recursive($result, $language['cart']);
        }

        $result['image'] = HTTPS_SERVER.'image/catalog/d_quickcheckout/step/cart.svg';

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

        $state = $this->model_extension_d_quickcheckout_store->getState();

        $this->model_extension_d_quickcheckout_address->updateTaxAddress();

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
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
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

            $recurring = '';

            if ($product['recurring']) {
                $frequencies = array(
                    'day'        => $this->language->get('text_day'),
                    'week'       => $this->language->get('text_week'),
                    'semi_month' => $this->language->get('text_semi_month'),
                    'month'      => $this->language->get('text_month'),
                    'year'       => $this->language->get('text_year'),
                    );

                if ($product['recurring']['trial']) {
                    $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                }

                if ($product['recurring']['duration']) {
                    $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                } else {
                    $recurring .= sprintf($this->language->get('text_payment_until_canceled_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
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
                'recurring' => $recurring,
                'quantity'  => $product['quantity'],
                'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                'price'     => $price,
                'total'     => $total,
                'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );
                            // fix for 2.1.0.0
            $data[(isset($product['cart_id'])) ? $product['cart_id'] : $product['key']] = $product['quantity'];

            $quantity = $product['quantity'];
        }
        $this->model_extension_d_quickcheckout_store->updateState(array( 'session' , 'quantity'), $quantity);

        if(!$quantity){
            $this->model_extension_d_quickcheckout_store->updateState(array( 'session', 'status'), false);
        }

        if(isset($data['error_warning'])){
            $this->model_extension_d_quickcheckout_store->updateState(array( 'errors' , 'cart', 'error_minimum'), $data['error_warning']);
        }else{
            $this->model_extension_d_quickcheckout_store->updateState(array( 'errors' , 'cart', 'error_minimum'), '');
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
        $this->load->model('extension/d_quickcheckout/order');
        return $this->model_extension_d_quickcheckout_order->getTotals($total_data);
    }
}
