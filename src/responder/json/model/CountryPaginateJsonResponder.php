<?php
namespace keeko\core\responder\json\model;

use keeko\core\model\Continent;
use keeko\core\model\Country;
use keeko\core\model\Currency;
use keeko\core\model\RegionType;
use keeko\core\model\Subdivision;
use keeko\framework\domain\payload\Found;
use keeko\framework\foundation\AbstractPayloadResponder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponder for Paginates countries
 * 
 * @author Thomas Gossmann
 */
class CountryPaginateJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Found $payload
	 */
	public function found(Request $request, Found $payload) {
		$params = new Parameters($request->query->all());
		$data = $payload->getModel();
		$serializer = Country::getSerializer();
		$resource = new Collection($data, $serializer);
		$resource = $resource->with($params->getInclude(['continent', 'currency', 'type', 'subtype', 'subordinates', 'country', 'subdivisions']));
		$resource = $resource->fields($params->getFields([
			'country' => Country::getSerializer()->getFields(),
			'continent' => Continent::getSerializer()->getFields(),
			'currency' => Currency::getSerializer()->getFields(),
			'type' => RegionType::getSerializer()->getFields(),
			'subtype' => RegionType::getSerializer()->getFields(),
			'subordinate' => Country::getSerializer()->getFields(),
			'subdivision' => Subdivision::getSerializer()->getFields()
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
