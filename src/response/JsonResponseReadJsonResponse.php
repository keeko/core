<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Action;
use keeko\core\model\Module;
use keeko\core\model\Group;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponse for Reads an action
 * 
 * @author gossi
 */
class JsonResponseReadJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$params = new Parameters($request->query->all());
		$serializer = Action::getSerializer();
		$resource = new Resource($data, $serializer);
		$resource = $resource->with($params->getInclude(['module', 'groups']));
		$resource = $resource->fields($params->getFields([
			'action' => Action::getSerializer()->getFields(),
			'module' => Module::getSerializer()->getFields(),
			'groups' => Group::getSerializer()->getFields()
		]));
		$document = new Document($resource);

		return new JsonResponse($document->toArray(), 200);
	}
}
