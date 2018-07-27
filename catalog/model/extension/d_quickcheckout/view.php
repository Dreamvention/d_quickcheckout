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
                $result[] = 'catalog/view/theme/'.$this->config->get('config_template').'/template/extension/d_quickcheckout/'.basename(dirname($file)).'/'.basename($file);
            }else{
                $result[] = 'catalog/view/theme/default/template/extension/d_quickcheckout/'.basename(dirname($file)).'/'.basename($file);
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

        $browser = new d_browser();
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
