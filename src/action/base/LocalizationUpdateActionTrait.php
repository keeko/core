<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\Localization;
use keeko\core\model\LocalizationQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\LocalizationUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait LocalizationUpdateActionTrait {

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
		$localization = LocalizationQuery::create()->findOneById($id);

		// check existence
		if ($localization === null) {
			throw new ResourceNotFoundException('Localization not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = Localization::getSerializer();
		$localization = $serializer->hydrate($localization, $data);

		// validate
		if (!$localization->validate()) {
			throw new ValidationException($localization->getValidationFailures());
		} else {
			$localization->save();
			return $this->response->run($request, $localization);
		}
	}
}
