<?php
namespace mjburgess;
class Request {
	private $endPoint;

	public function setEndPoint($ep) {
		$this->endPoint = $ep;
	}
	
	public function getEndPoint() {
		return $this->endPoint;
	}
}