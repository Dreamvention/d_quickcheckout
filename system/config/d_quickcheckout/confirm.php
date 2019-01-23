<?php
$_['d_quickcheckout_confirm_language'] = array(
    'heading_title' => 'text_confirm',
    'description_confirm' => '',
    'text_trigger' => 'text_trigger',
    'text_confirm' => 'text_confirm',
    'button_confirm' => 'button_confirm',
    'text_prev' => 'button_back',
    'text_next' => 'button_continue',
    'entry_button_prev' => 'entry_button_prev',
    'entry_button_next' => 'entry_button_next'
);
$_['d_quickcheckout_confirm'] = array(
    'id' => 'confirm',
    'icon' => '',
    'text' => 'heading_title',
    'description' => 'description_confirm',
    'trigger' => '#button-confirm, .button, .btn, .button_oc, input[type=submit]',
    'style' => 'clear',
    'account' => array(
        'guest' => array(
            'display' => 1,
            'buttons' => array(
                'prev' => array(
                    'display' => 0,
                ),
                'next' => array(
                    'display' => 1,
                ),
                'conforim' => array(
                    'display' => 1,
                ) 
            )
        ),
        'register' => array(
            'display' => 1,
            'buttons' => array(
                'prev' => array(
                    'display' => 0,
                ),
                'next' => array(
                    'display' => 1,
                ),
                'conforim' => array(
                    'display' => 1,
                ) 
            )
        ),
        'logged' => array(
            'display' => 1,
            'buttons' => array(
                'prev' => array(
                    'display' => 0,
                ),
                'next' => array(
                    'display' => 1,
                ),
                'conforim' => array(
                    'display' => 1,
                ) 
            )
        )
    )
);