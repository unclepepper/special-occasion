<?php

declare(strict_types=1);

namespace App\Domain\UserProfile\UseCase\New;

use App\Domain\Common\Handler\AbstractHandler;
use App\Domain\UserProfile\Dto\UserProfileDto;
use App\Domain\UserProfile\Entity\Event\UserProfileEvent;
use App\Domain\UserProfile\Entity\UserProfile;
use ReflectionException;
use Symfony\Component\VarExporter\Exception\ClassNotFoundException;

class UserProfileHandler extends AbstractHandler
{
    /**
     * @throws ReflectionException
     * @throws ClassNotFoundException
     */
    public function handle(UserProfileDto $command): false|UserProfile
    {
        $this->setCommand($command);

        $this->createOrUpdate(UserProfile::class, UserProfileEvent::class);

        $this->flush();

        if (!$this->root instanceof UserProfile) {
            return false;
        }

        return $this->root;
    }
}
