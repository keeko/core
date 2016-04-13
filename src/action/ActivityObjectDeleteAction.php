<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\core\domain\ActivityObjectDomain;

/**
 * Action Class for activity_object-delete
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 */
class ActivityObjectDeleteAction extends AbstractAction {

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
		$domain = new ActivityObjectDomain($this->getServiceContainer());
		$payload = $domain->delete($id);
		return $this->response->run($request, $payload);
	}
}
