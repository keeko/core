<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\domain\UserDomain;

/**
 * Action Class for user-create
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 */
class UserCreateAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());
		$domain = new UserDomain($this->getServiceContainer());
		$payload = $domain->create($data);
		return $this->response->run($request, $payload);
	}
}