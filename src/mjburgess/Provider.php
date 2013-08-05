<?php
namespace mjburgess;
class Provider {
	private $providers;

	public function register($name, callable $c) {
		$this->providers[$name] = $c;
	}

	public function provide($name) {
		return $this->providers[$name];
	}

	public function __call($method, array $args = []) {
		return call_user_func_array($this->providers[$method], $args);
	}
}