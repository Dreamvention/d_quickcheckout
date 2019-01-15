<?php
$_['d_quickcheckout_cart_language'] = array(
    'heading_title' => 'text_cart',
    'description_cart' => '',

    'entry_voucher' => 'entry_voucher',
    'placeholder_voucher' => '',
    'entry_coupon' => 'entry_coupon',
    'placeholder_coupon' => '',
    'entry_reward' => 'entry_reward',
    'placeholder_reward' => '',

    'entry_image' => 'column_image',
    'entry_name' => 'column_name',
    'entry_model' => 'column_model',
    'entry_quantity' => 'column_quantity',
    'entry_price' => 'column_price',
    'entry_total' => 'column_total',

    'button_apply' => 'Apply'
);
$_['d_quickcheckout_cart'] = array(
    'id' => 'cart',
    'icon' => '', //'fa fa-shopping-cart',
    'text' => 'heading_title',
    'description' => 'description_cart',
    'display_text' => 1,
    'display_description' => 1,
    'image_size' => array('width' => 180, 'height' => 180),
    'thumb_size' => array('width' => 60, 'height' => 60),
    'min_total' => 0,
    'min_quantity' => 0,
    'style' => 'card',
    'option' => array(
        'voucher' => array(
            'id' => 'voucher',
            'text' => 'entry_voucher',
            'placeholder' => 'placeholder_voucher',
            'type' => 'text',
            'refresh' => '3',
            'custom' => 0,
            'class' => ''
        ),
        'coupon' => array(
            'id' => 'coupon',
            'text' => 'entry_coupon',
            'placeholder' => 'placeholder_coupon',
            'type' => 'text',
            'refresh' => '3',
            'custom' => 0,
            'class' => ''
        ),
        'reward' => array(
            'id' => 'reward',
            'text' => 'entry_reward',
            'placeholder' => 'placeholder_rewards',
            'type' => 'text',
            'refresh' => '3',
            'custom' => 0,
            'class' => ''
        )
    ),
    'columns' => array(
        'image' => array(
            'text' => 'entry_image'
        ),
        'name' => array(
            'text' => 'entry_name'
        ),
        'model' => array(
            'text' => 'entry_model'
        ),
        'quantity' => array(
            'text' => 'entry_quantity'
        ),
        'price' => array(
            'text' => 'entry_price'
        ),
        'total' => array(
            'text' => 'entry_total'
        )
    ),
    'account' => array(
        'guest' => array(
            'display' => 1,
            'option' => array(
                'voucher' => array(
                    'display' => 1
                ),
                'coupon' => array(
                    'display' => 1
                ),
                'reward' => array(
                    
                )
            ),
            'columns' => array(
                'image' => array(
                    'display' => 1
                ),
                'name' => array(
                    'display' => 1
                ),
                'model' => array(
                    'display' => 0
                ),
                'quantity' => array(
                    'display' => 1
                ),
                'price' => array(
                    'display' => 1
                ),
                'total' => array(
                    'display' => 1
                )
            )
        ),
        'register' => array(
            'display' => 1,
            'option' => array(
                'voucher' => array(
                    'display' => 1
                ),
                'coupon' => array(
                    'display' => 1
                ),
                'reward' => array(
                    
                )
            ),
            'columns' => array(
                'image' => array(
                    'display' => 1
                ),
                'name' => array(
                    'display' => 1
                ),
                'model' => array(
                    'display' => 0
                ),
                'quantity' => array(
                    'display' => 1
                ),
                'price' => array(
                    'display' => 1
                ),
                'total' => array(
                    'display' => 1
                )
            )
        ),
        'logged' => array(
            'display' => 1,
            'option' => array(
                'voucher' => array(
                    'display' => 1
                ),
                'coupon' => array(
                    'display' => 1
                ),
                'reward' => array(
                    'display' => 1
                ),
            ),
            'columns' => array(
                'image' => array(
                    'display' => 1
                ),
                'name' => array(
                    'display' => 1
                ),
                'model' => array(
                    'display' => 0
                ),
                'quantity' => array(
                    'display' => 1
                ),
                'price' => array(
                    'display' => 1
                ),
                'total' => array(
                    'display' => 1
                )
            )
        )
    )
);