<?php

namespace PHPixie\Amalgama;

/**
 * Must be extended your base controller class(e.g \App\Page).
 * 
 * @property-read \PHPixie\Amalgama $amalgama
 * @package Amalgama
 */
class Controller extends \PHPixie\Controller {
	/**
	 * Amalgama module instance
	 * @var \PHPixie\Amalgama 
	 */
	protected $amalgama;
	
	/**
	 * Current language
	 * @var string 
	 */
	protected $lang;

	/**
	 * This method is called before the action.
	 * (Don't forget calls parent::before() in your base controller)
	 *
	 * @return void
	 */
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
		return $this->pixie->amalgama->__($str, $params);
	}

}
