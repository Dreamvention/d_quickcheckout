<?php
namespace Opencart\Catalog\Model\Extension\AjaxQuickCheckout\AjaxQuickCheckout;

class Page extends \Opencart\System\Engine\Model {

    public function getActivePages(){
        $pages = array();
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

        if(isset($state['layout']['pages'])){
            foreach($state['layout']['pages'] as $page_id => $page){
                if(!$page['deleted']){
                    $pages[] = $page_id;
                }
            }
            
        }
        return $pages;
    }
}