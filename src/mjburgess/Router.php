<?php
namespace mjburgess;

class Router {
	private $routes;

	public function __construct(array $routes = []) {
		$this->routes = $routes;
	}

	public function addRoute($route, IDispatchable $d) {
		$this->routes[$route] = $d;
	}

	public function getDispatchable($route) {
		return $this->routes[$route];
	}
}