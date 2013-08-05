<?php
namespace mjburgess\view;

class Container {
	private $file;
	private $container;

	public function __construct($file, array $container = []) {
		$this->file = $file;
		$this->container = $container;
	}

	public function inject($key, $value) {
		$this->container[$key] = $value;
	}

	public function __toString() {
		extract($this->container);

		ob_start();
		include $this->file;
		return ob_get_clean();
	}
}