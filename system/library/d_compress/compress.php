<?php

    $unix_timestamp = time();  //get unix time stamp added to all file names
    if (defined('DIR_CATALOG')) {
        $adir = DIR_CATALOG . 'view/javascript/d_quickcheckout/compress';    //directory to store output, must not be one of the source directories
    } else {
        $adir = DIR_APPLICATION . 'view/javascript/d_quickcheckout/compress';
    }
    require_once(DIR_SYSTEM . 'library/d_compress/jsmin.php');  //load javascript minifier	
    require_once(DIR_SYSTEM . 'library/d_compress/cssmin.php');  //load css minifier	
    //		Create target directory and write Apache htaccess file when no directory found 
    if (!file_exists($adir)) {
        mkdir($adir) && chmod($adir, 0777);
        $htaccess = 'Options All -Indexes' . PHP_EOL;
        $htaccess.='AddType text/css cssgz' . PHP_EOL;
        $htaccess.='AddType text/javascript jsgz' . PHP_EOL;
        $htaccess.='AddEncoding x-gzip .cssgz .jsgz' . PHP_EOL;
        $htaccess.='# for all files in min directory' . PHP_EOL;
        $htaccess.='FileETag None' . PHP_EOL;
        $htaccess.='# Cache for a week, attempt to always use local copy' . PHP_EOL;
        $htaccess.='<IfModule mod_expires.c>' . PHP_EOL;
        $htaccess.='  ExpiresActive On' . PHP_EOL;
        $htaccess.='  ExpiresDefault A604800' . PHP_EOL;
        $htaccess.='</IfModule>' . PHP_EOL;
        $htaccess.='<IfModule mod_headers.c>' . PHP_EOL;
        $htaccess.='  Header unset ETag' . PHP_EOL;
        $htaccess.='  Header set Cache-Control "max-age=604800, public"' . PHP_EOL;
        $htaccess.='</IfModule>' . PHP_EOL;
        file_put_contents($adir . '/.htaccess', $htaccess);   //write initial htaccess file
    }
    $scripts = array();

    if (defined('DIR_CATALOG')) {
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/library/backbone-nested/backbone-nested.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/main.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/engine/model.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/engine/view.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/model/login.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/view/login.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/view/field.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/model/payment_address.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/view/payment_address.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/model/shipping_address.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/view/shipping_address.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/model/shipping_method.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/view/shipping_method.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/model/payment_method.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/view/payment_method.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/model/cart.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/view/cart.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/model/payment.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/view/payment.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/model/confirm.js';
        $scripts[] = DIR_CATALOG . 'view/javascript/d_quickcheckout/view/confirm.js';  //directory to store output, must not be one of the source directories
    } else {
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/library/backbone-nested/backbone-nested.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/main.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/engine/model.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/engine/view.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/model/login.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/view/login.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/view/field.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/model/payment_address.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/view/payment_address.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/model/shipping_address.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/view/shipping_address.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/model/shipping_method.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/view/shipping_method.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/model/payment_method.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/view/payment_method.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/model/cart.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/view/cart.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/model/payment.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/view/payment.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/model/confirm.js';
        $scripts[] = DIR_APPLICATION . 'view/javascript/d_quickcheckout/view/confirm.js';
    }



    $result = file_compress('d_quickcheckout.js', $scripts, $adir);

    sleep(5);

    $unix_timestamp.='';  //make a string

    function file_compress($file_name, $file_input, $adir) {
        global $unix_timestamp;
        $pos = strrpos($file_name, '.');    //get last . in file name
        if ($pos == false)
            die('illogical response from strrpos');
        $fn = substr($file_name, 0, $pos) . '.min' . substr($file_name, $pos); //put timestamp into file name
        $fl = null;      //clear file data variable
        foreach ($file_input as $value)    //merge files in the group
            $fl.= file_get_contents($value) . ' ';
        $len_orig = strlen($fl);
        if (strtolower(substr($file_name, $pos + 1, 2)) == 'js')
            $fl = JSMin::minify($fl);   //minify js	
        else
            $fl = CssMin::minify($fl);   //minify css

        $len_minify = strlen($fl);

        //		write files no need to lock, filename is unique and not yet in use
        return file_put_contents($adir . '/' . $fn, $fl);
    }
    