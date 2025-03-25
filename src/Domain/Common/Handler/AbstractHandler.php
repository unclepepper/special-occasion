<?php

declare(strict_types=1);

namespace App\Domain\Common\Handler;

use App\Domain\UserProfile\Entity\Event\UserProfileEvent;
use App\Domain\UserProfile\Entity\Event\UserProfileEventInterface;
use App\Domain\UserProfile\Entity\UserProfile;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Cache\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

abstract class AbstractHandler
{
    protected UserProfile $root;
    private UserProfileEventInterface $command;
    private UserProfileEvent $event;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function flush(bool $flush = true): void
    {
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    protected function setCommand(UserProfileEventInterface $command): self
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @throws ReflectionException
     * @throws ClassNotFoundException
     */
    protected function persistOrUpdate(object|string $root, object|string $event): void
    {
        if (is_string($root) && class_exists($root)) {
            // @phpstan-ignore-next-line
            $this->root = new $root();
        }

        if (is_string($event) && class_exists($event)) {
            // @phpstan-ignore-next-line
            $this->event = new $event();
        }

        if (!$this->command->event) {
            $this->event->setRoot($this->root);
            $this->event->setEntity($this->command);

            $this->root->setEvent($this->event);

            $this->entityManager->clear();
            //            $this->entityManager->persist($this->event);
            $this->entityManager->persist($this->root);
        } else {
            /** Получаем активное событие */
            $EventClass    = $this->event::class;
            $currentEvent  = $this->entityManager
                ->getRepository($EventClass)
                ->find($this->command->event)
            ;

            if (!isset($currentEvent)) {
                throw new InvalidArgumentException();
            }

            $rootClass     = $this->root::class;
            $rootFromRepo  = $this->entityManager
                ->getRepository($rootClass)
                ->findOneBy(['event' => $this->command->event])
            ;

            if (null === $rootFromRepo) {
                throw new NotFoundHttpException(sprintf('%s is deleted or event is not actual', $rootClass));
            }

            $this->root = $rootFromRepo;

            $this->event->setRoot($this->root);

            $dtoReflection = new ReflectionClass($this->command);

            $EventRepoReflection = new ReflectionClass($currentEvent);

            $dtoReflectionProperties = $dtoReflection->getProperties();

            foreach ($dtoReflectionProperties as $dtoProperty) {
                $property = $dtoProperty->getName();

                if (!property_exists($currentEvent, $property)) {
                    continue;
                }

                $repoProperty = $EventRepoReflection->getProperty($property);

                // Получаем значения свойства для обоих объектов
                $repoValue = $repoProperty->getValue($currentEvent);
                $dtoValue  = $dtoProperty->getValue($this->command);

                if (null === $dtoValue) {
                    $this->command->{$property} = $repoValue;
                }
            }

            $this->event->setEntity($this->command);
            $this->root->setEvent($this->event);
        }

        $this->entityManager->persist($this->event);
    }
}
