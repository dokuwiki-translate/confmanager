<?php

class ConfigManagerAdminShowConfig implements ConfigManagerAdminAction {

    /**
     * @var helper_plugin_confmanager
     */
    private $helper;
    private $configId;

    /**
     * @var ConfigManagerConfigType
     */
    private $config;

    public function __construct() {
        $this->helper = plugin_load('helper', 'confmanager');
    }

    public function handle() {
        global $INPUT;
        global $ID;

        $this->configId = $INPUT->str('configFile');
        $this->config = $this->helper->getConfigById($this->configId);
        if ($this->config === false) {
            $params = array(
                'do' => 'admin',
                'page' => 'confmanager'
            );
            send_redirect(wl($ID, $params, false, '&'));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->config->save();
        }
    }

    public function html() {
        $this->header();
        $this->displayDescription();
        $this->displayConfig();
    }

    private function header() {
        $configFiles = $this->helper->getConfigFiles();
        $default = $this->configId;
        include DOKU_PLUGIN . 'confmanager/tpl/selectConfig.php';
    }

    public function displayConfig() {
        $this->formStart();
        $this->config->display();
        $this->formEnd();
    }

    private function formStart() {
        $id = $this->configId;
        include DOKU_PLUGIN . 'confmanager/tpl/formStart.php';
    }

    private function formEnd() {
        include DOKU_PLUGIN . 'confmanager/tpl/formEnd.php';
    }

    private function displayDescription() {
        $description = $this->config->getDescription();
        if (empty($description)) {
            return;
        }

        echo $this->helper->render($description);
    }
}