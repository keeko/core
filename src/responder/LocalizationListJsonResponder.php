<?php
namespace keeko\core\responder;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\framework\foundation\AbstractPayloadResponder;
use keeko\core\model\Localization;
use keeko\core\model\Language;
use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageVariant;
use keeko\core\model\ApplicationUri;
use keeko\framework\domain\payload\Found;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponder for List all localizations
 * 
 * @author gossi
 */
class LocalizationListJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Found $payload
	 */
	public function found(Request $request, Found $payload) {
		$params = new Parameters($request->query->all());
		$data = $payload->getModel();
		$serializer = Localization::getSerializer();
		$resource = new Collection($data, $serializer);
		$resource = $resource->with($params->getInclude(['localization', 'parent', 'language', 'ext-lang', 'script', 'language-variant', 'application-uri']));
		$resource = $resource->fields($params->getFields([
			'localization' => Localization::getSerializer()->getFields(),
			'parent' => Localization::getSerializer()->getFields(),
			'language' => Language::getSerializer()->getFields(),
			'ext-lang' => Language::getSerializer()->getFields(),
			'script' => LanguageScript::getSerializer()->getFields(),
			'language-variant' => LanguageVariant::getSerializer()->getFields(),
			'application-uri' => ApplicationUri::getSerializer()->getFields()
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
