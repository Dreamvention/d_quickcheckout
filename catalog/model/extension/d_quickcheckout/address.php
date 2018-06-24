<?php
class ModelExtensionDQuickcheckoutAddress extends Model {
    /*
     *  Country and Zones
     */

    public function getCountries() {
        $this->load->model('localisation/country');
        $countries = $this->model_localisation_country->getCountries();
        $options = array();
        foreach ($countries as $country) {
            $country['value'] = $country['country_id'];
            unset($country['country_id']);
            $options[] = $country;
        }
        return $options;
    }

    public function getZonesByCountryId($country_id) {
        $options = array();
        if($country_id && is_numeric($country_id)){
            $this->load->model('localisation/zone');
            $zones = $this->model_localisation_zone->getZonesByCountryId($country_id);
            $options = array();
            if($zones){
                foreach ($zones as $zone) {
                    $zone['value'] = $zone['zone_id'];
                    unset($zone['zone_id']);
                    $options[] = $zone;
                }
            }else{
                $options[] = array(
                    'code' => '',
                    'country_id' => $country_id,
                    'name' => $this->language->get('text_none'),
                    'status' => '1',
                    'value' => '0'
                );
            }
        }
        return $options;
    }

    public function updateTaxAddress() {
        $this->tax->clearRates();
        $address = $this->paymentOrShippingAddress();

        $this->config->set('config_customer_group_id', $this->session->data['payment_address']['customer_group_id']);

        $this->tax->setShippingAddress($address['country_id'], $address['zone_id']);
        $this->tax->setPaymentAddress($this->session->data['payment_address']['country_id'], $this->session->data['payment_address']['zone_id']);
        $this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
    }

    public function paymentOrShippingAddress() {
        $address = array();
        if(isset($this->session->data['shipping_address'])){
            $address = $this->session->data['shipping_address'];
        }
        
        if (isset($this->session->data['payment_address']) && isset($this->session->data['payment_address']['shipping_address']) && $this->session->data['payment_address']['shipping_address']) {
            $address = $this->session->data['payment_address'];
        }
        return $address;
    }

    public function getCountryInfo($country_id) {
        $this->load->model('localisation/country');
        return $this->model_localisation_country->getCountry($country_id);
    }

    public function getZoneInfo($zone_id) {
        $this->load->model('localisation/zone');
        return $this->model_localisation_zone->getZone($zone_id);
    }

    public function getAddress($address_id) {
        $this->load->model('account/address');
        $address = $this->model_account_address->getAddress($address_id);

        if (!empty($address) && empty($address['address_format'])) {
                $address['address_format'] = '{firstname} {lastname}' . '{company}' . "\n" . '{address_1}' . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }

        return $address;
    }

    public function getAddresses() {

        $this->load->model('account/address');
        $addresses = $this->model_account_address->getAddresses();
        foreach ($addresses as $key => $address) {
            if (!empty($address) && empty($address['address_format'])) {
                $addresses[$key]['address_format'] = '{firstname} {lastname}' . '{company}' . "\n" . '{address_1}' . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }
        }
        return $addresses;
    }

    public function addAddress($data){
        $this->load->model('account/address');

        if(VERSION < '3.0.0.0'){
            $address_id = $this->model_account_address->addAddress($data);
        }else{
            $address_id = $this->model_account_address->addAddress($this->customer->getId(), $data);
        }
        
        return $address_id;
    }


    public function setPaymentAddressCountry($country_id){

        $country_info = $this->getCountryInfo($country_id);
        if ($country_info) {
            $this->session->data['payment_address']['country'] = $country_info['name'];
            $this->session->data['payment_address']['iso_code_2'] = $country_info['iso_code_2'];
            $this->session->data['payment_address']['iso_code_3'] = $country_info['iso_code_3'];
            $this->session->data['payment_address']['address_format'] = $country_info['address_format'];
        }else{
            $this->session->data['payment_address']['country'] = '';
            $this->session->data['payment_address']['iso_code_2'] = '';
            $this->session->data['payment_address']['iso_code_3'] = '';
            $this->session->data['payment_address']['address_format'] = '';
        }
    }

    public function setShippingAddressCountry($country_id){

        $country_info = $this->getCountryInfo($country_id);
        if ($country_info) {
            $this->session->data['shipping_address']['country'] = $country_info['name'];
            $this->session->data['shipping_address']['iso_code_2'] = $country_info['iso_code_2'];
            $this->session->data['shipping_address']['iso_code_3'] = $country_info['iso_code_3'];
            $this->session->data['shipping_address']['address_format'] = $country_info['address_format'];
        }else{
            $this->session->data['shipping_address']['country'] = '';
            $this->session->data['shipping_address']['iso_code_2'] = '';
            $this->session->data['shipping_address']['iso_code_3'] = '';
            $this->session->data['shipping_address']['address_format'] = '';
        }
    }

    public function setPaymentAddressZone($zone_id){
        $zone_info = $this->getZoneInfo($zone_id);

        if ($zone_info) {
            $this->session->data['payment_address']['zone'] = $zone_info['name'];
            $this->session->data['payment_address']['zone_code'] = $zone_info['code'];
        } else {
            $this->session->data['payment_address']['zone'] = '';
            $this->session->data['payment_address']['zone_code'] = '';
        }
    }

    public function setShippingAddressZone($zone_id){
        $zone_info = $this->getZoneInfo($zone_id);

        if ($zone_info) {
            $this->session->data['shipping_address']['zone'] = $zone_info['name'];
            $this->session->data['shipping_address']['zone_code'] = $zone_info['code'];
        } else {
            $this->session->data['shipping_address']['zone'] = '';
            $this->session->data['shipping_address']['zone_code'] = '';
        }
    }


    private function getDisplayShippingAddress(){

        if($this->session->data['account'] == 'logged' && $this->session->data['payment_address']['address_id'] != 0){
            $display = 1;
        }elseif(empty($this->session->data['payment_address']['shipping_address'])){
            $display = 1;
        }else{
            $display = 0;
        }

        return $display;
    }
}