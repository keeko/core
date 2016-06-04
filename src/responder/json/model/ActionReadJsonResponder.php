<?php
namespace keeko\core\responder\json\model;

use keeko\core\model\Action;
use keeko\core\model\Api;
use keeko\core\model\Group;
use keeko\core\model\Module;
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
 * Automatically generated JsonResponder for Reads an action
 * 
 * @author Thomas Gossmann
 */
class ActionReadJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Found $payload
	 */
	public function found(Request $request, Found $payload) {
		$params = new Parameters($request->query->all());
		$serializer = Action::getSerializer();
		$resource = new Resource($payload->getModel(), $serializer);
		$resource = $resource->with($params->getInclude(['module', 'groups', 'apis']));
		$resource = $resource->fields($params->getFields([
			'action' => Action::getSerializer()->getFields(),
			'module' => Module::getSerializer()->getFields(),
			'group' => Group::getSerializer()->getFields(),
			'api' => Api::getSerializer()->getFields()
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
