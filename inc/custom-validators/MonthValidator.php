<?php

namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class MonthValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Month) {
            throw new UnexpectedTypeException($constraint, Month::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || "" === $value) {
            return;
        }

        if (!is_numeric($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, "numeric");

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, "string|int");
        }

        $months = [
            1  => "January",
            2  => "February",
            3  => "March",
            4  => "April",
            5  => "May",
            6  => "June",
            7  => "July",
            8  => "August",
            9  => "September",
            10 => "October",
            11 => "November",
            12 => "December"
        ];

        if (!array_key_exists($value, $months)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
