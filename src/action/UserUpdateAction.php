<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\domain\UserDomain;

/**
 * Action Class for user-update
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 */
class UserUpdateAction extends AbstractAction {

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureParams(OptionsResolver $resolver) {
		$resolver->setRequired(['id']);
	}

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$id = $this->getParam('id');
		$data = Json::decode($request->getContent());
		$domain = new UserDomain($this->getServiceContainer());
		$payload = $domain->update($id, $data);
		return $this->response->run($request, $payload);
	}
}