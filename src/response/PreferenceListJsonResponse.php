<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Preference;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponse for List all preferences
 * 
 * @author gossi
 */
class PreferenceListJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$params = new Parameters($request->query->all());
		$serializer = Preference::getSerializer();
		$resource = new Collection($data, $serializer);
		$resource = $resource->with($params->getInclude([]));
		$resource = $resource->fields($params->getFields([
			'preference' => Preference::getSerializer()->getFields()
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
}
