<?php
namespace keeko\core\responder;

use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Api;

/**
 * Automatically generated JsonResponder for Reads the relationship of api to action
 * 
 * @author gossi
 */
class ApiActionJsonResponder extends AbstractResponder {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = Api::getSerializer();
		$relationship = $serializer->action();

		return new JsonResponse($relationship->toArray());
	}
}
