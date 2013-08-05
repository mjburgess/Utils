<?php
namespace mjburgess\http;

use mjburgess\Request;

class HttpRequest extends Request {
	private $uri;
	private $method;

	public function __construct(Server $s, $ep) {
		$this->uri    = $s->getRequestURI();
		$this->method = $s->getRequestMethod();
		$this->setEndPoint($ep);
	}

	public function getURI() {
		return $this->uri;
	}


	public function getMethod() {
		return $this->method;
	}
}
