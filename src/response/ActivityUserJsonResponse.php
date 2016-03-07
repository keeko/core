<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Activity;

/**
 * Automatically generated JsonResponse for Reads the relationship of activity to user
 * 
 * @author gossi
 */
class ActivityUserJsonResponse extends AbstractResponse {

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
