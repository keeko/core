<?php
namespace keeko\core\responder\json\model;

use keeko\core\model\Activity;
use keeko\core\model\Group;
use keeko\core\model\Session;
use keeko\core\model\User;
use keeko\framework\domain\payload\Found;
use keeko\framework\foundation\AbstractPayloadResponder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponder for Paginates users
 * 
 * @author Thomas Gossmann
 */
class UserPaginateJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Found $payload
	 */
	public function found(Request $request, Found $payload) {
		$params = new Parameters($request->query->all());
		$data = $payload->getModel();
		$serializer = User::getSerializer();
		$resource = new Collection($data, $serializer);
		$resource = $resource->with($params->getInclude(['sessions', 'groups', 'activities']));
		$resource = $resource->fields($params->getFields([
			'user' => User::getSerializer()->getFields(),
			'session' => Session::getSerializer()->getFields(),
			'group' => Group::getSerializer()->getFields(),
			'activity' => Activity::getSerializer()->getFields()
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
