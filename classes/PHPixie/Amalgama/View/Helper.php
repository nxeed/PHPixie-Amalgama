<?php

namespace PHPixie\Amalgama\View;

class Helper extends \PHPixie\View\Helper {

    protected $aliases = array(
        '_' => 'output',
        '__' => 'translate',
    );

    public function translate($str, $params = array()) {
        return $this->pixie->amalgama->translate($str, $params);
    }

    public function getCurrentLang() {
        return $this->pixie->amalgama->lang;
    }

    public function getLangList() {
        return $this->pixie->amalgama->getLangList();
    }

    public function getDefaultLang() {
        return $this->pixie->amalgama->getDefaultLang();
    }

    public function link($link) {
        $currentLang = $this->pixie->amalgama->lang;
        $defaultLang = $this->pixie->amalgama->getDefaultLang();

        if ($currentLang != $defaultLang) {
            $link = $this->pixie->amalgama->lang . '/' . $link;
        }
        return $this->pixie->basepath . $link;
    }

    public function langSwitch($lang) {
        
    }

}
