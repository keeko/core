<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Localization;
use keeko\core\model\Language;
use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageVariant;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponse for Updates a localization
 * 
 * @author gossi
 */
class LocalizationUpdateJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$params = new Parameters($request->query->all());
		$serializer = Localization::getSerializer();
		$resource = new Resource($data, $serializer);
		$resource = $resource->with($params->getInclude(['localization', 'language', 'language', 'language-script', 'language-variants']));
		$resource = $resource->fields($params->getFields([
			'localization' => Localization::getSerializer()->getFields(),
			'language' => Language::getSerializer()->getFields(),
			'language-script' => LanguageScript::getSerializer()->getFields(),
			'language-variants' => LanguageVariant::getSerializer()->getFields()
		]));
		$document = new Document($resource);

		return new JsonResponse($document->toArray(), 200);
	}
}
