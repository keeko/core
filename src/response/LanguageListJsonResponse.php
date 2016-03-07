<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Language;
use keeko\core\model\LanguageScope;
use keeko\core\model\LanguageType;
use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageFamily;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponse for List all languages
 * 
 * @author gossi
 */
class LanguageListJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$params = new Parameters($request->query->all());
		$serializer = Language::getSerializer();
		$resource = new Collection($data, $serializer);
		$resource = $resource->with($params->getInclude(['language', 'language-scope', 'language-type', 'language-script', 'language-family']));
		$resource = $resource->fields($params->getFields([
			'language' => Language::getSerializer()->getFields(),
			'language-scope' => LanguageScope::getSerializer()->getFields(),
			'language-type' => LanguageType::getSerializer()->getFields(),
			'language-script' => LanguageScript::getSerializer()->getFields(),
			'language-family' => LanguageFamily::getSerializer()->getFields()
		]));
		$document = new Document($resource);

		// meta
		$document->setMeta([
			'total' => $data->getNbResults(),
			'first' => $data->getFirstPage(),
			'next' => $data->getNextPage(),
			'previous' => $data->getPreviousPage(),
			'last' => $data->getLastPage()
		]);

		// return response
		return new JsonResponse($document->toArray());
	}
}
