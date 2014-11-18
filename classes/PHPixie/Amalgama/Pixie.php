<?php

namespace PHPixie\Amalgama;

/**
 * @property-read \PHPixie\Amalgama $amalgama
 */
class Pixie extends \PHPixie\Pixie {

	public function view_helper() {
		return new \PHPixie\Amalgama\View\Helper($this);
	}

	public function route($name, $rule, $defaults, $methods = null) {
		return new \PHPixie\Amalgama\Route($this->basepath, $name, $rule, $defaults, $this, $methods);
	}

	protected function after_bootstrap() {
		if (!isset($this->modules['amalgama'])) {
			throw new \Exception('Module amalgama was not defined');
		}

		$this->amalgama->runExtensions();
	}

}
