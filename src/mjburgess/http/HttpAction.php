<?php
namespace mjburgess\http;

use mjburgess\IDispatchable;
use mjburgess\IProvisionable;
use mjburgess\Provider;
use mjburgess\Request;
use mjburgess\Response;

abstract class HttpAction implements IDispatchable, IProvisionable {
	private $provider;

	private $method;
	
	public function provision(Provider $p) {
		$this->provider = $p;
	}

	public function view(array $context = [], $name = '') {
		return $this->provider->view($name ?: get_class($this), $this->method, $context);
	}

	public function dispatch(Request $r) {
		$this->method =$r->getMethod();
		$method = $r->getMethod() . $r->getEndPoint();
		return new Response($this->$method($r));
	}
}