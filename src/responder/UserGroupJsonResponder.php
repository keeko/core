<?php
namespace keeko\core\responder;

use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\User;

/**
 * Automatically generated JsonResponder for Reads the relationship of user to group
 * 
 * @author gossi
 */
class UserGroupJsonResponder extends AbstractResponder {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = User::getSerializer();
		$relationship = $serializer->groups();

		return new JsonResponse($relationship->toArray());
	}
}
