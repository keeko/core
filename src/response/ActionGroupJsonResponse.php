<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Action;

/**
 * Automatically generated JsonResponse for Reads the relationship of action to group
 * 
 * @author gossi
 */
class ActionGroupJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = Action::getSerializer();
		$relationship = $serializer->groups();

		return new JsonResponse($relationship->toArray());
	}
}
