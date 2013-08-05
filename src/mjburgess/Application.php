<?php
namespace mjburgess;

class Application implements IProvisionable {
	private $provider;

	public function __construct(Provider $p = null) {
		$this->provision($p);
	}
	
	public function provision(Provider $p) {
		$this->provider = $p;
	}
	
	public static function Autoloader($root) {
		return function ($classname) use ($root) {
			$classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
			
			require $root . DIRECTORY_SEPARATOR . "$classname.php";
		};
	}
	
	public function http(http\Server $server, Router $router) {
		$rq  = new http\HttpRequest($server, 'GMail');
		$d   = $router->getDispatchable($rq->getURI());
		
		return $this->run(new $d, $rq);
	}

	
	public function run(IDispatchable $d, http\HttpRequest $rq) {
		if($d instanceof IProvisionable) {
			$d->provision($this->provider);
		}
		
		return $d->dispatch($rq);
	}
}