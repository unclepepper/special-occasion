<?php

declare(strict_types = 1);

namespace App\Domain\Users\Type;



use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

class UserUid
{
    public const TYPE = 'user_id';

    private Uuid $value;


    public function __construct(AbstractUid|self|string|null $value = null,) {

        if(null === $value) {
            $value = Uuid::v4();
        }

        if(is_string($value)) {
            $value = new Uuid($value);
        }

        $this->value = $value;

    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function getValue(): Uuid
    {
        return $this->value;
    }

    public static function isUid(mixed $value): bool
    {
        return preg_match('{^[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}$}Di', $value);
    }

}