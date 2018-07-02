<?php
class ModelExtensionDQuickcheckoutView extends Model {
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

        $files = glob(DIR_APPLICATION . 'view/theme/default/template/extension/d_quickcheckout/*/*.tag', GLOB_BRACE);
        foreach($files as $file){

            if(file_exists('catalog/view/theme/'.$this->config->get('config_template').'/template/extension/d_quickcheckout/'.basename(dirname($file)).'/'.basename($file))){
                $result[] = 'catalog/view/theme/'.$this->config->get('config_template').'/template/extension/d_quickcheckout/'.basename(dirname($file)).'/'.basename($file).'?'.rand();
            }else{
                $result[] = 'catalog/view/theme/default/template/extension/d_quickcheckout/'.basename(dirname($file)).'/'.basename($file).'?'.rand();
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
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']); 
        if (strpos($userAgent,'Edge') != false ){ 
            $name = 'edge';
        } 
        elseif ((substr($_SERVER['HTTP_USER_AGENT'],0,6)=="Opera/") || (strpos($userAgent,'opera')) != false ){ 
            $name = 'opera';
        } 
        elseif ((strpos($userAgent,'chrome')) != false) { 
            $name = 'chrome'; 
        } 
        elseif ((strpos($userAgent,'safari')) != false && (strpos($userAgent,'chrome')) == false && (strpos($userAgent,'chrome')) == false){ 
            $name = 'safari'; 
        } 
        elseif (preg_match('/msie/', $userAgent)) { 
            $name = 'msie'; 
        } 
        elseif ((strpos($userAgent,'firefox')) != false) { 
            $name = 'firefox'; 
        } 
        else { 
            $name = 'unrecognized'; 
        } 

        if (preg_match('/.+(?:me|ox|ge|it|on|ra|ie)[\/: ]([\d.]+)/', $userAgent, $matches) && $name=='safari' ) { 
            $version = $matches[1]; 
        }
        elseif (preg_match('/.+(?:me|ox|ge|it|on|ra|ie)[\/: ]([\d.]+)/', $userAgent, $matches) && $name!='safari' ) { 
            $version = $matches[1]; 
        }
        else { 
            $version = 'unknown'; 
        } 

        $supports = array(
            'edge' => 14,
            'chrome' => 67,
            'safari' => 7,
            'msie' => 9
        );

        if(array_key_exists($name,$supports)){
            if($supports[$name] > $version){
                return false;
            }
        }

        return true;
        
    }
}
