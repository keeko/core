<?php
namespace keeko\core\responder\json\model;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\framework\foundation\AbstractPayloadResponder;
use keeko\framework\domain\payload\NotFound;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\NotDeleted;

/**
 * Automatically generated JsonResponder for Deletes a group
 * 
 * @author gossi
 */
class GroupDeleteJsonResponder extends AbstractPayloadResponder {

	/**
	 * @param Request $request
	 * @param Deleted $payload
	 */
	public function deleted(Request $request, Deleted $payload) {
		return new JsonResponse(null, 204);
	}

	/**
	 * @param Request $request
	 * @param NotDeleted $payload
	 */
	public function notDeleted(Request $request, NotDeleted $payload) {
		return new \Exception($payload->getMessage());
	}

	/**
	 * @param Request $request
	 * @param NotFound $payload
	 */
	public function notFound(Request $request, NotFound $payload) {
		throw new ResourceNotFoundException($payload->getMessage());
	}

	/**
	 */
	protected function getPayloadMethods() {
		return [
			'keeko\framework\domain\payload\NotFound' => 'notFound',
			'keeko\framework\domain\payload\Deleted' => 'deleted',
			'keeko\framework\domain\payload\NotDeleted' => 'notDeleted'
		];
	}
}
