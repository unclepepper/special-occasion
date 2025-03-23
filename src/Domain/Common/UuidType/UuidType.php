<?php

namespace App\Domain\Common\UuidType;

use App\Domain\Users\Type\UserUid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use RuntimeException;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

abstract class UuidType extends StringType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        $classType = $this->getClassType();

        if (null === $value) {
            return null;
        }

        if ($value instanceof AbstractUid) {
            return new $classType($value);
        }

        if (is_string($value)) {
            try {
                return new $classType(Uuid::fromString($value));
            } catch (RuntimeException) {
            }
        }

        throw ConversionException::conversionFailed($value, $this->getClassType());
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        $classType = $this->getClassType();

        if (null === $value) {
            return null;
        }

        if (is_string($value)) {
            try {
                return new $classType(Uuid::fromString($value));
            } catch (RuntimeException) {
            }
        }

        if ($value instanceof UserUid) {
            return $value->getValue();
        }

        throw ConversionException::conversionFailed($value, $this->getClassType());
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    abstract public function getClassType(): string;
}
