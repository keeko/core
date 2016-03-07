<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Group;

/**
 * Automatically generated JsonResponse for Reads the relationship of group to action
 * 
 * @author gossi
 */
class GroupActionJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = Group::getSerializer();
		$relationship = $serializer->actions();

		return new JsonResponse($relationship->toArray());
	}
}
