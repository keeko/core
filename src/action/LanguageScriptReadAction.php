<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\core\domain\LanguageScriptDomain;

/**
 * Action Class for language_script-read
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 */
class LanguageScriptReadAction extends AbstractAction {

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
		$domain = new LanguageScriptDomain($this->getServiceContainer());
		$payload = $domain->read($id);
		return $this->response->run($request, $payload);
	}
}
