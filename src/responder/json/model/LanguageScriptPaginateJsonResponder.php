<?php
namespace keeko\core\responder\json\model;

use keeko\core\model\LanguageScript;
use keeko\core\model\Language;
use keeko\core\model\Localization;
use keeko\framework\domain\payload\Found;
use keeko\framework\foundation\AbstractPayloadResponder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponder for Paginates language_scripts
 * 
 * @author Thomas Gossmann
 */
class LanguageScriptPaginateJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Found $payload
	 */
	public function found(Request $request, Found $payload) {
		$params = new Parameters($request->query->all());
		$data = $payload->getModel();
		$serializer = LanguageScript::getSerializer();
		$resource = new Collection($data, $serializer);
		$resource = $resource->with($params->getInclude(['languages', 'localizations']));
		$resource = $resource->fields($params->getFields([
			'language-script' => LanguageScript::getSerializer()->getFields(),
			'language' => Language::getSerializer()->getFields(),
			'localization' => Localization::getSerializer()->getFields()
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
