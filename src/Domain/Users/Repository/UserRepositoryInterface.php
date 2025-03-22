<?php

namespace App\Domain\Users\Repository;

use App\Domain\Users\Entity\User;

interface UserRepositoryInterface
{
    public function findUserById($id): ?User;
}