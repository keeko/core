<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use keeko\core\model\LocalizationQuery;

/**
 */
class LocalizationLanguageVariantReadAction extends AbstractAction {

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
		$localization = LocalizationQuery::create()->findOneById($id);

		if ($localization === null) {
			throw new ResourceNotFoundException('Localization with id ' . $id . ' does not exist');
		}

		// run response
		return $this->response->run($request, $localization);
	}
}
