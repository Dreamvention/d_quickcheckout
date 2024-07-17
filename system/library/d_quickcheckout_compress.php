<?php

class d_quickcheckout_compress {

    public function compress() {

        if (defined('DIR_CATALOG')) {
            $adir = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/compress';
            if (!is_dir($adir)) {
                mkdir($adir);
            }
            chmod($adir, 0777);    //directory to store output, must not be one of the source directories
        }
        	
        //		Create target directory and write Apache htaccess file when no directory found 
        
        $scripts = array();

        $result = false;

        if (defined('DIR_CATALOG')) {
             
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/main.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/component/setting.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/component/page.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/component/row.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/component/col.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/component/step.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/component/field.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/component/error.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/step/account.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/step/payment_address.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/step/shipping_address.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/step/custom.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/step/shipping_method.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/step/payment_method.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/step/cart.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/step/confirm.js';
            $scripts[] = DIR_CATALOG . 'view/theme/default/javascript/d_quickcheckout/step/payment.js';
            
            $result = $this->file_compress('d_quickcheckout.min.js', $scripts, $adir);
        }
        

        return $result;
    }

    public function file_compress($file_name, $file_input, $adir) {
        global $unix_timestamp;
        $pos = strrpos($file_name, '.');    //get last . in file name
        if ($pos == false)
            die('illogical response from strrpos');
        $fn = $file_name; //put timestamp into file name
        $fl = null;      //clear file data variable
        foreach ($file_input as $value)    //merge files in the group
            $fl.= file_get_contents($value) . ' ';
        $len_orig = strlen($fl);
        if (strtolower(substr($file_name, $pos + 1, 2)) == 'js')
            $fl = \d_compress\JSMin::minify($fl);   //minify js	
        else
            $fl = \d_compress\CssMin::minify($fl);   //minify css

        $len_minify = strlen($fl);

        //		write files no need to lock, filename is unique and not yet in use
        return file_put_contents($adir . '/' . $fn, $fl);
    }
}