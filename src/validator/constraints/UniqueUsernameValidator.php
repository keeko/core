<?php
namespace keeko\core\validator\constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use keeko\core\model\UserQuery;

class UniqueUsernameValidator extends ConstraintValidator {
	
	public function validate($value, Constraint $constraint) {
		$users = UserQuery::create()->findByUserName($value);
		if (count($users) > 0) {
			$this->context->buildViolation($constraint->message)
				->setParameter('username', $value)
				->addViolation();
		}
	}
}