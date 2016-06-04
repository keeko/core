<?php
namespace keeko\core\action\model;

use keeko\core\domain\ActivityObjectDomain;
use keeko\framework\foundation\AbstractAction;
use phootwork\json\Json;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tobscure\JsonApi\Exception\InvalidParameterException;

/**
 * Creates an activity_object
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author Thomas Gossmann
 */
class ActivityObjectCreateAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$body = Json::decode($request->getContent());
		if (!isset($body['data'])) {
			throw new InvalidParameterException();
		}
		$data = $body['data'];
		$domain = new ActivityObjectDomain($this->getServiceContainer());
		$payload = $domain->create($data);
		return $this->responder->run($request, $payload);
	}
}
