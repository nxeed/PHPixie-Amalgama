<?php

namespace PHPixie\Amalgama;

class Controller extends \PHPixie\Controller {

    public function before() {
        $langParam = $this->request->param('lang');
        $langDefault = $this->pixie->amalgama->getDefaultLang();
        
        if (!$langParam) {
            $this->pixie->amalgama->lang = $langDefault;
            return;
        }

        $path = parse_url($this->request->server('REQUEST_URI'), PHP_URL_PATH);
        $query = parse_url($this->request->server('REQUEST_URI'), PHP_URL_QUERY);

        $langCookie = $this->pixie->cookie->get('lang');
        $langSegment = explode('/', trim($path, '/'))[0];
        $langList = $this->pixie->amalgama->getLangList();

        if ($query) {
            $query = '?' . $query;
        }

        $this->pixie->amalgama->lang = $langParam;

        if (in_array($langCookie, $langList)) {
            if ($langCookie != $langDefault && $langParam != $langSegment) {
                header("location: /{$langCookie}{$path}{$query}");
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
        return $this->pixie->amalgama->translate($str, $params);
    }

}
