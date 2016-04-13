<?php
namespace keeko\core\responder;

use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Extension;

/**
 * Automatically generated JsonResponder for Reads the relationship of extension to package
 * 
 * @author gossi
 */
class ExtensionPackageJsonResponder extends AbstractResponder {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = Extension::getSerializer();
		$relationship = $serializer->package();

		return new JsonResponse($relationship->toArray());
	}
}
