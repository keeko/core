<?php
namespace keeko\core\responder;

use keeko\framework\domain\payload\PayloadInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\framework\foundation\AbstractPayloadResponder;
use keeko\framework\exceptions\ValidationException;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use keeko\core\model\Activity;

/**
 * Automatically generated JsonResponder for Creates an activity
 * 
 * @author gossi
 */
class ActivityCreateJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param PayloadInterface $payload
	 */
	public function created(Request $request, PayloadInterface $payload) {
		$serializer = Activity::getSerializer();
		$resource = new Resource($payload->getModel(), $serializer);
		$document = new Document($resource);

		return new JsonResponse($document->toArray(), 201, ['Location' => $resource->getLinks()['self']]);
	}

	/**
	 * @param Request $request
	 * @param PayloadInterface $payload
	 */
	public function notValid(Request $request, PayloadInterface $payload) {
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
