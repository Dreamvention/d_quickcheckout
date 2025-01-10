<?php
namespace Opencart\Catalog\Controller\Extension\AjaxQuickCheckout\AjaxQuickCheckout;

class Custom extends \Opencart\System\Engine\Controller
{
    private $route = "ajax_quick_checkout/custom";

    public $action = ["custom/update"];

    private $pro = "";

    public function __construct($registry)
    {
        parent::__construct($registry);

        if (is_file(DIR_EXTENSION . "ajax_quick_checkout_pro/install.json")) {
            $this->pro .= "_pro";
        }

        $this->config->addPath(
            DIR_EXTENSION .
                "ajax_quick_checkout" .
                $this->pro .
                "/system/config/"
        );

        $this->load->model(
            "extension/ajax_quick_checkout/module/ajax_quick_checkout"
        );
        $this->model_extension_ajax_quick_checkout_module_ajax_quick_checkout->loadDependencies();
        $this->load->model(
            "extension/ajax_quick_checkout/ajax_quick_checkout/store"
        );
        $this->load->model(
            "extension/ajax_quick_checkout/ajax_quick_checkout/address"
        );
        $this->load->model(
            "extension/ajax_quick_checkout/ajax_quick_checkout/account"
        );
    }
    /**
     * Initialization
     */
    public function index()
    {
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();

        //set default values
        $state["config"] = $this->getConfig();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState(
            $state
        );
        $state["session"]["custom"] = $this->getDefault();
        //opencart fix
        $state["session"]["comment"] = $state["session"]["custom"]["comment"];

        $state["language"]["custom"] = $this->getLanguages();
        $state["action"]["custom"] = $this->action;
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState(
            $state
        );
    }

    /**
     * update via ajax
     */
    public function update()
    {
        $rawData = file_get_contents("php://input");
        $post = json_decode($rawData, true);
        if (!$post) {
            $post = $this->request->post;
        }
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->loadState();
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch(
            "custom/update/before",
            $post
        );
        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch(
            "custom/update",
            $post
        );

        $data = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getStateUpdated();

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($data));
    }

    /**
     * Receiver
     * Receiver listens to dispatch of events and accepts data array with action and state
     */
    public function receiver($data)
    {
        $update = false;

        //updating payment_address field values
        if ($data["action"] == "custom/update") {
            if (!empty($data["data"]["session"]["custom"])) {
                foreach (
                    $data["data"]["session"]["custom"]
                    as $field => $value
                ) {
                    $this->updateField($field, $value);
                    $update = true;
                }
            }
            //REFACTOR - added other data like config and layout
            if (
                !empty($data["data"]["config"]) ||
                !empty($data["data"]["layout"])
            ) {
                $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState(
                    $data["data"]
                );
            }
        }

        if ($update) {
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->dispatch(
                "custom/update/after",
                $data
            );
        }
    }

    public function validate()
    {
        $this->load->model(
            "extension/ajax_quick_checkout/ajax_quick_checkout/error"
        );
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        $step = "custom";
        $result = true;

        if (!$state["config"][$state["session"]["account"]][$step]["display"]) {
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_error->clearStepErrors(
                $step
            );
            return $result;
        }

        foreach ($state["session"]["custom"] as $field_id => $value) {
            if (
                !empty(
                    $state["config"][$state["session"]["account"]][$step][
                        "fields"
                    ][$field_id]["display"]
                ) &&
                !empty(
                    $state["config"][$state["session"]["account"]][$step][
                        "fields"
                    ][$field_id]["require"]
                ) &&
                !empty(
                    $state["config"][$state["session"]["account"]][$step][
                        "fields"
                    ][$field_id]["errors"]
                )
            ) {
                $errors =
                    $state["config"][$state["session"]["account"]][$step][
                        "fields"
                    ][$field_id]["errors"];
                $no_errors = true;
                foreach ($errors as $error) {
                    if (is_array($error)) {
                        foreach ($error as $validate => $rule) {
                            if (
                                !$this->model_extension_ajax_quick_checkout_ajax_quick_checkout_error->$validate(
                                    $rule,
                                    $value
                                )
                            ) {
                                $state["errors"][$step][
                                    $field_id
                                ] = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_error->text(
                                    $error["text"],
                                    $value
                                );
                                $result = false;
                                $no_errors = false;
                                break;
                            }
                        }
                    }
                    if ($no_errors) {
                        $state["errors"][$step][$field_id] = "";
                    }
                }
            } else {
                $state["errors"][$step][$field_id] = "";
            }
        }

        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState(
            $state
        );

        return $result;
    }

    /**
     * logic for updating fields
     */
    private function updateField($field, $value)
    {
        $state["session"]["custom"][$field] = $value;
        if ($this->validateField($field, $value)) {
            switch ($field) {
                case "comment":
                    $state["session"]["comment"] = $value;
                    break;

                default:
                    if (
                        isset(
                            $state["config"]["guest"]["custom"]["fields"][
                                $field
                            ]
                        )
                    ) {
                        if (
                            $state["config"]["guest"]["custom"]["fields"][
                                $field
                            ]["custom"]
                        ) {
                            $location =
                                $state["config"]["guest"]["custom"]["fields"][
                                    $field
                                ]["location"];
                            $custom_field_id =
                                $state["config"]["guest"]["custom"]["fields"][
                                    $field
                                ]["custom_field_id"];
                        }
                    } else {
                        $part = explode("-", $field);
                        if (isset($part[2]) && is_numeric($part[2])) {
                            if ($part[0] == "custom") {
                                $location = $part[1];
                                $custom_field_id = $part[2];
                            }
                        }
                    }
                    if (isset($location)) {
                        $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->updateState(
                            [
                                "session",
                                "custom",
                                "custom_field",
                                $location,
                                $custom_field_id,
                            ],
                            $value
                        );
                    }
                    //nothing at the moment;
                    break;
            }
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState(
                $state
            );
        } else {
            $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->setState(
                $state
            );
        }
    }

    private function getConfig()
    {
        $this->load->config("ajax_quick_checkout/custom");
        $this->load->model(
            "extension/ajax_quick_checkout/ajax_quick_checkout/store"
        );
        $config = $this->config->get("ajax_quick_checkout_custom");

        $result = [];
        $settings = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getSetting();

        foreach ($config["account"] as $account => $value) {
            if (!empty($settings["config"][$account]["custom"])) {
                $result[$account]["custom"] =
                    $settings["config"][$account]["custom"];
            } else {
                $result[$account]["custom"] = array_replace_recursive(
                    $config,
                    $value
                );
            }
        }

        return $result;
    }

    private function getLanguages()
    {
        $this->load->language("checkout/confirm");
        $this->load->language("checkout/payment_method");
        $this->load->language("account/login");
        $this->load->language("account/register");
        $this->load->language(
            "extension/ajax_quick_checkout/ajax_quick_checkout/custom"
        );

        $result = [];
        $languages = $this->config->get("ajax_quick_checkout_custom_language");

        foreach ($languages as $key => $language) {
            $result[$key] = $this->language->get($language);
        }

        $language = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getLanguage();
        if (isset($language["custom"])) {
            $result = array_replace_recursive($result, $language["custom"]);
        }

        //links in default text
        if ($this->config->get("config_checkout_id")) {
            $this->load->model("catalog/information");

            $information_info = $this->model_catalog_information->getInformation(
                $this->config->get("config_checkout_id")
            );

            if ($information_info) {
                $this->load->model("extension/dv_opencart_patch/library/url");
                $result["entry_agree"] = sprintf(
                    $result["entry_agree"],
                    $this->model_extension_dv_opencart_patch_library_url->link(
                        "information/information|info",
                        "information_id=" .
                            $this->config->get("config_checkout_id"),
                        true
                    ),
                    htmlspecialchars_decode($information_info["title"]),
                    $information_info["title"]
                );

                $result["error_agree_checked"] = sprintf(
                    $result["error_agree_checked"],
                    htmlspecialchars_decode($information_info["title"])
                );
            }
        }

        if (
            is_file(DIR_IMAGE . "catalog/ajax_quick_checkout/step/custom.svg")
        ) {
            $result["image"] =
                HTTP_SERVER .
                "image/catalog/ajax_quick_checkout/step/custom.svg";
        } else {
            $result["image"] =
                HTTP_SERVER .
                "extension/ajax_quick_checkout/image/catalog/ajax_quick_checkout/step/custom.svg";
        }

        return $result;
    }

    /**
     * Default state
     */
    private function getDefault($populate = true)
    {
        $custom = [];
        $state = $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_store->getState();
        if ($populate) {
            if (isset($state["session"]["custom"])) {
                $custom = $state["session"]["custom"];
            }
        }
        $default =
            $state["config"][$state["session"]["account"]]["custom"]["fields"];
        $address = [
            "comment" => isset($custom["comment"])
                ? $custom["comment"]
                : $default["comment"]["value"],
            "agree" => isset($custom["agree"])
                ? $custom["agree"]
                : $default["agree"]["value"],
            "custom_field" => [],
        ];

        //init custom fields
        foreach ($default as $key => $field) {
            if (!empty($field["custom"])) {
                $address[$key] = $field["value"];

                $part = explode("-", $key);
                if (isset($part[2]) && is_numeric($part[2])) {
                    if ($part[0] == "custom") {
                        $location = $part[1];
                        $custom_field_id = $part[2];
                    }
                }

                $custom_field = [
                    $location => [
                        $custom_field_id => $field["value"],
                    ],
                ];
                $address["custom_field"] = array_merge(
                    $address["custom_field"],
                    $custom_field
                );
            }
        }
        return $address;
    }

    private function validateField($field, $value)
    {
        $this->load->model(
            "extension/ajax_quick_checkout/ajax_quick_checkout/error"
        );
        return $this->model_extension_ajax_quick_checkout_ajax_quick_checkout_error->validateField(
            "custom",
            $field,
            $value
        );
    }
}
