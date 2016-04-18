<?php
namespace keeko\core\responder;

use keeko\framework\domain\payload\PayloadInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\framework\foundation\AbstractPayloadResponder;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use keeko\core\model\Country;
use keeko\core\model\Continent;
use keeko\core\model\Currency;
use keeko\core\model\RegionType;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponder for Reads a country
 * 
 * @author gossi
 */
class CountryReadJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param PayloadInterface $payload
	 */
	public function found(Request $request, PayloadInterface $payload) {
		$params = new Parameters($request->query->all());
		$serializer = Country::getSerializer();
		$resource = new Resource($payload->getModel(), $serializer);
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

	/**
	 * @param Request $request
	 * @param PayloadInterface $payload
	 */
	public function notFound(Request $request, PayloadInterface $payload) {
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
