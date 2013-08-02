<?php
require implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'src', 'bootstrap.php']);
require 'services.config.php';

use mjburgess\Application;
use mjburgess\Provider;

echo (new Application($p = provide_services(new Provider())))->http($p->server(), $p->routes());
