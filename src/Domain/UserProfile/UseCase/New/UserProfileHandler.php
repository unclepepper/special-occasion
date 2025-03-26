<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\UseCase\New;

use App\Domain\Common\Handler\AbstractHandler;
use App\Domain\UserProfile\Entity\Event\UserProfileEvent;
use App\Domain\UserProfile\Entity\Event\UserProfileEventInterface;
use App\Domain\UserProfile\Entity\UserProfile;
use ReflectionException;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

class UserProfileHandler extends AbstractHandler
{
    /**
     * @throws ReflectionException
     * @throws ClassNotFoundException
     */
    public function handle(UserProfileEventInterface $command): false|string|UserProfile
    {
        $this->setCommand($command);

        $this->createOrUpdate(UserProfile::class, UserProfileEvent::class);

        $this->flush();

        return $this->root;
    }
}
