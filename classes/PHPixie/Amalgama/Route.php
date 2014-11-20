<?php

namespace PHPixie\Amalgama;

/**
 * Routing class to extract and parse request parameters from the URL.
 * 
 * @package Amalgama
 */
class Route extends \PHPixie\Route {

	/**
	 * Pixie Dependancy Container
	 * @var \PHPixie\Pixie
	 */
	protected $pixie;

	/**
	 * Constructs a route(added pixie)
	 *
	 * @param string $basepath URL path base
	 * @param string $name Name of the route
	 * @param mixed $rule Rule for this route
	 * @param array $defaults Default parameters for the route
	 * @param \PHPixie\Pixie Pixie Dependancy Container
	 * @param mixed $methods Methods to restrict this route to.
	 *                       Either a single method or an array of them.
	 */
	public function __construct($basepath, $name, $rule, $defaults, $pixie, $methods = null) {
		parent::__construct($basepath, $name, $rule, $defaults, $methods);
		$this->pixie = $pixie;
	}

	/**
	 * Generates a url for a route(added language param handler)
	 *
	 * @param array $params    Parameters to substitute in the route
	 * @param bool $absolute   Whether to return an absolute url
	 * @param string $protocol	Protocol to use for absolute url
	 * @return string Generated url
	 */
	public function url($params = array(), $absolute = false, $protocol = 'http') {

		if (is_callable($this->rule)) {
			throw new \Exception("The rule for '{$this->name}' route is a function and cannot be reversed");
		}

		$url = $this->basepath;
		if (substr($url, -1) === '/') {
			$url = substr($url, 0, -1);
		}
		$url.= is_array($this->rule) ? $this->rule[0] : $this->rule;

		$replace = array();
		$params = array_merge($this->defaults, $params);
		foreach ($params as $key => $value) {
			$replace["<{$key}>"] = $value;
		}
		$url = str_replace(array_keys($replace), array_values($replace), $url);

		$count = 1;
		$chars = '[^\(\)]*?';
		while ($count > 0) {
			$url = preg_replace("#\({$chars}<{$chars}>{$chars}\)#", '', $url, -1, $count);
		}

		$url = str_replace(array('(', ')'), '', $url);



		if (isset($params['lang'])) {
			$langDefault = $this->pixie->amalgama->getDefaultLang();
			$url = preg_replace('/^' . preg_quote($this->basepath, '/') . '/', '', $url);
			$urlArr = explode('/', trim($url, '/'));
			$langSegment = $urlArr[0];

			if ($langSegment == $langDefault) {
				array_shift($urlArr);
			}

			$url = $this->basepath . implode('/', $urlArr);
		}



		if ($absolute) {
			$url = $protocol . '://' . $_SERVER['HTTP_HOST'] . $url;
		}

		return $url;
	}

}
