<?php
use mjburgess\Provider;
use mjburgess\view\Container;
use mjburgess\http\Server;
use mjburgess\Router;

function provide_services(Provider $p) {
	$p->register('view', function ($name, $method, array $context) {
		$name   = strtolower(str_replace('Action', '', end(explode('\\', $name))));
		$method = strtolower($method);
		
		return new Container(sprintf(__DIR__ . '/phtml/%s/%s.phtml', $name, "$method.$name"), $context);
	});
	
	$p->register('server', function () {
		return new Server([
			'REQUEST_METHOD' => 'GET',
			'REQUEST_URI' => '/'
		]);
	});
	
	$p->register('routes', function() {
		$actionLocation = 'mjburgess\projects\util\actions\\';
		
		return new Router([
			'/' => $actionLocation . 'GMailAction'
		]);
	});
	
	return $p;
}