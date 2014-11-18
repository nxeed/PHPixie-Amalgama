<?php

namespace PHPixie\Amalgama\View;

class Helper extends \PHPixie\View\Helper {

	protected $aliases = array(
		'_' => 'output',
		'__' => 'translate',
	);

	public function translate($str, $params = array()) {
		return $this->pixie->amalgama->__($str, $params);
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

	public function langSwitchLink($lang) {
		$uri = $_SERVER['REQUEST_URI'];
		$path = parse_url($uri, PHP_URL_PATH);
		$path = preg_replace('/^' . preg_quote($this->pixie->basepath, '/') . '/', '', $path);
		$query = parse_url($uri, PHP_URL_QUERY);
		$pathArr = explode('/', trim($path, '/'));
		$langSegment = $pathArr[0];
		$langList = $this->pixie->amalgama->getLangList();
		$langDefault = $this->pixie->amalgama->getDefaultLang();
		if ($query) {
			$query = '?' . $query;
		}

		if (in_array($langSegment, $langList)) {
			array_shift($pathArr);
		}
		if ($lang != $langDefault) {
			array_unshift($pathArr, $lang);
		}

		return $this->pixie->basepath . trim(implode('/', $pathArr), '/') . $query;
	}

}
