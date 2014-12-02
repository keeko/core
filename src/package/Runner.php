<?php
namespace keeko\core\package;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Runner {

	/**
	 * Runs an application or action
	 *
	 * @param RunnableInterface $target
	 * @param Request $request
	 * @return Response
	 */
	public function run(RunnableInterface $target, Request $request) {
		$target->beforeRun();
		$response = $target->run($request);
		$target->afterRun();
		
		return $response;
	}
}
