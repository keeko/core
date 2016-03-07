<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Application;

/**
 * Automatically generated JsonResponse for Reads the relationship of application to package
 * 
 * @author gossi
 */
class ApplicationPackageJsonResponse extends AbstractResponse {

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
