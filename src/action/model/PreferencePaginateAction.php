<?php
namespace keeko\core\action\model;

use keeko\core\domain\PreferenceDomain;
use keeko\framework\foundation\AbstractAction;
use keeko\framework\utils\Parameters;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Paginates preferences
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author Thomas Gossmann
 */
class PreferencePaginateAction extends AbstractAction {

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureParams(OptionsResolver $resolver) {
		$resolver->setDefaults(['include' => [], 'fields' => [], 'sort' => [], 'filter' => [], 'page' => []]);
	}

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$params = new Parameters($request->query->all());
		$domain = new PreferenceDomain($this->getServiceContainer());
		$payload = $domain->paginate($params);
		return $this->responder->run($request, $payload);
	}
}
