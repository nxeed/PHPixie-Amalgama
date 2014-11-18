<?php

namespace PHPixie\Amalgama;

class Controller extends \PHPixie\Controller {
    protected $amalgama;
    protected $lang;

    public function before() {
        $this->amalgama = $this->pixie->amalgama;
        
        $langParam = $this->request->param('lang');
        $langDefault = $this->pixie->amalgama->getDefaultLang();
        
        if (!$langParam) {
            $this->amalgama->lang = $langDefault;
            $this->lang = $langDefault;
            return;
        }

        $this->amalgama->lang = $langParam;
        $this->lang = $langParam;
    }

    public function __($str, $params = array()) {
        return $this->pixie->amalgama->__($str, $params);
    }

}
