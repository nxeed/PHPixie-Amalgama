<?php

namespace PHPixie;

/**
 * Internationalization Module for PHPixie
 *
 * This module allows you to internationalize your application.
 * 
 * @property-read \PHPixie\Amalgama\Extension\Autorouting $autorouting
 * @package Amalgama
 * @author Nxeed <https://github.com/nxeed>
 */
class Amalgama {

	/**
	 * Pixie Dependancy Container
	 * @var \PHPixie\Pixie
	 */
	protected $pixie;

	/**
	 * Module config
	 * @var array
	 */
	protected $config;

	/**
	 * Extension definitions
	 * @var array 
	 */
	protected $extensionClasses = array(
		'autorouting' => '\PHPixie\Amalgama\Extension\Autorouting'
	);

	/**
	 * Instanced extension classes
	 * @var array
	 */
	protected $extensionInstances = array();

	/**
	 * Current language
	 * @var string 
	 */
	public $lang;

	/**
	 * Gets a list of language used
	 * @return array
	 */
	public function getLangList() {
		return $this->config['list'];
	}

	/**
	 * Gets a default language
	 * @return string
	 */
	public function getDefaultLang() {
		return $this->config['default'];
	}

	/**
	 * Gets a property by name. Returns defined extension instances
	 *
	 * @param string $name Property name
	 * @return mixed Instance of defined extension
	 */
	public function __get($name) {
		if (isset($this->extensionInstances[$name])) {
			return $this->extensionInstances[$name];
		}

		if (isset($this->extensionClasses[$name])) {
			return $this->extensionInstances[$name] = new $this->extensionClasses[$name]($this->pixie, $this, $this->config);
		}

		throw new \Exception("Property {$name} not found on " . get_class($this));
	}

	/**
	 * Runs defined extensions
	 * Must be called after pixie bootstrap
	 * 
	 * @return void
	 */
	public function runExtensions() {
		foreach ($this->extensionClasses as $key => $val) {
			$this->$key->run();
		}
	}

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
	public function __($str, $params = array()) {
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

	/**
	 * Constructs a module.
	 *
	 * @param \PHPixie\Pixie $pixie Pixie Dependancy Container
	 * @return void
	 */
	public function __construct($pixie) {
		$this->pixie = $pixie;
		$this->pixie->assets_dirs[] = dirname(dirname(dirname(__FILE__))) . '/assets/';
		$this->config = $this->pixie->config->get('amalgama');
	}

}
