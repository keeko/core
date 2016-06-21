<?php
namespace keeko\core\responder\json\model;

use keeko\core\model\Activity;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\exceptions\ValidationException;
use keeko\framework\foundation\AbstractPayloadResponder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;

/**
 * Automatically generated JsonResponder for Creates an activity
 * 
 * @author Thomas Gossmann
 */
class ActivityCreateJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Created $payload
	 */
	public function created(Request $request, Created $payload) {
		$serializer = Activity::getSerializer();
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