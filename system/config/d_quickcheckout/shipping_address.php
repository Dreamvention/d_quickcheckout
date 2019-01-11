<?php 
$_['d_quickcheckout_shipping_address_language'] = array(
    'heading_title' => 'text_shipping_address',
    'description_shipping_address' => '',

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

    'entry_company' => 'entry_company',
    'placeholder_company' => 'entry_company',
    'tooltip_company' => '',
    'error_company_min_length' => 'error_company_length',
    'error_company_max_length' => 'error_company_length',

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

    'text_address_style' => 'text_address_style'

);
$_['d_quickcheckout_shipping_address'] = array(
    'id' => 'shipping_address',
    'icon' => '', //'fa fa-book',
    'text' => 'heading_title',
    'description' => 'description_shipping_address',
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
            'sort_order' => 1,
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
            'sort_order' => 2,
            'style' => 'col',
            'class' => '',
            'mask' => '',
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
                    'max_length' => 32,
                    'text' => 'error_company_max_length'
                )
            ),
            'autocomplete' => 'organization',
            'type' => 'text',
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 3,
            'style' => 'row',
            'class' => '',
            'mask' => '',
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
            'sort_order' => 4,
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
            'sort_order' => 5,
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
            'sort_order' => 6,
            'style' => 'col',
            'class' => '',
            'mask' => '',
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
            'refresh' => '0',
            'custom' => 0,
            'sort_order' => 7,
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
            'autocomplete' => 'country-name',
            'type' => 'select',
            'search' => 0,
            'refresh' => '4',
            'custom' => 0,
            'sort_order' => 8,
            'style' => 'list',
            'class' => '',
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
            'refresh' => '4',
            'custom' => 0,
            'sort_order' => 9,
            'style' => 'list',
            'class' => ''
        )
    ),
    'account' => array(
        'guest' => array(
            'display' => 1,
            'require' => 0,
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
                )
            )
        ),
        'register' => array(
            'display' => 1,
            'require' => 0,
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
                )
            )
        ),
        'logged' => array(
            'display' => 1,
            'require' => 0,
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
                )
            )
        )
    )
);