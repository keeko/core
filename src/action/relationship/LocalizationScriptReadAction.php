<?php
namespace keeko\core\action\relationship;

use keeko\core\domain\LocalizationDomain;
use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Reads the relationship of localization to script
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author Thomas Gossmann
 */
class LocalizationScriptReadAction extends AbstractAction {

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
		$domain = new LocalizationDomain($this->getServiceContainer());
		$payload = $domain->read($id);
		return $this->responder->run($request, $payload);
	}
}
