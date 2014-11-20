<?php

namespace PHPixie\Amalgama\View;

/**
 * View helper class.
 * An instance of this class is passed automatically
 * to every View.
 *
 * @package Amalgama
 */
class Helper extends \PHPixie\View\Helper {

	/**
	 * List of aliases to create for methods
	 * @var array
	 */
	protected $aliases = array(
		'_' => 'output',
		'__' => 'translate',
	);

	/**
	 * Translates the specified string
	 * 
	 * <code>
	 * <?php
	 * $__('Hello <?>!, array('Nxeed');
	 * ?>
	 * </code>
	 * @param string $str Translation string
	 * @param array $params
	 * @return string
	 * @throws \Exception If current languge was not defined
	 */
	public function translate($str, $params = array()) {
		return $this->pixie->amalgama->__($str, $params);
	}

	/**
	 * Gets a current language
	 * @return string
	 */
	public function getCurrentLang() {
		return $this->pixie->amalgama->lang;
	}

	/**
	 * Gets a list of language used
	 * @return array
	 */
	public function getLangList() {
		return $this->pixie->amalgama->getLangList();
	}

	/**
	 * Gets a default language
	 * @return string
	 */
	public function getDefaultLang() {
		return $this->pixie->amalgama->getDefaultLang();
	}

	/**
	 * Adds lang param to specifed uri
	 * @param string $link URI string
	 * @return string
	 */
	public function link($link) {
		$currentLang = $this->pixie->amalgama->lang;
		$defaultLang = $this->pixie->amalgama->getDefaultLang();

		if ($currentLang != $defaultLang) {
			$link = $this->pixie->amalgama->lang . '/' . $link;
		}
		return $this->pixie->basepath . $link;
	}

	/**
	 * Returns current uri with specifed language param
	 * 
	 * @param string $lang Langauga param
	 * @return string
	 */
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
