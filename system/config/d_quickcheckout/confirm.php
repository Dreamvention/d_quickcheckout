<?php
$_['d_quickcheckout_confirm_language'] = array(
    'heading_title' => 'text_confirm',
    'description_confirm' => '',
    'text_trigger' => 'text_trigger',
    'button_confirm' => 'button_confirm'
);
$_['d_quickcheckout_confirm'] = array(
    'id' => 'confirm',
    'icon' => '',
    'text' => 'heading_title',
    'description' => 'description_confirm',
    'trigger' => '#button-confirm, .button, .btn, .button_oc, input[type=submit]',
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