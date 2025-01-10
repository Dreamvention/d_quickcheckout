<?php
namespace Opencart\Catalog\Model\Extension\AjaxQuickCheckout\Utils;

class Error extends \Opencart\System\Engine\Model {
    public function customErrorHandler(string $code, string $message, string $file, string $line): bool {
		switch ($code) {
			case E_NOTICE:
			case E_USER_NOTICE:
				$error = 'Notice';
				break;
			case E_WARNING:
			case E_USER_WARNING:
				$error = 'Warning';
				break;
            case E_DEPRECATED:
                $error = 'Warning';
				break;
            case E_USER_DEPRECATED:
                $error = 'Warning';
				break;
            case E_STRICT:
                $error = 'Warning';
				break;
			case E_ERROR:
			case E_USER_ERROR:
				$error = 'Fatal Error';
				break;
			default:
				$error = 'Unknown';
				break;
		}

        if ($this->config->get('config_error_log') || $error == 'Notice' || $error == 'Warning') {
			$this->log->write('PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line);
		}

		if ($this->config->get('config_error_display')) {
			echo '<b>' . $error . '</b>: ' . $message . ' in <b>' . $file . '</b> on line <b>' . $line . '</b>';
		} else if ($error != 'Notice' && $error != 'Warning') {
			header('Location: ' . $this->config->get('error_page'));
			exit();
		}
	
		return true;
	}

    public function fatal_error_shutdown_handler()
    {
        $last_error = error_get_last();
        if ($last_error && $last_error['type'] === E_ERROR) {
            $this->customErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
        }
    }
}