<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\Package;
use keeko\core\model\PackageQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\PackageUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait PackageUpdateActionTrait {

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
		$package = PackageQuery::create()->findOneById($id);

		// check existence
		if ($package === null) {
			throw new ResourceNotFoundException('Package not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = Package::getSerializer();
		$package = $serializer->hydrate($package, $data);

		// validate
		if (!$package->validate()) {
			throw new ValidationException($package->getValidationFailures());
		} else {
			$package->save();
			return $this->response->run($request, $package);
		}
	}
}
