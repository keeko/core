<?php
namespace keeko\core\application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ApplicationContentInterface {

	/**
	 * 
	 * @return string
	 */
	public function run(Request $request, Response $response);
}