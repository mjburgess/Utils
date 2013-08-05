<?php
namespace mjburgess\projects\util\actions;

use mjburgess\http\HttpAction;
use mjburgess\http\HttpRequest;


class GMailAction extends HttpAction {
	public function getGMail(HttpRequest $r) {
		return $this->view();
	}
	
	public function postGMail(HttpRequest $r) {
		return $this->view();
	}
}