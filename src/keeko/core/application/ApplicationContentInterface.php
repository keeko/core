<?php
namespace keeko\core\application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ApplicationContentInterface {

	/**
	 * 
	 * @param Request $request
	 * @param Response $response
	 * 
	 * @return Response
	 */
	public function run(Request $request, Response $response);
}