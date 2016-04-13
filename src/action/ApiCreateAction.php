<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\domain\ApiDomain;

/**
 * Action Class for api-create
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 */
class ApiCreateAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());
		$domain = new ApiDomain($this->getServiceContainer());
		$payload = $domain->create($data);
		return $this->response->run($request, $payload);
	}
}
