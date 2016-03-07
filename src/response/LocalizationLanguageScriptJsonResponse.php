<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Localization;

/**
 * Automatically generated JsonResponse for Reads the relationship of localization to language_script
 * 
 * @author gossi
 */
class LocalizationLanguageScriptJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$serializer = Localization::getSerializer();
		$relationship = $serializer->languageScript();

		return new JsonResponse($relationship->toArray());
	}
}
