<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\ApplicationUri;

/**
 * Automatically generated JsonResponse for Reads the relationship of application_uri to application
 * 
 * @author gossi
 */
class ApplicationUriApplicationJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = ApplicationUri::getSerializer();
		$relationship = $serializer->application();

		return new JsonResponse($relationship->toArray());
	}
}
