<?php
namespace mjburgess;
interface IDispatchable {
	public function dispatch(Request $r);
}