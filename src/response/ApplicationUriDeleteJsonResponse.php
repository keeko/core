<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Automatically generated JsonResponse for Deletes an application-uri
 * 
 * @author gossi
 */
class ApplicationUriDeleteJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		return new JsonResponse(null, 204);
	}
}
