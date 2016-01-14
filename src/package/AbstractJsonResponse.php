<?php
namespace keeko\core\package;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractJsonResponse extends AbstractResponse {

	/**
	 * Returns the passed data as JsonResponse
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = []) {
		return new JsonResponse($data);
	}

}