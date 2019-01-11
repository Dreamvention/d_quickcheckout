<?php
$_['d_quickcheckout_custom_language'] = array(
    'heading_title' => 'text_custom',
    'description_custom' => '',

    'entry_comment' => 'text_comments',
    'placeholder_comment' => 'text_comments',
    'tooltip_comment' => '',
    'error_comment_not_empty' => 'error_comment_not_empty',

    'entry_agree' => 'text_agree',
    'tooltip_agree' => '',
    'error_agree_checked' => 'error_agree',
    );
$_['d_quickcheckout_custom'] = array(
    'id' => 'custom',
    'icon' => '',
    'text' => 'heading_title',
    'description' => 'description_custom',
    'style' => 'card',
    'fields' => array(
        'comment' => array(
            'id' => 'comment',
            'text' => 'entry_comment',
            'tooltip' => 'tooltip_comment',
            'placeholder' => 'placeholder_comment',
            'errors' => array(
                'error0' => array(
                    'not_empty' => true,
                    'text' => 'error_comment_not_empty'
                )
            ),
            'type' => 'textarea',
            'refresh' => '0',
            'custom' => 0,
            'class' => '',
            'sort_order' => 0,
            'style' => 'row',
            'value' => '',
        ),
        'agree' => array(
            'id' => 'agree',
            'text' => 'entry_agree',
            'tooltip' => 'tooltip_agree',
            //'information_id' => $this->config->get('config_checkout_id'),
            'errors' => array(
                'error0' => array(
                    'checked' => true,
                    'text' => 'error_agree_checked',
                    //'information_id' => $this->config->get('config_checkout_id')
                )
            ),
            'type' => 'checkbox',
            'refresh' => '0',
            'value' => '0',
            'custom' => 0,
            'class' => '',
            'sort_order' => 1,
            'style' => 'row',
        )
    ),
    'account' => array(
        'guest' => array(
            'display' => 1,
            'fields' => array(
                'comment' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => '',
                ),
                'agree' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => '0',
                )
            )
        ),
        'register' => array(
            'display' => 1,
            'fields' => array(
                'comment' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => '',
                ),
                'agree' => array(
                    'display' => 1,
                    'require' => 1,
                    'value' => '0',
                )
            )
        ),
        'logged' => array(
            'display' => 1,
            'fields' => array(
                'comment' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => '',
                ),
                'agree' => array(
                    'display' => 1,
                    'require' => 0,
                    'value' => '0',
                )
            )
        )
    )
);
