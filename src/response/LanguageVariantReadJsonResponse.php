<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\LanguageVariant;
use keeko\core\model\Localization;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponse for Reads a language-variant
 * 
 * @author gossi
 */
class LanguageVariantReadJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$params = new Parameters($request->query->all());
		$serializer = LanguageVariant::getSerializer();
		$resource = new Resource($data, $serializer);
		$resource = $resource->with($params->getInclude(['localizations']));
		$resource = $resource->fields($params->getFields([
			'language-variant' => LanguageVariant::getSerializer()->getFields(),
			'localizations' => Localization::getSerializer()->getFields()
		]));
		$document = new Document($resource);

		return new JsonResponse($document->toArray(), 200);
	}
}
