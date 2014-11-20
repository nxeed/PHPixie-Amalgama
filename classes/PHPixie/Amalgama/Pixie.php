<?php

namespace PHPixie\Amalgama;

/**
 * Must be extended your pixie class(e.g \App\Pixie).
 * 
 * @package Amalgama
 */
class Pixie extends \PHPixie\Pixie {

	/**
	 * Constructs a view helper
	 *
	 * @return \PHPixie\View\Helper
	 */
    public function view_helper() {
        return new \PHPixie\Amalgama\View\Helper($this);
    }

	/**
	 * Constructs a route
	 *
	 * @param string $name Name of the route
	 * @param mixed $rule Rule for this route
	 * @param array $defaults Default parameters for the route
	 * @param mixed $methods Methods to restrict this route to.
	 *                       Either a single method or an array of them.
	 * @return \PHPixie\Route
	 */
    public function route($name, $rule, $defaults, $methods = null) {
        return new \PHPixie\Amalgama\Route($this->basepath, $name, $rule, $defaults, $this, $methods);
    }

	/**
	 * Perform some initialization after bootstrap finished
	 *
	 * @return void
	 */
    protected function after_bootstrap() {
        if (!isset($this->modules['amalgama'])) {
            throw new \Exception('Module amalgama was not defined');
        }

        $this->amalgama->runExtensions();
    }

}
