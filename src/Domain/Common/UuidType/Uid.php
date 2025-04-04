<?php

namespace App\Domain\Common\UuidType;

use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

abstract class Uid
{
    private Uuid $value;

    public function __construct(null|AbstractUid|self|string $value = null)
    {
        if (null === $value) {
            $value = Uuid::v7();
        }

        $this->value = new Uuid((string) $value);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function getValue(): Uuid
    {
        return $this->value;
    }

    public function equals(mixed $value): bool
    {
        if (null === $value) {
            return false;
        }

        if (is_string($value)) {
            return (string) $this->value === $value;
        }

        return (string) $this->value === (string) $value->value;
    }
}
