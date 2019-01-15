<?php 

$_['d_quickcheckout_layout'] = array( 
    "codename" => 'default',
    "header_footer" => 1,
    "pages" => array( 
        "page0" => array(
            "id" => "page0",
            "deleted" => 0,
            "sort_order" => 0,
            "type" => "page",
            "display" => "0",
            "text" => "Checkout",
            "description" => "One page checkout",
            "children" => array( 
                "row0" => array(
                    "id" => "row0", 
                    "type" => "row", 
                    "sort_order" => 0,
                    "children" => array( 
                        "col0" => array(
                            "id" => "col0",
                            "size" => 4,
                            "type" => "col", 
                            "sort_order" => 0,
                            "children" => array( 
                                "child0" => array(
                                    "id" => "child0",
                                    "name" => "account",
                                    "type" => "item",
                                    "sort_order" => 0,
                                ),
                                "child1" => array( 
                                    "id" => "child1",
                                    "name" => "payment_address",
                                    "type" => "item",
                                    "sort_order" => 1,
                                ),
                                "child2" => array(
                                    "id" => "child2",
                                    "name" => "shipping_address",
                                    "type" => "item",
                                    "sort_order" => 2,
                                ) 
                            ) 
                        ),
                        "col1" => array(
                            "id" => "col1",
                            "size" => 8,
                            "type" => "col",
                            "sort_order" => 1,
                            "children" => array( 
                                "row1" => array(
                                    "id" => "row1",
                                    "type" => "row",
                                    "sort_order" => 0,
                                    "children" => array( 
                                        "col2" => array(
                                            "id" => "col2",
                                            "size" => 6,
                                            "type" => "col", 
                                            "sort_order" => 0,
                                            "children" => array( 
                                                "child6" => array( 
                                                    "id" => "child6",
                                                    "name" => "shipping_method",
                                                    "type" => "item",
                                                    "sort_order" => 0,
                                                )
                                            ) 
                                        ),
                                        "col3" => array( 
                                            "id" => "col3",
                                            "size" => 6,
                                            "type" => "col",
                                            "sort_order" => 1,
                                            "children" => array( 
                                                "child7" => array( 
                                                    "id" => "child7",
                                                    "name" => "payment_method",
                                                    "type" => "item",
                                                    "sort_order" => 1,
                                                )
                                            ) 
                                        ) 
                                    )
                                ),
                                "child3" => array(
                                    "id" => "child3",
                                    "name" => "cart",
                                    "type" => "item",
                                    "sort_order" => 1,
                                ),
                                "child4" => array(
                                    "id" => "child4",
                                    "name" => "custom",
                                    "type" => "item",
                                    "sort_order" => 2,
                                ),
                                "child8" => array( 
                                    "id" => "child8",
                                    "name" => "payment",
                                    "type" => "item",
                                    "sort_order" => 3,
                                ),
                                "child10" => array(
                                    "id" => "child10", 
                                    "name" => "confirm",
                                    "type" => "item",
                                    "sort_order" => 4,
                                ) 
                            ) 
                        ) 
                    ) 
                ),
                
            ) 
        )
    ) 
);