<?php
namespace keeko\core\validator\constraints;

use Symfony\Component\Validator\Constraint;

class UniqueUsername extends Constraint {
	
	public $message = 'Username %username% is already been taken';
	
	public function validatedBy() {
		return get_class($this).'Validator';
	}
}