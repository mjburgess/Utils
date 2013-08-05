<?php
namespace mjburgess;
class Response {
	private $body;

	public function __construct($body) {
		$this->body = $body;
	}

	public function __toString() {
		return (string) $this->body;
	}
}