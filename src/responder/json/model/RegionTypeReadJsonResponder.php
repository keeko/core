<?php
namespace keeko\core\responder\json\model;

use keeko\core\model\Country;
use keeko\core\model\RegionArea;
use keeko\core\model\RegionType;
use keeko\core\model\Subdivision;
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
 * Automatically generated JsonResponder for Reads a region_type
 * 
 * @author Thomas Gossmann
 */
class RegionTypeReadJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Found $payload
	 */
	public function found(Request $request, Found $payload) {
		$params = new Parameters($request->query->all());
		$serializer = RegionType::getSerializer();
		$resource = new Resource($payload->getModel(), $serializer);
		$resource = $resource->with($params->getInclude(['countries', 'subdivisions', 'area']));
		$resource = $resource->fields($params->getFields([
			'region-type' => RegionType::getSerializer()->getFields(),
			'country' => Country::getSerializer()->getFields(),
			'subdivision' => Subdivision::getSerializer()->getFields(),
			'area' => RegionArea::getSerializer()->getFields()
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
