<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\Country;
use keeko\core\model\Continent;
use keeko\core\model\Currency;
use keeko\core\model\RegionType;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponse for Reads a country
 * 
 * @author gossi
 */
class CountryReadJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$params = new Parameters($request->query->all());
		$serializer = Country::getSerializer();
		$resource = new Resource($data, $serializer);
		$resource = $resource->with($params->getInclude(['continent', 'currency', 'region-type', 'region-type', 'country']));
		$resource = $resource->fields($params->getFields([
			'country' => Country::getSerializer()->getFields(),
			'continent' => Continent::getSerializer()->getFields(),
			'currency' => Currency::getSerializer()->getFields(),
			'region-type' => RegionType::getSerializer()->getFields()
		]));
		$document = new Document($resource);

		return new JsonResponse($document->toArray(), 200);
	}
}
