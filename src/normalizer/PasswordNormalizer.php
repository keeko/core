<?php
namespace keeko\core\normalizer;

use keeko\framework\normalizer\AbstractNormalizer;

class PasswordNormalizer extends AbstractNormalizer {
	
	public function normalize($value) {
		$authManager = $this->service->getAuthManager();
		return $authManager->encryptPassword($value);
	}
}