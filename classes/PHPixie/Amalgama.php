<?php

namespace PHPixie;

/**
 * @property-read \PHPixie\Amalgama\Extension\Autorouting $autorouting
 */
class Amalgama {

    protected $pixie;
    protected $config;
    protected $extensionClasses = array(
        'autorouting' => '\PHPixie\Amalgama\Extension\Autorouting'
    );
    protected $extensionInstances = array();
    public $lang;

    public function getLangList() {
        return $this->config['list'];
    }

    public function getDefaultLang() {
        return $this->config['default'];
    }

    public function __get($name) {
        if (isset($this->extensionInstances[$name])) {
            return $this->extensionInstances[$name];
        }

        if (isset($this->extensionClasses[$name])) {
            return $this->extensionInstances[$name] = new $this->extensionClasses[$name]($this->pixie, $this, $this->config);
        }

        throw new \Exception("Property {$name} not found on " . get_class($this));
    }

    public function runExtensions() {
        foreach ($this->extensionClasses as $key => $val) {
            $this->$key->run();
        }
    }

    public function translate($str, $params = array()) {
        $lang = $this->lang;
        $translation = $str;

        if (!$lang) {
            throw new \Exception('Current language was not defined');
        }

        $file = $this->pixie->config->get('amalgama/' . $lang);

        if ($file && isset($file[$str])) {
            $translation = $file[$str];
        }

        if (!empty($params)) {

            $translation = str_replace(array('%', '<?>'), array('%%', '%s'), $translation);
            array_unshift($params, $translation);
            $translation = call_user_func_array('sprintf', $params);
        }

        return $translation;
    }

    public function __construct($pixie) {
        $this->pixie = $pixie;
        $this->pixie->assets_dirs[] = dirname(dirname(dirname(__FILE__))) . '/assets/';
        $this->config = $this->pixie->config->get('amalgama');
    }

}
