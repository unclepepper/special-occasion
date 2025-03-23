<?php

namespace App\Domain\Users\Repository;

use App\Domain\Users\Entity\User;
use App\Domain\Users\Type\UserUid;

interface UserRepositoryInterface
{
    public function findUserById(string|UserUid $id): ?User;
}
