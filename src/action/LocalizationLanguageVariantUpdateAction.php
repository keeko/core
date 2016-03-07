<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Tobscure\JsonApi\Exception\InvalidParameterException;
use keeko\core\model\LocalizationVariantQuery;
use keeko\core\model\LocalizationQuery;
use keeko\core\model\LanguageVariantQuery;

/**
 */
class LocalizationLanguageVariantUpdateAction extends AbstractAction {

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
		$body = $request->getContent();
		if (!isset($body['data'])) {
			throw new InvalidParameterException();
		}
		$data = $body['data'];

		$id = $this->getParam('id');
		$localization = LocalizationQuery::create()->findOneById($id);

		if ($localization === null) {
			throw new ResourceNotFoundException('localization with id ' . $id . ' does not exist');
		}

		// remove all relationships before
		LocalizationVariantQuery::create()->filterByLocalization($localization)->delete();

		// add them
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				throw new InvalidParameterException();
			}
			$languageVariant = LanguageVariantQuery::create()->findOneById($entry['id']);
			$localization->addLanguageVariant($languageVariant);
			$localization->save();	
		}

		// run response
		return $this->response->run($request, $localization);
	}
}
