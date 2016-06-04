<?php
namespace keeko\core\action\model;

use keeko\core\domain\ActionDomain;
use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Reads an action
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author Thomas Gossmann
 */
class ActionReadAction extends AbstractAction {

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureParams(OptionsResolver $resolver) {
		$resolver->setRequired(['id']);
		$resolver->setDefaults(['include' => [], 'fields' => []]);
	}

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$id = $this->getParam('id');
		$domain = new ActionDomain($this->getServiceContainer());
		$payload = $domain->read($id);
		return $this->responder->run($request, $payload);
	}
}
