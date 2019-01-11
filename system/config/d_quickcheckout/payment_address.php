<?php 
$_['d_quickcheckout_payment_address_language'] = array(
    'heading_title' => 'text_payment_address',
    'description_payment_address' => '',

    'entry_firstname' => 'entry_firstname',
    'placeholder_firstname' => 'entry_firstname',
    'tooltip_firstname' => '',
    'error_firstname_min_length' => 'error_firstname',
    'error_firstname_max_length' => 'error_firstname',

    'entry_lastname' => 'entry_lastname',
    'placeholder_lastname' => 'entry_lastname',
    'tooltip_lastname' => '',
    'error_lastname_min_length' => 'error_lastname',
    'error_lastname_max_length' => 'error_lastname',

    'entry_email' => 'entry_email',
    'placeholder_email' => 'entry_email',
    'tooltip_email' => '',
    'error_email_min_length' => 'error_email_length',
    'error_email_max_length' => 'error_email_length',
    'error_email_regex' => 'error_email',
    'error_email_email_exists' => 'error_exists',
    'error_email_not_empty' => 'error_email',

    'entry_email_confirm' => 'entry_email_confirm',
    'placeholder_email_confirm' => 'entry_email_confirm',
    'tooltip_email_confirm' => '',
    'error_email_confirm_compare_to' => 'error_email_confirm_compare',

    'entry_telephone' => 'entry_telephone',
    'placeholder_telephone' => 'entry_telephone',
    'tooltip_telephone' => '',
    'error_telephone_min_length' => 'error_telephone',
    'error_telephone_max_length' => 'error_telephone',
    'error_telephone_telephone' => 'error_telephone',

    'entry_fax' => 'entry_fax',
    'placeholder_fax' => 'entry_fax',
    'tooltip_fax' => '',
    'error_fax_min_length' => 'error_telephone',
    'error_fax_max_length' => 'error_telephone',

    'entry_password' => 'entry_password',
    'placeholder_password' => 'entry_password',
    'tooltip_password' => '',
    'error_password_min_length' => 'error_confirm',
    'error_password_max_length' => 'error_confirm',

    'entry_confirm' => 'entry_confirm',
    'placeholder_confirm' => 'entry_confirm',
    'tooltip_confirm' => '',
    'error_confirm_min_length' => 'error_password',
    'error_confirm_compare_to' => 'error_password',

    'entry_heading' => 'text_your_address',

    'entry_company' => 'entry_company',
    'placeholder_company' => 'entry_company',
    'tooltip_company' => '',
    'error_company_min_length' => 'error_company_length',
    'error_company_max_length' => 'error_company_length',

    'entry_customer_group_id' => 'entry_customer_group',
    'tooltip_customer_group_id' => '',

    'entry_address_1' => 'entry_address_1',
    'placeholder_address_1' => 'entry_address_1',
    'tooltip_address_1' => '',
    'error_address_1_min_length' => 'error_address_1',
    'error_address_1_max_length' => 'error_address_1',

    'entry_address_2' => 'entry_address_2',
    'placeholder_address_2' => 'entry_address_2',
    'tooltip_address_2' => '',
    'error_address_2_min_length' => 'error_address_1',
    'error_address_2_max_length' => 'error_address_1',

    'entry_city' => 'entry_city',
    'placeholder_city' => 'entry_city',
    'tooltip_city' => '',
    'error_city_min_length' => 'error_city',
    'error_city_max_length' => 'error_city',

    'entry_postcode' => 'entry_postcode',
    'placeholder_postcode' => 'entry_postcode',
    'tooltip_postcode' => '',
    'error_postcode_min_length' => 'error_postcode',
    'error_postcode_max_length' => 'error_postcode',

    'entry_country_id' => 'entry_country',
    'placeholder_country_id' => 'text_select',
    'tooltip_country_id' => '',
    'error_country_id_not_empty' => 'error_country',

    'entry_zone_id' => 'entry_zone',
    'placeholder_zone_id' => 'text_select',
    'tooltip_zone_id' => '',
    'error_zone_id_not_empty' => 'error_country',

    'entry_newsletter' => 'entry_newsletter',
    'tooltip_newsletter' => '',
    'error_newsletter_checked' => 'error_newsletter_checked',

    'entry_shipping_address' => 'entry_shipping',
    'tooltip_shipping_address' => '',
    'error_shipping_address_checked' => 'error_shipping_address_checked',

    'entry_agree' => 'text_agree',
    'tooltip_agree' => '',
    'error_agree_checked' => 'error_agree',

    'text_address_style' => 'text_address_style'

);
$_['d_quickcheckout_payment_address'] = array(
    'id' => 'payment_address',
    'icon' => '', //'fa fa-book',
    'text' => 'heading_title',
    'description' => 'description_payment_address',
    'address_style' => 'radio',
    'style' => 'card',
    'fields' => array(
        'firstname' => array(
            'id' => 'firstname',
            'text' => 'entry_firstname',
            'placeholder' => 'placeholder_firstname',
            'tooltip' => 'tooltip_firstname',
            'errors' => array(
                'error0' => array(
                    'min_length' => 1,
                    'text' => 'error_firstname_min_length'
                ),
                'error1' => array(
                    'max_length' => 32,
                    'text' => 'error_firstname_max_length'
                )
            ),
            'autocomplete' => 'given-name',
            'type' => 'text',
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 0,
            'style' => 'col',
            'class' => '',
            'mask' => '',
        ),
        'lastname' => array(
            'id' => 'lastname',
            'text' => 'entry_lastname',
            'placeholder' => 'placeholder_lastname',
            'tooltip' => 'tooltip_lastname',
            'errors' => array(
                'error0' => array(
                    'min_length' => 1,
                    'text' => 'error_lastname_min_length'
                ),
                'error1' => array(
                    'max_length' => 32,
                    'text' => 'error_lastname_max_length'
                )
            ),
            'autocomplete' => 'family-name',
            'type' => 'text',
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 1,
            'style' => 'col',
            'class' => '',
            'mask' => '',
        ),
        'email' => array(
            'id' => 'email',
            'text' => 'entry_email',
            'placeholder' => 'placeholder_email',
            'tooltip' => 'tooltip_email',
            'errors' => array(),
            'autocomplete' => 'email',
            'type' => 'email',
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 2,
            'style' => 'row',
            'class' => '',
            'mask' => '',
        ),
        'email_confirm' => array(
            'id' => 'email_confirm',
            'text' => 'entry_email_confirm',
            'placeholder' => 'placeholder_email_confirm',
            'tooltip' => 'tooltip_email_confirm',
            'errors' => array(
                'error0' => array(
                    'compare_to' => 'payment_address.email',
                    'text' => 'error_email_confirm_compare_to'
                )
            ),
            'type' => 'text',
            'autocomplete' => 'email',
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 3,
            'style' => 'row',
            'class' => '',
            'mask' => '',
        ),
        
        'telephone' => array(
            'id' => 'telephone',
            'text' => 'entry_telephone',
            'placeholder' => 'placeholder_telephone',
            'tooltip' => 'tooltip_telephone',
            'errors' => array(
                'error0' => array(
                    'min_length' => 3,
                    'text' => 'error_telephone_min_length'
                ),
                'error1' => array(
                    'max_length' => 32,
                    'text' => 'error_telephone_max_length'
                )
            ),
            'validation' => 0,
            'countries' => '',
            'autocomplete' => 'tel',
            'type' => 'tel',
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 4,
            'style' => 'row',
            'class' => '',
            'mask' => '',
        ),

        'fax' => array(
            'id' => 'fax',
            'text' => 'entry_fax',
            'placeholder' => 'placeholder_fax',
            'tooltip' => 'tooltip_fax',
            'errors' => array(
                'error0' => array(
                    'min_length' => 3,
                    'text' => 'error_fax_min_length'
                ),
                'error1' => array(
                    'max_length' => 32,
                    'text' => 'error_fax_max_length'
                )
            ),
            'type' => 'text',
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 5,
            'style' => 'list',
            'class' => '',
            'mask' => '',
        ),
        'password' => array(
            'id' => 'password',
            'text' => 'entry_password',
            'placeholder' => 'placeholder_password',
            'tooltip' => 'tooltip_password',
            'errors' => array(
                'error0' => array(
                    'min_length' => 3,
                    'text' => 'error_password_min_length'
                ),
                'error1' => array(
                    'max_length' => 20,
                    'text' => 'error_password_max_length'
                )
            ),
            'type' => 'password',
            'refresh' => '3',
            'custom' => 0,
            'sort_order' => 6,
            'style' => 'row',
            'class' => ''
        ),
        'confirm' => array(
            'id' => 'confirm',
            'text' => 'entry_confirm',
            'placeholder' => 'placeholder_confirm',
            'tooltip' => 'tooltip_confirm',
            'errors' => array(
                'error0' => array(
                    'compare_to' => 'payment_address.password',
                    'text' => 'error_confirm_compare_to'
            ),
                'error1' => array(
                    'min_length' => '1',
                    'text' => 'error_confirm_min_length'
                )
            ),
            'type' => 'password',
            'refresh' => '3',
            'custom' => 0,
            'sort_order' => 7,
            'style' => 'row',
            'class' => ''
        ),
        'heading' => array(
            'id' => 'heading',
            'text' => 'entry_heading',
            'errors' => array(),
            'type' => 'heading',
            'sort_order' => 8,
            'class' => ''
        ),
        'company' => array(
            'id' => 'company',
            'text' => 'entry_company',
            'placeholder' => 'placeholder_company',
            'tooltip' => 'tooltip_company',
            'errors' => array(
                'error0' => array(
                    'min_length' => 3,
                    'text' => 'error_company_min_length'
                ),
                'error1' => array(
                    'max_length' => 34,
                    'text' => 'error_company_max_length'
                )
            ),
            'autocomplete' => 'organization',
            'type' => 'text',
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 9,
            'style' => 'row',
            'class' => '',
            'mask' => '',
        ),
        'customer_group_id' => array(
            'id' => 'customer_group_id',
            'text' => 'entry_customer_group_id',
            'tooltip' => 'tooltip_customer_group_id',
            'errors' => array(),
            'type' => 'radio',
            'refresh' => '2',
            'custom' => 0,
            'sort_order' => 10,
            'style' => 'list',
            'class' => ''
        ),
        'address_1' => array(
            'id' => 'address_1',
            'text' => 'entry_address_1',
            'placeholder' => 'placeholder_address_1',
            'tooltip' => 'tooltip_address_1',
            'errors' => array(
                'error0' => array(
                    'min_length' => 3,
                    'text' => 'error_address_1_min_length'
                ),
                'error1' => array(
                    'max_length' => 128,
                    'text' => 'error_address_1_max_length'
                )
            ),
            'autocomplete' => 'address-line1',
            'type' => 'text',
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 11,
            'style' => 'row',
            'class' => '',
            'mask' => '',
        ),
        'address_2' => array(
            'id' => 'address_2',
            'text' => 'entry_address_2',
            'placeholder' => 'placeholder_address_2',
            'tooltip' => 'tooltip_address_2',
            'errors' => array(
                'error0' => array(
                    'min_length' => 3,
                    'text' => 'error_address_2_min_length'
                ),
                'error1' => array(
                    'max_length' => 128,
                    'text' => 'error_address_2_max_length'
                )
            ),
            'autocomplete' => 'address-line2',
            'type' => 'text',
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 12,
            'style' => 'row',
            'class' => '',
            'mask' => '',
         ),
        'city' => array(
            'id' => 'city',
            'text' => 'entry_city',
            'placeholder' => 'placeholder_city',
            'tooltip' => 'tooltip_city',
            'errors' => array(
                'error0' => array(
                    'min_length' => 3,
                    'text' => 'error_city_min_length'
                ),
                'error1' => array(
                    'max_length' => 128,
                    'text' => 'error_city_max_length'
                )
            ),
            'autocomplete' => 'address-level2',
            'type' => 'text',
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 13,
            'style' => 'col',
            'class' => ''
        ),
        'postcode' => array(
            'id' => 'postcode',
            'text' => 'entry_postcode',
            'placeholder' => 'placeholder_postcode',
            'tooltip' => 'tooltip_postcode',
            'errors' => array(
                'error0' => array(
                    'min_length' => 2,
                    'text' => 'error_postcode_min_length'
                ),
                'error1' => array(
                    'max_length' => 10,
                    'text' => 'error_postcode_max_length'
                )
            ),
            'autocomplete' => 'postal-code',
            'type' => 'text',
            'refresh' => '3',
            'custom' => 0,
            'sort_order' => 14,
            'style' => 'col',
            'class' => '',
            'mask' => '',
        ),
        
        'country_id' => array(
            'id' => 'country_id',
            'text' => 'entry_country_id',
            'tooltip' => 'tooltip_country_id',
            'placeholder' => 'placeholder_country_id',
            'errors' => array(
                'error0' => array(
                    'not_empty' => true,
                    'text' => 'error_country_id_not_empty'
                )
            ),
            'type' => 'select',
            'search' => 0,
            'autocomplete' => 'country-name',
            'refresh' => '3',
            'custom' => 0,
            'sort_order' => 15,
            'style' => 'list',
            'class' => 'country'
        ),

        'zone_id' => array(
            'id' => 'zone_id',
            'text' => 'entry_zone_id',
            'tooltip' => 'tooltip_zone_id',
            'placeholder' => 'placeholder_zone_id',
            'errors' => array(
                'error0' => array(
                    'not_empty' => true,
                    'text' => 'error_zone_id_not_empty'
                )
            ),
            'autocomplete' => 'address-level1',
            'type' => 'select',
            'search' => 0,
            'refresh' => '3',
            'custom' => 0,
            'sort_order' => 16,
            'style' => 'list',
            'class' => 'zone'
        ),

        'newsletter' => array(
            'id' => 'newsletter',
            'text' => 'entry_newsletter',
            'tooltip' => 'placeholder_newsletter',
            'errors' => array(
                'error0' => array(
                    'checked' => true,
                    'text' => 'error_newsletter_checked'
                )
            ),
            'type' => 'checkbox',
            'custom' => 0,
            'class' => '',
            'refresh' => '0',
            'sort_order' => 17,
            'value' => '1'
        ),

        'shipping_address' => array(
            'id' => 'shipping_address',
            'text' => 'entry_shipping_address',
            'tooltip' => 'tooltip_shipping_address',
            'errors' => array(
                'error0' => array(
                    'checked' => true,
                    'text' => 'error_shipping_address_checked'
                )
            ),
            'type' => 'checkbox',
            'refresh' => '3',
            'custom' => 0,
            'sort_order' => 18,
            'class' => '',

        ),

        'agree' => array(
            'id' => 'agree',
            'text' => 'entry_agree',
            //'information_id' => $this->config->get('config_account_id'),
            'tooltip' => 'tooltip_agree',
            'errors' => array(
                'error0' => array(
                    'checked' => true,
                    'text' => 'error_agree_checked',
                    //'information_id' => $this->config->get('config_account_id')
                )
            ),
            'type' => 'checkbox',
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 19,
            'class' => ''
        )
    ),

    'account' => array(
        'guest' => array(
            'display' => 1,
            'fields' => array(
                'firstname' => array('display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'lastname' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'email' => array(
                    'display' => 1,
                    'require' => 1,
                    'errors' => array(
                        'error0' => array(
                            'min_length' => 3,
                            'text' => 'error_email_min_length'
                        ),
                        'error1' => array(
                            'max_length' => 96,
                            'text' => 'error_email_max_length'
                        ),
                        'error2' => array(
                            'regex' => '/^[^\@]+@.*\.[a-z]{2,6}$/i',
                            'text' => 'error_email_regex'
                        ),
                        'error3' => array(
                            'not_empty' => true,
                            'text' => 'error_email_not_empty'
                        )
                    ),
                    'value' => ''
                ),
                'email_confirm' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'heading' => array(
                    'display' => 1,
                    'value' => ''
                ),
                'telephone' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'fax' => array(
                    'display' => 0,
                    'require' => 0,
                    'value' => ''
                ),
                'company' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => ''
                ),
                'customer_group_id' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => ''
                ),
                'address_1' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'address_2' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => ''
                ),
                'city' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'postcode' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'country_id' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'zone_id' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'shipping_address' => array(
                    'display' => 1,
                    'value' => 1
                ),
                'agree' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => '0'
                )
            )
        ),
        'register' => array(
            'display' => 1,
            'fields' => array(
                'firstname' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'lastname' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'email' => array(
                    'display' => 1,
                    'require' => 1,
                    'errors' => array(
                        'error0' => array(
                            'min_length' => 3,
                            'text' => 'error_email_min_length'
                        ),
                        'error1' => array(
                            'max_length' => 96,
                            'text' => 'error_email_max_length'
                        ),
                        'error2' => array(
                            'regex' => '/^[^\@]+@.*\.[a-z]{2,6}$/i',
                            'text' => 'error_email_regex'
                        ),
                        'error3' => array(
                            'email_exists' => true,
                            'text' => 'error_email_email_exists'
                        ),
                        'error4' => array(
                            'not_empty' => true,
                            'text' => 'error_email_not_empty'
                        )
                    ),
                    'value' => ''
                ),
                'email_confirm' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'heading' => array(
                    'display' => 1,
                    'value' => ''
                ),
                'telephone' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'fax' => array(
                    'display' => 0,
                    'require' => 0,
                    'value' => ''
                ),
                'password' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'confirm' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'company' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => ''
                ),
                'customer_group_id' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => ''
                ),
                'address_1' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'address_2' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => ''
                ),
                'city' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'postcode' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'country_id' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'zone_id' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'newsletter' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => '0'
                ),
                'shipping_address' => array(
                    'display' => 1,
                    'value' => 1
                ),
                'agree' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => '0'
                )
            )
        ),
        'logged' => array(
            'display' => 1,
            'fields' => array(
                'firstname' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'lastname' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'company' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => ''
                ),
                'address_1' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'address_2' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => ''
                ),
                'city' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => ''
                ),
                'postcode' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'country_id' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'zone_id' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => ''
                ),
                'shipping_address' => array(
                    'display' => 1,
                    'value' => 0
                ),
                'address_id' => array()
            )
        )
    )
);