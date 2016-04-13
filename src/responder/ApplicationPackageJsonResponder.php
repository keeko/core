<?php
namespace keeko\core\responder;

use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Application;

/**
 * Automatically generated JsonResponder for Reads the relationship of application to package
 * 
 * @author gossi
 */
class ApplicationPackageJsonResponder extends AbstractResponder {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = Application::getSerializer();
		$relationship = $serializer->package();

		return new JsonResponse($relationship->toArray());
	}
}
