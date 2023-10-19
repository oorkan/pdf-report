<?php

namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class NonceVerified extends Constraint
{
    public string $message = "Nonce is not verified.";

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
