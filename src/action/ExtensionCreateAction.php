<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use Tobscure\JsonApi\Exception\InvalidParameterException;
use keeko\core\domain\ExtensionDomain;

/**
 * Creates an extension
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
class ExtensionCreateAction extends AbstractAction {

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
		$domain = new ExtensionDomain($this->getServiceContainer());
		$payload = $domain->create($data);
		return $this->responder->run($request, $payload);
	}
}