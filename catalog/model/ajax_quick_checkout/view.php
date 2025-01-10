<?php
namespace Opencart\Catalog\Model\Extension\AjaxQuickCheckout\AjaxQuickCheckout;

class View extends \Opencart\System\Engine\Model {
    public $tags = array();

    public function template($template) {
        if(VERSION >= '2.2.0.0'){
            return $template;
        }elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/'.$template.'.tpl')) {
            return $this->config->get('config_template') . '/template/'.$template.'.tpl';
        } else {
            return 'default/template/'.$template.'.tpl';
        }
    }

    public function getRiotTags(){
        $result = array();

        $files = glob(DIR_EXTENSION . 'ajax_quick_checkout/catalog/view/template/ajax_quick_checkout/*/*.tag', GLOB_BRACE);
        foreach($files as $file){
            if(file_exists(DIR_EXTENSION . 'ajax_quick_checkout/catalog/view/template/ajax_quick_checkout/'.basename(dirname($file)).'/'.basename($file))){
                $result[] = '/extension/ajax_quick_checkout/catalog/view/template/ajax_quick_checkout/'.basename(dirname($file)).'/'.basename($file);
            }
        }

        if(file_exists(DIR_EXTENSION.'ajax_quick_checkout_pro/install.json')){
            $files = glob(DIR_EXTENSION . 'ajax_quick_checkout_pro/catalog/view/template/ajax_quick_checkout/*/*.tag', GLOB_BRACE);
            foreach($files as $file){
                if(file_exists(DIR_EXTENSION . 'ajax_quick_checkout_pro/catalog/view/template/ajax_quick_checkout/'.basename(dirname($file)).'/'.basename($file))){
                    $result[] = '/extension/ajax_quick_checkout_pro/catalog/view/template/ajax_quick_checkout/'.basename(dirname($file)).'/'.basename($file);
                }
            }
        }

        return $result;
    }

    public function language($text){
        if(is_array($text)){
          if(isset($text[$this->config->get('config_language_id')])){
            return $text[$this->config->get('config_language_id')];
          }else{
            return array_shift(array_values($text));
          }
        }else{
          return $this->language->get($text);
        }

        return $text;
    }


    public function browserSupported(){
        $browser = new \Opencart\System\Library\Extension\AjaxQuickCheckout\DvBrowser();
        $supports = array(
            'Internet Explorer' => 8
        );

        $name = $browser->getBrowser();
        $version = $browser->getVersion();
        if(array_key_exists($name,$supports)){
            if($supports[$name] > $version){
                return false;
            }
        }

        return true;

    }
}
