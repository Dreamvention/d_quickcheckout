<?php

    /*
     * 	location: admin/model
     */

    class ModelDQuickcheckoutAddress extends Model {
        /*
         * 	This is a Opencart Hack to update the Tax Address, because in opencart 
         * 	the addresses are set in system/library/tax.php in _construct before the 
         * 	session is changed. Therefore the tax address is not correctly set and  
         * 	requires us to reset it according to the new session. 
         */

        public function getCustomerGroups() {
            $result = array();
            if (is_array($this->config->get('config_customer_group_display'))) {

                $this->load->model('account/customer_group');
                $customer_groups = $this->model_account_customer_group->getCustomerGroups();

                foreach ($customer_groups as $customer_group) {

                    //customer_group_id
                    $customer_group['value'] = $customer_group['customer_group_id'];

                    //name
                    $customer_group['title'] = $customer_group['name'];

                    if (in_array($customer_group['value'], $this->config->get('config_customer_group_display'))) {
                        $result[] = $customer_group;
                    }
                }
            }

            return $result;
        }

        public function updateTaxAddress() {
            $this->tax->clearRates();
            $address = $this->paymentOrShippingAddress();
            $this->tax->setShippingAddress($address['country_id'], $address['zone_id']);
            $this->tax->setPaymentAddress($this->session->data['payment_address']['country_id'], $this->session->data['payment_address']['zone_id']);
            $this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
        }

        public function paymentOrShippingAddress() {

            $address = $this->session->data['shipping_address'];
            if (isset($this->session->data['payment_address']['shipping_address']) && $this->session->data['payment_address']['shipping_address']) {
                $address = $this->session->data['payment_address'];
            }
            return $address;
        }

        public function showShippingAddress() {
            if (!$this->session->data['d_quickcheckout']['account'][$this->session->data['account']]['shipping_address']['display']) {
                return false;
                
            }
            if (!$this->cart->hasShipping()) {
                return false;
            }
            
            if ($this->session->data['d_quickcheckout']['account'][$this->session->data['account']]['shipping_address']['require']) {
                return true;
            }

            if (isset($this->session->data['payment_address']['shipping_address']) && $this->session->data['payment_address']['shipping_address'] && !$this->customer->isLogged()) {

                return false;
            }

            if (isset($this->session->data['payment_address']['shipping_address']) && $this->session->data['payment_address']['shipping_address'] && $this->customer->isLogged() && $this->session->data['payment_address']['address_id'] == 'new') {
                  
                return false;
            }

            return true;
        }

        public function getPaymentAddressCountryId() {
            if (isset($this->session->data['payment_address']) && isset($this->session->data['payment_address']['country_id'])) {
                return $this->session->data['payment_address']['country_id'];
            }

            return $this->config->get('config_country_id');
        }

        public function getShippingAddressCountryId() {
            if (isset($this->session->data['shipping_address']) && isset($this->session->data['shipping_address']['country_id'])) {
                return $this->session->data['shipping_address']['country_id'];
            }

            return $this->config->get('config_country_id');
        }

        /* 	
         * 	Country and Zones
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
            $this->load->model('localisation/zone');
            $zones = $this->model_localisation_zone->getZonesByCountryId($country_id);
            $options = array();
            foreach ($zones as $zone) {
                $zone['value'] = $zone['zone_id'];
                unset($zone['zone_id']);
                $options[] = $zone;
            }
            return $options;
        }

        public function getCountryInfo($country_id) {
            $this->load->model('localisation/country');
            return $this->model_localisation_country->getCountry($country_id);
        }

        public function getZoneInfo($zone_id) {
            $this->load->model('localisation/zone');
            return $this->model_localisation_zone->getZone($zone_id);
        }

        public function compareAddress($new_address, $old_address) {

            if ($new_address['country_id'] !== $old_address['country_id']) {
                $country_info = $this->getCountryInfo($new_address['country_id']);
                if ($country_info) {
                    $new_address['country'] = $country_info['name'];
                    $new_address['iso_code_2'] = $country_info['iso_code_2'];
                    $new_address['iso_code_3'] = $country_info['iso_code_3'];
                    $new_address['zone_id'] = 0;

                    if (!empty($country_info['address_format'])) {
                        $new_address['address_format'] = $country_info['address_format'];
                    } else {
                        $new_address['address_format'] = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
                    }
                } else {
                    $new_address['country'] = '';
                    $new_address['iso_code_2'] = '';
                    $new_address['iso_code_3'] = '';
                    $new_address['address_format'] = '';
                    $new_address['zone_id'] = 0;
                }
            }


            if ($new_address['zone_id'] !== $old_address['zone_id']) {
                $zone_info = $this->getZoneInfo($new_address['zone_id']);
                if ($zone_info) {
                    $new_address['zone'] = $zone_info['name'];
                    $new_address['zone_code'] = $zone_info['code'];
                } else {
                    $new_address['zone'] = '';
                    $new_address['zone_code'] = '';
                }
            }

            return $new_address;
        }

        public function prepareAddress($address) {

            $country_info = $this->getCountryInfo($address['country_id']);
            if ($country_info) {
                $address['country'] = $country_info['name'];
                $address['iso_code_2'] = $country_info['iso_code_2'];
                $address['iso_code_3'] = $country_info['iso_code_3'];

                if (!empty($country_info['address_format'])) {
                    $address['address_format'] = $country_info['address_format'];
                } else {
                    $address['address_format'] = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
                }
            } else {
                $address['country'] = '';
                $address['iso_code_2'] = '';
                $address['iso_code_3'] = '';
                $address['address_format'] = '';
            }

            if (!isset($address['zone_id'])) {
                $address['zone_id'] = 0;
            }
            $zone_info = $this->getZoneInfo($address['zone_id']);
            if ($zone_info) {
                $address['zone'] = $zone_info['name'];
                $address['zone_code'] = $zone_info['code'];
            } else {
                $address['zone'] = '';
                $address['zone_code'] = '';
            }

            return $address;
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

    }
    