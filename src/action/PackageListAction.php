<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tobscure\JsonApi\Parameters;
use keeko\core\domain\PackageDomain;

/**
 * Action Class for package-list
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 */
class PackageListAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$params = new Parameters($request->query->all());
		$domain = new PackageDomain($this->getServiceContainer());
		$payload = $domain->paginate($params);
		return $this->response->run($request, $payload);
	}
}
