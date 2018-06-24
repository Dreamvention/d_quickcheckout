<?php
class ModelExtensionDQuickcheckoutPage extends Model {

    public function getActivePages(){
        $pages = array();
        $state = $this->model_extension_d_quickcheckout_store->getState();

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