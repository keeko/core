<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tobscure\JsonApi\Parameters;
use keeko\core\domain\GroupDomain;

/**
 * Action Class for group-list
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 */
class GroupListAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$params = new Parameters($request->query->all());
		$domain = new GroupDomain($this->getServiceContainer());
		$payload = $domain->paginate($params);
		return $this->response->run($request, $payload);
	}
}
