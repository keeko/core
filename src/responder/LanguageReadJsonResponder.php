<?php
namespace keeko\core\responder;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\framework\foundation\AbstractPayloadResponder;
use keeko\framework\domain\payload\NotFound;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use keeko\core\model\Language;
use keeko\core\model\LanguageScope;
use keeko\core\model\LanguageType;
use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageFamily;
use keeko\core\model\Localization;
use keeko\framework\domain\payload\Found;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponder for Reads a language
 * 
 * @author gossi
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
		$resource = $resource->with($params->getInclude(['sublanguage', 'parent', 'scope', 'type', 'script', 'family', 'localization']));
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