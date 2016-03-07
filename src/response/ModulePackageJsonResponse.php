<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Module;

/**
 * Automatically generated JsonResponse for Reads the relationship of module to package
 * 
 * @author gossi
 */
class ModulePackageJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = Module::getSerializer();
		$relationship = $serializer->package();

		return new JsonResponse($relationship->toArray());
	}
}
