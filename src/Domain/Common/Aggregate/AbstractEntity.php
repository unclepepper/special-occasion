<?php

declare(strict_types=1);

namespace App\Domain\Common\Aggregate;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

abstract class AbstractEntity
{
    private EntityManagerInterface $entityManager;
    private object $dto;

    public function setEntityManager(EntityManagerInterface $entityManager): self
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @throws ReflectionException
     */
    protected function setEntity(object $dto): self
    {
        $this->dto = $dto;

        $dtoReflection           = new ReflectionClass($this->dto);
        $dtoReflectionProperties = $dtoReflection->getProperties();

        $currentEvent = false;

        // @phpstan-ignore-next-line
        if ($this->dto->event) {
            /** Получаем переданное в дто событие событие */
            $EventClass = $this::class;

            $currentEvent = $this->entityManager
                ->getRepository($EventClass)
                ->find($this->dto->event)
            ;

            if (!isset($currentEvent)) {
                throw new InvalidArgumentException('Event is not valid');
            }

            $EventCurrentReflection = new ReflectionClass($currentEvent);

            foreach ($dtoReflectionProperties as $dtoProperty) {
                $property = $dtoProperty->getName();

                if (!property_exists($currentEvent, $property)) {
                    continue;
                }

                $repoProperty = $EventCurrentReflection->getProperty($property);

                // Получаем значения свойства для обоих объектов
                if (empty($repoProperty->getValue($currentEvent))) {
                    continue;
                }

                $repoValue = $repoProperty->getValue($currentEvent);
                $dtoValue  = $dtoProperty->getValue($dto);

                if (null === $dtoValue) {
                    $dto->{$property} = $repoValue;
                }
            }
        }
        //        else {
        //            $thisReflection = new ReflectionClass($this);
        //
        //            foreach ($dtoReflectionProperties as $dtoProperty) {
        //                $property = $dtoProperty->getName();
        //
        //                if (!property_exists($this, $property)) {
        //                    continue;
        //                }
        //
        //                $thisReflectionProperty = $thisReflection->getProperty($property);
        //
        //                $oneToOne = $thisReflectionProperty->getAttributes(ORM\OneToOne::class);
        //
        //                if ($oneToOne) {
        //                    $oneToOneTargetClass = current($oneToOne)->getArguments()['targetEntity'];
        //
        //                    if (isset($oneToOneTargetClass)) {
        //                        $childDto = new ReflectionClass($oneToOneTargetClass->getMethod('getEntity')->getReturnType());
        //
        //                        $new = new $oneToOneTargetClass();
        //
        //                        var_dump($new);
        //                    }
        //                }
        //            }

        //            if(method_exists($thisReflectionProperty->getType(), 'getTypes'))
        //            {
        //                throw new InvalidArgumentException(
        //                    sprintf('Property %s can not include double value typehint', $thisReflectionProperty)
        //                );
        //            }
        //
        //            if(class_exists($thisReflectionProperty->getType()?->getName()))
        //            {
        //                $instanceClass = new ReflectionClass($thisReflectionProperty->getType()?->getName());
        //               if($instanceClass->isInstantiable()){
        //                   var_dump($instanceClass->getName());
        //               }
        //
        //
        //            }
        //        }

        return $this;
    }
}
