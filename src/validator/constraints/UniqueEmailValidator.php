<?php
namespace keeko\core\validator\constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use keeko\core\model\UserQuery;

class UniqueEmailValidator extends ConstraintValidator {
	
	public function validate($value, Constraint $constraint) {
		$users = UserQuery::create()->filterByEmail($value)->count();
		if ($users > 0) {
			$this->context->buildViolation($constraint->message)
				->setParameter('email', $value)
				->addViolation();
		}
	}
}