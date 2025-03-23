<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\Type\UserUid;

interface GetUserByIdRepositoryInterface
{
    public function getById(UserUid $userUid): ?User;
}
