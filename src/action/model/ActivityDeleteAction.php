<?php
namespace keeko\core\action\model;

use keeko\core\domain\ActivityDomain;
use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Deletes an activity
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author Thomas Gossmann
 */
class ActivityDeleteAction extends AbstractAction {

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
		$domain = new ActivityDomain($this->getServiceContainer());
		$payload = $domain->delete($id);
		return $this->responder->run($request, $payload);
	}
}
