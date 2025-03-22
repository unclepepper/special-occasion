<?php

namespace App\Domain\Common\UuidType;

use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

abstract class Uid
{
    private Uuid $value;

    public function __construct(AbstractUid|self|string|null $value = null)
    {

        if(null === $value)
        {
            $value = Uuid::v4();
        }

        if(is_string($value))
        {
            $value = new Uuid($value);
        }

        $this->value = $value;

    }

    public function getValue(): Uuid
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function equals(mixed $value): bool
    {
        if($value === null)
        {
            return false;
        }

        if(is_string($value))
        {
            return (string) $this->value === $value;
        }

        return (string) $this->value === (string) $value->value;
    }
}