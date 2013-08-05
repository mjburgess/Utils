<?php
namespace mjburgess;

interface IProvisionable {
	public function provision(Provider $p);
}