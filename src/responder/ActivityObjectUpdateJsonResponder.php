<?php
namespace keeko\core\responder;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\framework\domain\payload\PayloadInterface;
use keeko\framework\foundation\AbstractPayloadResponder;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use keeko\framework\exceptions\ValidationException;
use keeko\core\model\ActivityObject;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponder for Updates an activity-object
 * 
 * @author gossi
 */
class ActivityObjectUpdateJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param PayloadInterface $payload
	 */
	public function notFound(Request $request, PayloadInterface $payload) {
		throw new ResourceNotFoundException($payload->getMessage());
	}

	/**
	 * @param Request $request
	 * @param PayloadInterface $payload
	 */
	public function notUpdated(Request $request, PayloadInterface $payload) {
		return new JsonResponse(null, 204);
	}

	/**
	 * @param Request $request
	 * @param PayloadInterface $payload
	 */
	public function notValid(Request $request, PayloadInterface $payload) {
		throw new ValidationException($payload->getViolations());
	}

	/**
	 * @param Request $request
	 * @param PayloadInterface $payload
	 */
	public function updated(Request $request, PayloadInterface $payload) {
		$params = new Parameters($request->query->all());
		$serializer = ActivityObject::getSerializer();
		$resource = new Resource($payload->getModel(), $serializer);
		$resource = $resource->with($params->getInclude([]));
		$resource = $resource->fields($params->getFields([
			'activity-object' => ActivityObject::getSerializer()->getFields()
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