<?php

namespace PHPixie\Amalgama\Extension;

/**
 * Autoroutin extension for Amalgama PHPixie module
 * Allow to use your routes without adding lang param
 * 
 * @package Amalgama
 */
class Autorouting {

	/**
	 * Pixie Dependancy Container
	 * @var \PHPixie\Pixie
	 */
	protected $pixie;

	/**
	 * Amalgama module instance
	 * @var \PHPixie\Amalgama 
	 */
	protected $amalgama;

	/**
	 * Amalgama module config
	 * @var array
	 */
	protected $config;

	/**
	 * Runs extension
	 * Adds lang param in all routes
	 * (except routes with names autoroutingExcept matched)
	 * @return void
	 */
	public function run() {
		if (!$this->config['autorouting']) {
			return;
		}

		if ($this->config['autoroutingExcept']) {
			$routeNames = array_filter(array_keys($this->pixie->config->get('routes')), function($name) {
				$rule = $this->config['autoroutingExcept'];
				return !preg_match('/' . $rule . '/', $name);
			});
		}

		$langList = $this->amalgama->getLangList();
		$langDefault = $this->amalgama->getDefaultLang();

		foreach ($routeNames as $name) {
			$route = $this->pixie->router->get($name);

			$rule = is_array($route->rule) ? $route->rule[0] : $route->rule;
			$params = is_array($route->rule) ? $route->rule[1] : array();
			$defaults = is_array($route->defaults) ? $route->defaults : array();

			$newRule = '(/<lang>)' . ltrim($rule, '/');
			$newParams = array_merge($params, array('lang' => implode('|', $langList)));
			$newDefaults = array_merge($defaults, array('lang' => $langDefault));

			$route->rule = array($newRule, $newParams);
			$route->defaults = $newDefaults;

			$this->pixie->router->add($route);
		}
	}

	/**
	 * Constructs a extension
	 * 
	 * @param \PHPixie\Pixie $pixie
	 * @param \PHPixie\Amalgama $amalgama
	 * @param array $config
	 * @return void
	 */
	public function __construct(\PHPixie\Pixie $pixie, \PHPixie\Amalgama $amalgama, array $config) {
		$this->pixie = $pixie;
		$this->amalgama = $amalgama;
		$this->config = $config;
	}

}
