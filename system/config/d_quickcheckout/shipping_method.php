<?php
$_['d_quickcheckout_shipping_method_language'] = array(
    'heading_title' => 'text_shipping_method',
    'description_shipping_method' => 'description_shipping_method',
    'text_display_group_title' => 'text_display_group_title',
    'text_display_options' => 'text_display_options',
    'text_input_style' => 'text_input_style',
    'error_no_shipping' => 'error_no_shipping'
);
$_['d_quickcheckout_shipping_method'] = array(
    'id' => 'shipping_method',
    'icon' => '', //'fa fa-truck',
    'text' => 'heading_title',
    'description' => 'description_shipping_method',
    'input_style' => 'radio',
    'display' => 1,
    'display_group_title' => 1,
    'display_desciption' => 1,
    'display_options' => 1,
    'default_option' => '',
    'style' => 'card',
    'account' => array(
        'guest' => array(
            'display' => 1
        ),
        'register' => array(
            'display' => 1
        ),
        'logged' => array(
            'display' => 1
        )
    )
);