<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Type\UserUid;

/**
 * @method findAll()
 */
interface UserRepositoryInterface
{
    public function findUserById(string|UserUid $id): ?User;
}
