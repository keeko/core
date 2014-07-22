<?php
namespace keeko\core\action;

use keeko\core\action\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractJsonResponse extends AbstractResponse {

	/**
	 * Returns the passed data as JsonResponse
	 *
	 * @param Request $request
     * @return JsonResponse
	 */
	public function run(Request $request)
    {
        return new JsonResponse($this->data);
    }

}