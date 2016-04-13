<?php
namespace keeko\core\responder;

use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Localization;

/**
 * Automatically generated JsonResponder for Reads the relationship of localization to localization
 * 
 * @author gossi
 */
class LocalizationLocalizationJsonResponder extends AbstractResponder {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = Localization::getSerializer();
		$relationship = $serializer->localization();

		return new JsonResponse($relationship->toArray());
	}
}
