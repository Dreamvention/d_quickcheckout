<?php
namespace Opencart\Catalog\Model\Extension\AjaxQuickCheckout\Module;

class AjaxQuickCheckout extends \Opencart\System\Engine\Model {

    public function loadDependencies() {
        if (!isset($this->autoloader)) {
            $this->autoload = new \Opencart\System\Engine\Autoloader();
        }
        $extension = json_decode(file_get_contents(DIR_EXTENSION . "ajax_quick_checkout/install.json"), true);
        if (!empty($extension['dependencies'])) {
            foreach (array_keys($extension['dependencies']) as $dependency) {
                if (is_file(DIR_EXTENSION . $dependency . "/install.json")) {
                    $namespace = str_replace(['_', '/'], ['', '\\'], ucwords($dependency, '_/'));
                    // Register controllers, models and system extension folders
                    $this->autoloader->register('Opencart\Catalog\Controller\Extension\\' . $namespace, DIR_EXTENSION . $dependency . '/catalog/controller/');
                    $this->autoloader->register('Opencart\Catalog\Model\Extension\\' . $namespace, DIR_EXTENSION . $dependency . '/catalog/model/');
                    $this->autoloader->register('Opencart\System\Extension\\' . $namespace, DIR_EXTENSION . $dependency . '/system/');
                    $this->autoloader->register('Opencart\System\Library\Extension\\' . $namespace, DIR_EXTENSION . $dependency . '/system/library/');

                    // Template directory
                    $this->template->addPath('extension/' . $dependency, DIR_EXTENSION . $dependency . '/catalog/view/template/');

                    // Language directory
                    $this->language->addPath('extension/' . $dependency, DIR_EXTENSION . $dependency . '/catalog/language/');

                    // Config directory
                    $this->config->addPath('extension/' . $dependency, DIR_EXTENSION . $dependency . '/system/config/');
                }
                
            }
        }
    }
}
