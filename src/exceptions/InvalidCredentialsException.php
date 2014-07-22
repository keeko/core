<?php
namespace keeko\core\exceptions;

class InvalidCredentialsException extends \Exception {

	public function __construct($message = 'Invalid Credentionals') {
		parent::__construct($message);
	}
}
