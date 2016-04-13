<?php
namespace keeko\core\responder;

use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Action;

/**
 * Automatically generated JsonResponder for Reads the relationship of action to module
 * 
 * @author gossi
 */
class ActionModuleJsonResponder extends AbstractResponder {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = Action::getSerializer();
		$relationship = $serializer->module();

		return new JsonResponse($relationship->toArray());
	}
}
