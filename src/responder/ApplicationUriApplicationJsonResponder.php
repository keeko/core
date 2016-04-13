<?php
namespace keeko\core\responder;

use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\ApplicationUri;

/**
 * Automatically generated JsonResponder for Reads the relationship of application_uri to application
 * 
 * @author gossi
 */
class ApplicationUriApplicationJsonResponder extends AbstractResponder {

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
