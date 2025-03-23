<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Type\UserUid;

interface UserRepositoryInterface
{
    public function findUserById(string|UserUid $id): ?User;
}
