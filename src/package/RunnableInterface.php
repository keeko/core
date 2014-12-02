<?php
namespace keeko\core\package;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RunnableInterface {

	public function beforeRun();
	
	/**
	 * Runs the particular target
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request);
	
	public function afterRun();
}
