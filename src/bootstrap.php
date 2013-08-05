<?php
namespace mjburgess;

require 'mjburgess/IProvisionable.php';
require 'mjburgess/Application.php';

spl_autoload_register(Application::Autoloader(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src'));