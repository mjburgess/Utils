<?php
namespace mjburgess\http;
class Server {
	private $server;
	
	public function __construct(array $server) {
		$this->server = $server;
	}
	
	public function getRequestURI() {
		return $this->server['REQUEST_URI'];
	}
	
	public function getRequestMethod() {
		return $this->server['REQUEST_METHOD'];
	}
}