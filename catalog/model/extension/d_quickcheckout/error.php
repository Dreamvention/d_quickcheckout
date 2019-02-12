<?php
class ModelExtensionDQuickcheckoutError extends Model {
    private $types = array(
        'not_empty',
        'min_length',
        'max_length',
        'checked',
        'compare_to',
        'telephone',
        'email_exists',
        'regex'
    );

    public function clearStepErrors($step){
        $state = $this->model_extension_d_quickcheckout_store->getState();
        if(isset($state['errors'][$step])){
            foreach($state['errors'][$step] as $field_id => $field){
                $state['errors'][$step][$field_id] = '';
            }
            $this->model_extension_d_quickcheckout_store->setState($state);
        }
    }


    public function validateCheckout(){
    
        $steps = $this->model_extension_d_quickcheckout_store->getReceivers();
        foreach($steps as $step){
            $this->load->controller('extension/d_quickcheckout/'.$step.'/validate');
        }

        $state = $this->model_extension_d_quickcheckout_store->getState();

        $errors = array_filter($state['errors'], function ($var) {
            foreach($var as $key => $value){
                if($value){
                    return $var;
                }
            }
        });

        if(empty($errors)){
            return true;
        }

        return false;
    }

    public function isCheckoutValid(){
        $state = $this->model_extension_d_quickcheckout_store->getState();
        $errors = array_filter($state['errors'], function ($var) {
            foreach($var as $key => $value){
                if($value){
                    return $var;
                }
            }
        });
        if(empty($errors)){
            return true;
        }

        return false;
    }

    public function validatePage($page_id){
        //clear all errors first
        $state = $this->model_extension_d_quickcheckout_store->getState();
        foreach($state['errors'] as $step => $errors){
            foreach($errors as $field_id => $error){
                $state['errors'][$step][$field_id] = '';
            }
        }
        $this->model_extension_d_quickcheckout_store->setState($state);

        $steps = $this->getPageSteps($page_id);

        foreach($steps as $step){
            $this->load->controller('extension/d_quickcheckout/'.$step.'/validate');
        }

        $state = $this->model_extension_d_quickcheckout_store->getState();
        if($state['errors']){
            $errors = array_filter($state['errors'], function ($var) {
                foreach($var as $key => $value){
                    if($value){
                        return $var;
                    }
                }
            });
        }
        if(empty($errors)){
            return true;
        }
        return false;
    }

    
    
    public function validateField($step, $field_id, $value){
      $this->load->model('extension/d_quickcheckout/store');
      $state = $this->model_extension_d_quickcheckout_store->getState();
      if(!empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['require'])
      && !empty($state['config'][$state['session']['account']][$step]['fields'][$field_id]['errors'])
      ){

        $errors = $state['config'][$state['session']['account']][$step]['fields'][$field_id]['errors'];
        foreach($errors as $error){
          foreach($error as $validate => $rule){
            if(!$this->$validate($rule, $value)){
              $this->model_extension_d_quickcheckout_store->updateState(array('errors', $step, $field_id), $error['text']);

              return false;
            }
          }
        }
      }

      $this->model_extension_d_quickcheckout_store->updateState(array('errors', $step, $field_id), '');

      return true;
    }

    

    private function getPageSteps($page_id){
        $state = $this->model_extension_d_quickcheckout_store->getState();
        return $this->array_flatten($state['layout']['pages'][$page_id]['children']);
    }

    private function array_flatten($array){

        $return = array();

        foreach ($array as $key => $value) {
            if (isset($value['type']) && $value['type'] == 'item') {
                $return[] = $value['name'];
            } elseif (!empty($value['children'])) {
                $return = array_merge($return, $this->array_flatten($value['children']));
            }
        }
        return $return;

    }

    public function not_empty($rule, $value){
        return (strlen($value) > 0);
    }

    public function min_length($rule, $value){
        return (strlen($value) >= $rule);
    }

    public function max_length($rule, $value){
        return (strlen($value) <= $rule);
    }

    public function checked($rule, $value){
        return ($value);
    }

    public function compare_to($rule, $value){
        $parts = explode('.', $rule);

        return ($this->session->data[$parts[0]][$parts[1]] == $value);
    }

    public function telephone($rule, $value){
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $result = true;
        try {
            if(!empty($value)){
                $swissNumberProto = $phoneUtil->parse($value);
                $result = $phoneUtil->isValidNumber($swissNumberProto);
            }else{
                $result = false;
            }
        } catch (\libphonenumber\NumberParseException $e) {
            $result = false;
        }

        return $result;
    }

    public function email_exists($rule, $value){
        $this->load->model('account/customer');

        if($this->model_account_customer->getTotalCustomersByEmail($value) > 0){
            return false;
        }
        return true;
    }

    public function regex($rule, $value){
        return (preg_match($rule, $value));
    }

    public function text($text, $value){
        return $text;
    }

    public function getErrorTypes(){
        return $this->types;
    }
}