<?php
namespace keeko\core\responder\json\model;

use keeko\core\model\Language;
use keeko\core\model\LanguageFamily;
use keeko\core\model\LanguageScope;
use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageType;
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
 * Automatically generated JsonResponder for Reads a language
 * 
 * @author Thomas Gossmann
 */
class LanguageReadJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Found $payload
	 */
	public function found(Request $request, Found $payload) {
		$params = new Parameters($request->query->all());
		$serializer = Language::getSerializer();
		$resource = new Resource($payload->getModel(), $serializer);
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
