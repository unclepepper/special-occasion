<?php

namespace App\Domain\Users\Repository;

use App\Domain\Users\Entity\User;
use App\Domain\Users\Type\UserUid;

interface GetUserByIdRepositoryInterface
{
    public function getById(UserUid $userUid): ?User;
}