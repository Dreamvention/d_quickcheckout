<?php
/*
 *  location: admin/model
 */

class ModelExtensionDQuickcheckoutMethod extends Model {

    /*
    *   Shipping method
    */

    public function shippingRequired()
    {
        if($this->cart->hasShipping()){
            return true;
        }
        return false;
    }

    public function getFirstShippingMethod()
    {
        if(isset($this->session->data['shipping_methods']) && is_array($this->session->data['shipping_methods'])){
            foreach ($this->session->data['shipping_methods'] as $group){
                foreach($group['quote'] as $shipping_method){
                    return $shipping_method;
                }
            }
        }
        return false;
    }

    public function getDefaultShippingMethod($default_option)
    {
        if(!empty($default_option)){
            if(isset($this->session->data['shipping_methods']) && is_array($this->session->data['shipping_methods'])){
                foreach ($this->session->data['shipping_methods'] as $group){
                    if(isset($group['quote'][$default_option])){
                        return $group['quote'][$default_option];
                    }
                }
            }
        }

        return $this->getFirstShippingMethod();
    }

    public function getShippingMethods($shipping_address){
        $method_data = array();

        if (VERSION >='3.0.0.0'){
            $this->load->model('setting/extension');
            $results = $this->model_setting_extension->getExtensions('shipping');
        } else {
            $this->load->model('extension/extension');
            $results = $this->model_extension_extension->getExtensions('shipping');
        }


        foreach ($results as $result) {

            if (VERSION>='3.0.0.0'){
                if ($this->config->get('shipping_'.$result['code'] . '_status')) {

                    if(file_exists(DIR_APPLICATION . 'model/extension/shipping/' . $result['code'] . '.php')){

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
            } else {
                if ($this->config->get($result['code'] . '_status')) {

                    if(file_exists(DIR_APPLICATION . 'model/extension/shipping/' . $result['code'] . '.php')){

                        $this->load->model('extension/shipping/' . $result['code']);
                        $quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($shipping_address);

                    } elseif (file_exists(DIR_APPLICATION . 'model/shipping/' . $result['code'] . '.php')) {

                        $this->load->model('shipping/' . $result['code']);
                        $quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address);
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

        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);
        return $method_data;
    }

    /*
    *   Payment method
    */

    public function getFirstPaymentMethod()
    {
        if(isset($this->session->data['payment_methods']) && is_array($this->session->data['payment_methods'])){
            foreach ($this->session->data['payment_methods'] as $payment_method){
                return $payment_method;
            }
        }
        return false;
    }

    public function getDefaultPaymentMethod($setting_payment_method = false)
    {
        if(isset($this->session->data['payment_methods']) && is_array($this->session->data['payment_methods'])){
            if(array_key_exists($setting_payment_method, $this->session->data['payment_methods'])){
                return $this->session->data['payment_methods'][$setting_payment_method];
            }
        }
        return $this->getFirstPaymentMethod();
    }



    public function getPaymentMethods($payment_address, $total)
    {
        $method_data = array();

        if (VERSION >='3.0.0.0'){
            $this->load->model('setting/extension');
            $results = $this->model_setting_extension->getExtensions('payment');
        } else {
            $this->load->model('extension/extension');
            $results = $this->model_extension_extension->getExtensions('payment');
        }

        $recurring = $this->cart->hasRecurringProducts();

        foreach ($results as $result) {
            if (VERSION>='3.0.0.0') {
                if ($this->config->get('payment_'.$result['code'] . '_status')) {


                    if(file_exists(DIR_APPLICATION . 'model/extension/payment/' . $result['code'] . '.php')){

                        $this->load->model('extension/payment/' . $result['code']);
                        $method = $this->{'model_extension_payment_' . $result['code']}->getMethod($payment_address, $total);
                    }

                    if ($method) {
                        if ($recurring) {

                            if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
                                $method_data[$result['code']] = $method;
                            }

                        } else {
                            $method_data[$result['code']] = $method;
                        }

                        if(file_exists(DIR_IMAGE.'catalog/d_quickcheckout/payment/'.$result['code'].'.png')){
                            $method_data[$result['code']]['image'] = 'image/catalog/d_quickcheckout/payment/'.$result['code'].'.png';
                        }
                    }
                }
            } else {
                if ($this->config->get($result['code'] . '_status')) {

                    if(file_exists(DIR_APPLICATION . 'model/extension/payment/' . $result['code'] . '.php')){

                        $this->load->model('extension/payment/' . $result['code']);
                        $method = $this->{'model_extension_payment_' . $result['code']}->getMethod($payment_address, $total);

                    } elseif (file_exists(DIR_APPLICATION . 'model/payment/' . $result['code'] . '.php')) {

                        $this->load->model('payment/' . $result['code']);
                        $method = $this->{'model_payment_' . $result['code']}->getMethod($payment_address, $total);
                    }

                    if ($method) {
                        if ($recurring) {
                            if (VERSION < '2.3.0.0'){

                                if(file_exists(DIR_APPLICATION . 'model/extension/payment/' . $result['code'] . '.php')){

                                    if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
                                        $method_data[$result['code']] = $method;
                                    }

                                } elseif (file_exists(DIR_APPLICATION . 'model/payment/' . $result['code'] . '.php')) {

                                    if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
                                        $method_data[$result['code']] = $method;
                                    }
                                }

                            } else {
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
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        return $method_data;
    }

    public function getPaymentPopup($payment = false)
    {
        $result =  $this->session->data['d_quickcheckout']['account'][$this->session->data['account']]['payment']['default_payment_popup'];
        if($payment){
            if(isset($this->session->data['d_quickcheckout']['account'][$this->session->data['account']]['payment']['payment_popups'][$payment])){
                $result =  $this->session->data['d_quickcheckout']['account'][$this->session->data['account']]['payment']['payment_popups'][$payment];
            }
        }

        return $result;
    }
}
