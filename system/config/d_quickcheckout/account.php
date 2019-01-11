<?php
$_['d_quickcheckout_account_language'] = array(
    'heading_title' => 'text_account',
    'description_account' => '',

    'entry_guest' => 'text_guest',
    'desciption_guest' => '',

    'entry_register' => 'text_register',
    'desciption_register' => 'text_register_account',

    
    'entry_login' => 'button_login',
    'desciption_login' => '',

    'entry_email' => 'entry_email',
    'placeholder_email' => '',
    'tooltip_email' => '',

    'entry_password' => 'entry_password',
    'tooltip_password' => '',

    'button_login' => 'button_login',
    'text_popup' => 'text_popup',

    'text_social_login' => 'text_social_login'
);
$_['d_quickcheckout_account'] = array(
    'id' => 'account',
    'icon' => '', //'fa fa-user',
    'text' => 'heading_title',
    'description' => 'description_account',
    'default_option' => 'guest',
    'login_popup' => 1,
    'style' => 'card',
    'social_login' => array(
        'value' => 1,
        'display' => 1
    ),
    'option' => array(
        'guest' => array(
            'text' => 'entry_guest',
            'description' => 'desciption_guest',
            'display' => 1
        ),
        'register' => array(
            'text' => 'entry_register',
            'description' => 'desciption_register',
            'display' => 1
        ),
        'login' => array(
            'text' => 'entry_login',
            'description' => 'desciption_login',
            'display' => 1,
            'fields' => array(
                'email' => array(
                    'id' => 'email',
                    'text' => 'entry_email',
                    'placeholder' => 'placeholder_email',
                    'tooltip' => 'tooltip_email',
                    'value' => ''
                ),
                'password' => array(
                    'id' => 'email',
                    'text' => 'entry_password',
                    'tooltip' => 'tooltip_password',
                    'value' => ''
                ),
            ),
        )
    ),
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