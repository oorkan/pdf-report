<?php

namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Hour extends Constraint
{
    public string $message = "This value is not a valid hour.";

    public $context = -1;

    #[HasNamedArguments]
    public function __construct(
        $context = -1,
        array $groups = null,
        mixed $payload = null
    ) {
        $this->context = $context;
        parent::__construct([], $groups, $payload);
    }
}
