<?php
namespace keeko\core\responder\json\model;

use keeko\core\model\ApplicationUri;
use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageVariant;
use keeko\core\model\Language;
use keeko\core\model\Localization;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\foundation\AbstractPayloadResponder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Parameters;
use Tobscure\JsonApi\Resource;

/**
 * Automatically generated JsonResponder for Reads a localization
 * 
 * @author Thomas Gossmann
 */
class LocalizationReadJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Found $payload
	 */
	public function found(Request $request, Found $payload) {
		$params = new Parameters($request->query->all());
		$serializer = Localization::getSerializer();
		$resource = new Resource($payload->getModel(), $serializer);
		$resource = $resource->with($params->getInclude(['localizations', 'parent', 'language', 'ext-lang', 'script', 'language-variants', 'application-uris']));
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

		return new JsonResponse($document->toArray(), 200);
	}

	/**
	 * @param Request $request
	 * @param NotFound $payload
	 */
	public function notFound(Request $request, NotFound $payload) {
		throw new ResourceNotFoundException($payload->getMessage());
	}

	/**
	 */
	protected function getPayloadMethods() {
		return [
			'keeko\framework\domain\payload\Found' => 'found',
			'keeko\framework\domain\payload\NotFound' => 'notFound'
		];
	}
}
