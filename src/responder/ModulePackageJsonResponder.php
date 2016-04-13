<?php
namespace keeko\core\responder;

use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Module;

/**
 * Automatically generated JsonResponder for Reads the relationship of module to package
 * 
 * @author gossi
 */
class ModulePackageJsonResponder extends AbstractResponder {

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
