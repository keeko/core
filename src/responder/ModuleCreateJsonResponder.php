<?php
namespace keeko\core\responder;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\framework\foundation\AbstractPayloadResponder;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\exceptions\ValidationException;
use keeko\framework\domain\payload\Created;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use keeko\core\model\Module;

/**
 * Automatically generated JsonResponder for Creates a module
 * 
 * @author gossi
 */
class ModuleCreateJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Created $payload
	 */
	public function created(Request $request, Created $payload) {
		$serializer = Module::getSerializer();
		$resource = new Resource($payload->getModel(), $serializer);
		$document = new Document($resource);

		return new JsonResponse($document->toArray(), 201, ['Location' => $resource->getLinks()['self']]);
	}

	/**
	 * @param Request $request
	 * @param NotValid $payload
	 */
	public function notValid(Request $request, NotValid $payload) {
		throw new ValidationException($payload->getViolations());
	}

	/**
	 */
	protected function getPayloadMethods() {
		return [
			'keeko\framework\domain\payload\NotValid' => 'notValid',
			'keeko\framework\domain\payload\Created' => 'created'
		];
	}
}
