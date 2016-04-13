<?php
namespace keeko\core\responder;

use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Activity;

/**
 * Automatically generated JsonResponder for Reads the relationship of activity to user
 * 
 * @author gossi
 */
class ActivityUserJsonResponder extends AbstractResponder {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = Activity::getSerializer();
		$relationship = $serializer->user();

		return new JsonResponse($relationship->toArray());
	}
}
