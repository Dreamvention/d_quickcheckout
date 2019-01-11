<?php
$_['d_quickcheckout_payment_method_language'] = array(
    'heading_title' => 'text_payment_method',
    'description_payment_method' => '',
    'text_display_images' => 'text_display_images',
    'text_display_options' => 'text_display_options',
    'text_input_style' => 'text_input_style'
);
$_['d_quickcheckout_payment_method'] = array(
    'id' => 'payment_method',
    'icon' => '', //'fa fa-credit-card',
    'text' => 'heading_title',
    'description' => 'description_payment_method',
    'display_options' => 1,
    'display_images' => 1,
    'input_style' => 'radio',
    'default_option' => 'bank_transfer',
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