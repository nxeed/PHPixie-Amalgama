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

        $uri = $this->request->server('REQUEST_URI');
        $path = parse_url($uri, PHP_URL_PATH);
        $path = preg_replace('/^'.preg_quote($this->pixie->basepath, '/').'/', '', $path);
        
        $query = parse_url($uri, PHP_URL_QUERY);

        $langCookie = $this->pixie->cookie->get('lang');
        $langSegment = explode('/', trim($path, '/'))[0];
        $langList = $this->amalgama->getLangList();

        if ($query) {
            $query = '?' . $query;
        }

        $this->amalgama->lang = $langParam;
        $this->lang = $langParam;

        if (in_array($langCookie, $langList)) {
            if ($langCookie != $langDefault && $langParam != $langSegment) {
                header("location: {$this->pixie->basepath}{$langCookie}{$path}{$query}");
                die();
            }
            $this->pixie->cookie->set('lang', $langParam);
        }

        if ($langParam != $langDefault) {
            $this->pixie->cookie->set('lang', $langParam);
        } else {
            $this->pixie->cookie->remove('lang');
        }
    }

    public function __($str, $params = array()) {
        return $this->pixie->amalgama->__($str, $params);
    }

}
