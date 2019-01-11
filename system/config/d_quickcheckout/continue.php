<?php
$_['d_quickcheckout_continue_language'] = array(
    'heading_title' => 'text_continue',
    'description_continue' => '',
    'text_prev' => 'button_back',
    'text_next' => 'button_continue',
    'entry_button_prev' => 'entry_button_prev',
    'entry_button_next' => 'entry_button_next'
);
$_['d_quickcheckout_continue'] = array(
    'id' => 'continue',
    'icon' => '',
    'text' => 'heading_title',
    'description' => 'description_continue',
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
                )
            )
        )
    )
);