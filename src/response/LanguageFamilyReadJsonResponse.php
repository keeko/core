<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\LanguageFamily;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponse for Reads a language-family
 * 
 * @author gossi
 */
class LanguageFamilyReadJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$params = new Parameters($request->query->all());
		$serializer = LanguageFamily::getSerializer();
		$resource = new Resource($data, $serializer);
		$resource = $resource->with($params->getInclude([]));
		$resource = $resource->fields($params->getFields([
			'language-family' => LanguageFamily::getSerializer()->getFields()
		]));
		$document = new Document($resource);

		return new JsonResponse($document->toArray(), 200);
	}
}
