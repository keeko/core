<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\domain\LocalizationDomain;

/**
 * Action Class for localization-create
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 */
class LocalizationCreateAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());
		$domain = new LocalizationDomain($this->getServiceContainer());
		$payload = $domain->create($data);
		return $this->response->run($request, $payload);
	}
}
