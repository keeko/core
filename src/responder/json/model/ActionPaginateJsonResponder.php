<?php
namespace keeko\core\responder\json\model;

use keeko\core\model\Action;
use keeko\core\model\Api;
use keeko\core\model\Group;
use keeko\core\model\Module;
use keeko\framework\domain\payload\Found;
use keeko\framework\foundation\AbstractPayloadResponder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponder for Paginates actions
 * 
 * @author Thomas Gossmann
 */
class ActionPaginateJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Found $payload
	 */
	public function found(Request $request, Found $payload) {
		$params = new Parameters($request->query->all());
		$data = $payload->getModel();
		$serializer = Action::getSerializer();
		$resource = new Collection($data, $serializer);
		$resource = $resource->with($params->getInclude(['module', 'groups', 'apis']));
		$resource = $resource->fields($params->getFields([
			'action' => Action::getSerializer()->getFields(),
			'module' => Module::getSerializer()->getFields(),
			'group' => Group::getSerializer()->getFields(),
			'api' => Api::getSerializer()->getFields()
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
