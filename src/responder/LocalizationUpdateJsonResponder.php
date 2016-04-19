<?php
namespace keeko\core\responder;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\framework\foundation\AbstractPayloadResponder;
use keeko\framework\domain\payload\NotFound;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\exceptions\ValidationException;
use keeko\core\model\Localization;
use keeko\core\model\Language;
use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageVariant;
use keeko\framework\domain\payload\Updated;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use Tobscure\JsonApi\Parameters;
use keeko\framework\domain\payload\NotUpdated;

/**
 * Automatically generated JsonResponder for Updates a localization
 * 
 * @author gossi
 */
class LocalizationUpdateJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param NotFound $payload
	 */
	public function notFound(Request $request, NotFound $payload) {
		throw new ResourceNotFoundException($payload->getMessage());
	}

	/**
	 * @param Request $request
	 * @param NotUpdated $payload
	 */
	public function notUpdated(Request $request, NotUpdated $payload) {
		return new JsonResponse(null, 204);
	}

	/**
	 * @param Request $request
	 * @param NotValid $payload
	 */
	public function notValid(Request $request, NotValid $payload) {
		throw new ValidationException($payload->getViolations());
	}

	/**
	 * @param Request $request
	 * @param Updated $payload
	 */
	public function updated(Request $request, Updated $payload) {
		$params = new Parameters($request->query->all());
		$serializer = Localization::getSerializer();
		$resource = new Resource($payload->getModel(), $serializer);
		$resource = $resource->with($params->getInclude(['parent', 'ext-lang', 'script', 'language-variants']));
		$resource = $resource->fields($params->getFields([
			'localization' => Localization::getSerializer()->getFields(),
			'parent' => Localization::getSerializer()->getFields(),
			'ext-lang' => Language::getSerializer()->getFields(),
			'script' => LanguageScript::getSerializer()->getFields(),
			'language-variants' => LanguageVariant::getSerializer()->getFields()
		]));
		$document = new Document($resource);

		return new JsonResponse($document->toArray(), 200);
	}

	/**
	 */
	protected function getPayloadMethods() {
		return [
			'keeko\framework\domain\payload\NotValid' => 'notValid',
			'keeko\framework\domain\payload\NotFound' => 'notFound',
			'keeko\framework\domain\payload\Updated' => 'updated',
			'keeko\framework\domain\payload\NotUpdated' => 'notUpdated'
		];
	}
}
