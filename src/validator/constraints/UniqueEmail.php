<?php
namespace keeko\core\validator\constraints;

use Symfony\Component\Validator\Constraint;

class UniqueEmail extends Constraint {
	
	public $message = 'Email %email% is already been taken';
	
	public function validatedBy() {
		return get_class($this).'Validator';
	}
}