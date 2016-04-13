<?php
namespace keeko\core\responder;

use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Session;

/**
 * Automatically generated JsonResponder for Reads the relationship of session to user
 * 
 * @author gossi
 */
class SessionUserJsonResponder extends AbstractResponder {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = Session::getSerializer();
		$relationship = $serializer->user();

		return new JsonResponse($relationship->toArray());
	}
}
