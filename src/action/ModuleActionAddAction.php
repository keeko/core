<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use Tobscure\JsonApi\Exception\InvalidParameterException;
use keeko\core\domain\ModuleDomain;

/**
 * Adds action as relationship to module
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
class ModuleActionAddAction extends AbstractAction {

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
		$body = Json::decode($request->getContent());
		if (!isset($body['data'])) {
			throw new InvalidParameterException();
		}
		$data = $body['data'];
		$id = $this->getParam('id');
		$domain = new ModuleDomain($this->getServiceContainer());
		$payload = $domain->addAction($id, $data);
		return $this->responder->run($request, $payload);
	}
}