<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\UseCase\New;

use App\Domain\UserProfile\Entity\Event\UserProfileEvent;
use App\Domain\UserProfile\Entity\Event\UserProfileEventInterface;
use App\Domain\UserProfile\Entity\UserProfile;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionException;

class UserProfileHandler
{
    private UserProfileEventInterface $command;
    private UserProfile $root;
    private UserProfileEvent $event;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function handle(UserProfileEventInterface $command): string|UserProfile
    {
        $this->setCommand($command);

        $this->persistOrUpdate(UserProfile::class, UserProfileEvent::class);

        $this->flush();

        return $this->root;
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    protected function setCommand(UserProfileEventInterface $command): self
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @throws ReflectionException
     */
    protected function persistOrUpdate(object|string $root, object|string $event): void
    {
        if (is_string($root) && class_exists($root)) {
            $root = new $root();
        }

        if (is_string($event) && class_exists($event)) {
            $event = new $event();
        }

        $this->root  = $root;
        $this->event = $event;

        if (is_null($this->command->event)) {
            $this->event->setRoot($this->root);
            $this->event->setEntity($this->command);

            $this->root->setEvent($this->event);

            $this->entityManager->clear();
            $this->entityManager->persist($this->event);
            $this->entityManager->persist($this->root);
        } else {
            /** Получаем активное событие */
            $EventClass = $this->event::class;
            $EventRepo  = $this->entityManager
                ->getRepository($EventClass)
                ->find($this->command->event)
            ;

            $rootClass  = $this->root::class;
            $this->root = $this->entityManager
                ->getRepository($rootClass)
                ->findOneBy(['event' => $this->command->event])
            ;

            $this->event->setRoot($this->root);

            $dtoReflection = new ReflectionClass($this->command);

            $EventRepoReflection = new ReflectionClass($EventRepo);

            $EventRepoReflectionProperties = $EventRepoReflection->getProperties();

            $dtoReflectionProperties = $dtoReflection->getProperties();

            foreach ($dtoReflectionProperties as $dtoProperty) {
                $property = $dtoProperty->getName();

                if (!property_exists($EventRepo, $property)) {
                    continue;
                }

                $repoProperty = $EventRepoReflection->getProperty($property);

                // Получаем значения свойства для обоих объектов
                $repoValue = $repoProperty->getValue($EventRepo);
                $dtoValue  = $dtoProperty->getValue($this->command);

                if (null === $dtoValue) {
                    $this->command->{$property} = $repoValue;
                }
            }

            $this->event->setEntity($this->command);
            $this->root->setEvent($this->event);
            $this->entityManager->persist($this->event);
        }
    }
}
