<?php

declare(strict_types=1);

namespace App\Domain\Common\Handler;

use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;
use Symfony\Component\Cache\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractHandler
{
    protected object $root;
    private object $command;
    private object $event;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function flush(bool $flush = true): void
    {
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    protected function setCommand(object $command): self
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @throws ReflectionException
     */
    protected function createOrUpdate(object|string $root, object|string $event): void
    {
        if (is_string($root) && class_exists($root)) {
            $this->root = new $root();
        }

        if (is_string($event) && class_exists($event)) {
            $this->event = new $event();
        }

        // TODO:  solve the problem
        // @phpstan-ignore-next-line
        if (!$this->command->event) {
            $this->saveEntity();
        } else {
            $this->updateEntity();
        }

        $this->entityManager->persist($this->event);
    }

    /**
     * @throws ReflectionException
     */
    private function saveEntity(): void
    {
        // TODO:  solve the problem
        // @phpstan-ignore-next-line
        $this->event->setRoot($this->root);

        // TODO:  solve the problem
        // @phpstan-ignore-next-line
        $this->event->setEntity($this->command);

        // TODO:  solve the problem
        // @phpstan-ignore-next-line
        $this->root->setEvent($this->event);

        $this->entityManager->clear();

        $this->entityManager->persist($this->root);
    }

    /**
     * @throws ReflectionException
     */
    private function updateEntity(): void
    {
        /** Получаем переданное в дто событие событие */
        $EventClass   = $this->event::class;
        $currentEvent = $this->entityManager
            ->getRepository($EventClass)
            // TODO:  solve the problem
            // @phpstan-ignore-next-line
            ->find($this->command->event)
        ;

        if (!isset($currentEvent)) {
            throw new InvalidArgumentException();
        }

        /** По событию получаем корень агрегата */
        $rootClass    = $this->root::class;
        $rootFromRepo = $this->entityManager
            ->getRepository($rootClass)
            // TODO:  solve the problem
            // @phpstan-ignore-next-line
            ->findOneBy(['event' => $this->command->event])
        ;

        if (null === $rootFromRepo) {
            throw new NotFoundHttpException(sprintf('%s is deleted or event is not actual', $rootClass));
        }

        $this->root = $rootFromRepo;

        /** Если событие актуальное, то в новое событие сетим корень */

        // TODO:  solve the problem
        // @phpstan-ignore-next-line
        $this->event->setRoot($this->root);

        // TODO:  solve the problem
        // @phpstan-ignore-next-line
        $this->event->setEntityManager($this->entityManager);

        // TODO:  solve the problem
        // @phpstan-ignore-next-line
        $this->event->setEntity($this->command);

        // TODO:  solve the problem
        // @phpstan-ignore-next-line
        $this->root->setEvent($this->event);
    }
}
