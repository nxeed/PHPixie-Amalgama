<?php

namespace PHPixie\Amalgama;

class Controller extends \PHPixie\Controller {

	public function before() {
		$uri = $this->request->server('REQUEST_URI');
		$langParam = $this->request->param('lang');
		$langCookie = $this->pixie->cookie->get('lang');
		$langSegment = explode('/', trim($uri, '/'))[0];
		$langDefault = $this->pixie->amalgama->getDefaultLang();

		$this->pixie->amalgama->lang = $langParam;

		if ($langCookie) {
			if ($langCookie != $langDefault && $langParam != $langSegment) {
				header("location: /{$langCookie}{$uri}");
				die();
			}
			$this->pixie->cookie->set('lang', $langParam);
		}

		$this->pixie->cookie->set('lang', $langParam);
	}

	public function __($str, $params = array()) {
		return $this->pixie->amalgama->translate($str, $params);
	}

}
