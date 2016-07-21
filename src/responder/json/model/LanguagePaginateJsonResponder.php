<?php
namespace keeko\core\responder\json\model;

use keeko\core\model\Language;
use keeko\core\model\LanguageFamily;
use keeko\core\model\LanguageScope;
use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageType;
use keeko\core\model\Localization;
use keeko\framework\domain\payload\Found;
use keeko\framework\foundation\AbstractPayloadResponder;
use keeko\framework\utils\Parameters;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Document;

/**
 * Automatically generated JsonResponder for Paginates languages
 * 
 * @author Thomas Gossmann
 */
class LanguagePaginateJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Found $payload
	 */
	public function found(Request $request, Found $payload) {
		$params = new Parameters($request->query->all());
		$data = $payload->getModel();
		$serializer = Language::getSerializer();
		$resource = new Collection($data, $serializer);
		$resource = $resource->with($params->getInclude(['sublanguages', 'parent', 'scope', 'type', 'script', 'family', 'localizations']));
		$resource = $resource->fields($params->getFields([
			'language' => Language::getSerializer()->getFields(),
			'sublanguage' => Language::getSerializer()->getFields(),
			'parent' => Language::getSerializer()->getFields(),
			'scope' => LanguageScope::getSerializer()->getFields(),
			'type' => LanguageType::getSerializer()->getFields(),
			'script' => LanguageScript::getSerializer()->getFields(),
			'family' => LanguageFamily::getSerializer()->getFields(),
			'localization' => Localization::getSerializer()->getFields()
		]));
		$document = new Document($resource);

		// meta
		if ($params->getPage('size') != -1) {
		    $document->setMeta([
		    	'total' => $data->getNbResults(),
		    	'first' => '%apiurl%/' . $serializer->getType(null) . '?' . $params->toQueryString(['page' => ['number' => $data->getFirstPage()]]),
		    	'next' => '%apiurl%/' . $serializer->getType(null) . '?' . $params->toQueryString(['page' => ['number' => $data->getNextPage()]]),
		    	'previous' => '%apiurl%/' . $serializer->getType(null) . '?' . $params->toQueryString(['page' => ['number' => $data->getPreviousPage()]]),
		    	'last' => '%apiurl%/' . $serializer->getType(null) . '?' . $params->toQueryString(['page' => ['number' => $data->getLastPage()]])
		    ]);
		}

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
