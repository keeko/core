<?php
namespace keeko\core\responder;

use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Group;

/**
 * Automatically generated JsonResponder for Reads the relationship of group to user
 * 
 * @author gossi
 */
class GroupUserJsonResponder extends AbstractResponder {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = Group::getSerializer();
		$relationship = $serializer->users();

		return new JsonResponse($relationship->toArray());
	}
}
