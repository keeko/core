<?php
namespace keeko\core\responder;

use keeko\framework\domain\payload\PayloadInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\framework\foundation\AbstractPayloadResponder;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use keeko\core\model\Localization;

/**
 * Automatically generated JsonResponder for Reads the relationship of localization to language_variant
 * 
 * @author gossi
 */
class LocalizationLanguageVariantJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param PayloadInterface $payload
	 */
	public function notFound(Request $request, PayloadInterface $payload) {
		throw new ResourceNotFoundException($payload->getMessage());
	}

	/**
	 * @param Request $request
	 * @param PayloadInterface $payload
	 */
	public function notUpdated(Request $request, PayloadInterface $payload) {
		return new JsonResponse(null, 204);
	}

	/**
	 * @param Request $request
	 * @param PayloadInterface $payload
	 */
	public function notValid(Request $request, PayloadInterface $payload) {
		throw new ValidationException($payload->getViolations());
	}

	/**
	 * @param Request $request
	 * @param PayloadInterface $payload
	 */
	public function updated(Request $request, PayloadInterface $payload) {
		$serializer = Localization::getSerializer();
		$relationship = $serializer->languageVariants($payload->getModel());

		return new JsonResponse($relationship->toArray());
	}

	/**
	 */
	protected function getPayloadMethods() {
		return [
			'keeko\framework\domain\payload\NotFound' => 'notFound',
			'keeko\framework\domain\payload\NotValid' => 'notValid',
			'keeko\framework\domain\payload\Updated' => 'updated',
			'keeko\framework\domain\payload\NotUpdated' => 'notUpdated'
		];
	}
}
