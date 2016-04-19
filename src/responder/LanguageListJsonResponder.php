<?php
namespace keeko\core\responder;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\framework\foundation\AbstractPayloadResponder;
use keeko\core\model\Language;
use keeko\core\model\LanguageScope;
use keeko\core\model\LanguageType;
use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageFamily;
use keeko\framework\domain\payload\Found;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponder for List all languages
 * 
 * @author gossi
 */
class LanguageListJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Found $payload
	 */
	public function found(Request $request, Found $payload) {
		$params = new Parameters($request->query->all());
		$data = $payload->getModel();
		$serializer = Language::getSerializer();
		$resource = new Collection($data, $serializer);
		$resource = $resource->with($params->getInclude(['parent', 'scope', 'type', 'script', 'family']));
		$resource = $resource->fields($params->getFields([
			'language' => Language::getSerializer()->getFields(),
			'parent' => Language::getSerializer()->getFields(),
			'scope' => LanguageScope::getSerializer()->getFields(),
			'type' => LanguageType::getSerializer()->getFields(),
			'script' => LanguageScript::getSerializer()->getFields(),
			'family' => LanguageFamily::getSerializer()->getFields()
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

	/**
	 */
	protected function getPayloadMethods() {
		return [
			'keeko\framework\domain\payload\Found' => 'found'
		];
	}
}
