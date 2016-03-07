<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\core\model\LanguageScriptQuery;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\LanguageScriptReadAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait LanguageScriptReadActionTrait {

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
		// read
		$id = $this->getParam('id');
		$languageScript = LanguageScriptQuery::create()->findOneById($id);

		// check existence
		if ($languageScript === null) {
			throw new ResourceNotFoundException('languageScript not found.');
		}

		// run response
		return $this->response->run($request, $languageScript);
	}
}
