<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\core\domain\ApplicationDomain;

/**
 * Action Class for application-delete
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 */
class ApplicationDeleteAction extends AbstractAction {

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
		$domain = new ApplicationDomain($this->getServiceContainer());
		$payload = $domain->delete($id);
		return $this->response->run($request, $payload);
	}
}
